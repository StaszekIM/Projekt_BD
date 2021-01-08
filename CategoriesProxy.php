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
        private static \Ds\Map $categories;
        private static \Ds\Map $named_categories;

        private function build()
        {

            static::$categories = new \Ds\Map();
            static::$named_categories = new \Ds\Map();
            static::$relations['subcategories'] = new \Ds\Map();
            static::$relations['parent_categories'] = new \Ds\Map();
            $dbconn = Connection::getPDO();
            $stmt = $dbconn->prepare("select * from shop.categories");
            $success = $stmt->execute();

            if ($success) {

                $data = $stmt->fetchAll();
                $N = $stmt->rowCount();
                // Reserve appropriate space in data structures
                static::$categories -> allocate($N);
                static::$relations['subcategories'] -> allocate($N);
                static::$relations['parent_categories'] -> allocate($N);
                foreach ($data as $row) {

                    $pid = $row['pid'];
                    $id = $row['id'];
                    $name = $row['name'];
                    // Store information about category
                    $cat = new CategoriesProxy\Category($id, $name, $pid);
                    static::$categories -> put($id, $cat);
                    static::$named_categories -> put($name, $cat);

                    // It's not category from the top of the hierarchy
                    if ($pid != null) {
                        // static::$relations['parent_categories'].get($id).add($pid);
                        static::$relations['subcategories'].get($pid).add($id);
                    }// Relations for top categories are set by their subcategories (those can be skipped)

                }

                // At this point only direct parent is included. Building hierarchy
                $done = new \Ds\Set();
                foreach (static::$relations['subcategories'].keys() as $id){
                    // if ($done.contains($id)) continue;
                    $pid = static::get_parent_id($id);
                    while ($pid != null){
                        static::$relations['parent_categories'].get($id).add($pid);
                        $pid = static::get_parent_id($pid);
                    }
                }

            } else {
                throw new RuntimeException;
            }

        }

        public static function get()
        {
            if (null === static::$instance) {
                static::$instance = new static();
                static::$instance->build();
            }

            return static::$instance;
        }

        public function list_hierarchy_up(string $name){
            $id = static::$named_categories[$name].$id;
            return static::$relations['parent_categories'][$id];
        }

        private function get_parent_id(string $arg){

            if ($arg instanceof string) static::$named_categories.get($arg).$pid;
            elseif ($arg instanceof int) static::$categories.get($arg).$pid;

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
        public int $pid;

        public function __construct($id, $name, $pid){
            $this -> $id = $id;
            $this -> $name = $name;
            $this -> $pid = $pid;
        }

    }

}


?>