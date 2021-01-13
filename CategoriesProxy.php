<?php

namespace {

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include "DBConnection.php";

    class CategoriesProxy
    {

        private static ?CategoriesProxy $instance = null;
        /* Structure of relations:
         *  - subcategories and parent_categories
         *  - each of above has \Ds\Map storing \Ds\Set as values and using IDs as keys [Map<int, Set>]
         *    (a = static::$relations['subcategories'].get($id); - Get set of subcategories for category with $id) */
        private static array $relations;
        // Category ID -> Category class map (id, name, pid)
        private static array $categories;
        private static array $named_categories;

        /**
         * Downloads and processes data from data base then saves results in cache in DB
         */
        private function build()
        {

            static::$categories = array();
            static::$named_categories = array();
            static::$relations['subcategories'] = array();
            static::$relations['parent_categories'] = array();
            $dbconn = Connection::getPDO();
            $stmt = $dbconn->prepare("select * from shop.categories");
            $success = $stmt->execute();

            if ($success) {

                $data = $stmt->fetchAll();
                foreach ($data as $row) {

                    $pid = $row['parent_id'];
                    $id = $row['id'];
                    $name = $row['name'];
                    // Store information about category - for purpose of building cache, data available in DB,
                    // can be discarded later
                    $cat = new CategoriesProxy\Category($id, $name, $pid);
                    static::$categories[$id] = $cat;
                    static::$named_categories[$name] = $cat;

                    if ($pid != null) {
                        if (!array_key_exists($pid, static::$relations['subcategories'])) {
                            static::$relations['subcategories'][$pid] = array();
                        }
                        array_push(static::$relations['subcategories'][$pid], $id);
                    }

                }

                // Building hierarchy, parent categories are stored as list of IDs beginning with direct parent
                // and going up the hierarchy until top category with null as parent is reached - order is important
                foreach (static::$categories as $cat){
                    $id = $cat -> id;
                    $pid = self::get_parent_id($id);
                    // Go through the hierarchy
                    while ($pid != null){
                        if (!array_key_exists($id, static::$relations['parent_categories'])){
                            static::$relations['parent_categories'][$id] = array();
                        }
                        array_push(static::$relations['parent_categories'][$id], $pid);
                        $pid = self::get_parent_id($pid);
                    }
                }

                self::save();

            } else {
                throw new RuntimeException;
            }

        }

        // Singleton is used to ensure proper cache state (which is checked when get() is called)
        public static function get()
        {
            if (null === static::$instance) {
                static::$instance = new static();
            }
            if (self::is_cache_empty()) {
                static::$instance->build();
            }
            return static::$instance;
        }

        /**
         * @param string $name Name of the category
         * @return array Ordered list of IDs of parent categories, starting with direct one and going up
         */
        public function list_hierarchy_up(string $name){
            $id = self::get_id_by_name($name);
            if (static::$relations['parent_categories'] != null) {
                return static::$relations['parent_categories'][$id];
            }else {
                $dbconn = Connection::getPDO();
                $stmt = $dbconn -> prepare("select values from cache.parent_categories where id = :id");
                $success = $stmt -> execute([':id' => $id]);
                return $stmt -> fetch()['values'];
            }
        }

        public function get_subcategories(string $name){
            $id = self::get_id_by_name($name);
            $dbconn = Connection::getPDO();
            $subs = null;
            if (static::$relations['subcategories'] != null) {
                $subs = static::$relations['subcategories'][$id];
            }else {
                $stmt = $dbconn -> prepare("select values from cache.parent_categories where id = :id");
                $stmt -> execute([':id' => $id]);
                $subs =  $stmt -> fetch()['values'];
            }
            $res = array();
            foreach ($subs as $sid) {
                $stmt = $dbconn -> prepare("select name from shop.categories where id = :sid)");
                $stmt -> execute();
                array_push($res, $stmt -> fetch()['name']);
            }
            return $res;
        }

        /**
         * @param $arg String or int representing category of interest
         * @return mixed ID of parent
         */
        private function get_parent_id($arg){

            if (is_string($arg)) {
                // Get data from program if it's available
                if (static::$named_categories != null) return static::$named_categories[$arg] -> pid;
                // Otherwise query DB for data
                else {
                    $dbconn = Connection::getPDO();
                    $stmt = $dbconn -> prepare("select pid from shop.categories where name = :name");
                    $success = $stmt -> execute(['name' => $arg]);
                    if ($success) return $stmt -> fetch()['pid'];
                    else throw new RuntimeException();
                }
            }
            elseif (is_int($arg)) {
                if (static::$categories != null) return static::$categories[$arg] -> pid;
                else {
                    $dbconn = Connection::getPDO();
                    $stmt = $dbconn -> prepare("select pid from shop.categories where id = :id");
                    $success = $stmt -> execute(['id' => $arg]);
                    if ($success) return $stmt -> fetch()['pid'];
                    else throw new RuntimeException();
                }
            }
            else throw new InvalidArgumentException();

        }

        private function get_id_by_name(string $name){
            if (static::$named_categories != null) static::$named_categories[$name] -> id;
            else {
                $dbconn = Connection::getPDO();
                $stmt = $dbconn -> prepare("select id from shop.categories where name = :name");
                $success = $stmt -> execute(['name' => $name]);
                if ($success) return $stmt -> fetch()['id'];
                else throw new RuntimeException();
            }
        }

        private static function is_cache_empty(){
            $dbconn = Connection::getPDO();
            $stmt = $dbconn -> prepare("select id from cache.subcategories limit 1");
            $success = $stmt -> execute();
            if ($success){
                if ($stmt -> rowCount()){
                    return false;
                }
                return true;
            }
            return true;
        }

        private function save() {

            $dbconn = Connection::getPDO();
            $stmt = $dbconn -> prepare('insert into cache.subcategories (id, "values") values (:id, :vals)');
            foreach (array_keys(static::$relations['subcategories']) as $key){
                $id = $key;
                $vals = static::$relations['subcategories'][$id];
                $stmt -> execute(['id' => $id, 'vals' => $this->to_pg_array($vals)]);
            }


        }

        private function to_pg_array($set) {
            settype($set, 'array'); // can be called with a scalar or array
            $result = array();
            foreach ($set as $t) {
                if (is_array($t)) {
                    $result[] = to_pg_array($t);
                } else {
                    $t = str_replace('"', '\\"', $t); // escape double quote
                    if (! is_numeric($t)) // quote only non-numeric values
                        $t = '"' . $t . '"';
                    $result[] = $t;
                }
            }
            return '{' . implode(",", $result) . '}'; // format
        }

        protected function __construct()
        {

        }

        private function __clone()
        {

        }

        private function __wakeup()
        {

        }

    }

}

namespace CategoriesProxy{

    class Category {

        public int $id;
        public string $name;
        public ?int $pid;

        public function __construct($id, $name, $pid){
            $this -> id = $id;
            $this -> name = $name;
            $this -> pid = $pid;
        }

    }

}


?>