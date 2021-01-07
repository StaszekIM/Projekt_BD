<?php

namespace {

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include "DBConnection.php";

    class CategoriesProxy
    {

        private static CategoriesProxy $instance;
        private static array $relations;
        private static \Ds\Map $categories;

        private function build()
        {

            $dbconn = Connection::getPDO();
            $stmt = $dbconn->prepare("select * from shop.categories");
            $success = $stmt->execute();

            if ($success) {

                $data = $stmt->fetchAll();
                $N = $stmt->rowCount();
                static::$categories -> allocate($N);
                // Initialize NxN matrix
                for ($i = 0; $i < $N; ++$i) {
                    static::$relations[i] = array();
                    for ($j = 0; $j < N; ++$j) {
                        static::$relations[i][j] = 0;
                    }
                }
                // 1 in $relations[i][j] means there's an edge in categories graph from i to j
                // that means i is subcategory of j (j is parent of i)
                foreach ($data as $row) {

                    $pid = $row['pid'];
                    $id = $row['id'];
                    static::$categories -> put($id, new CategoriesProxy\Category($id, $row['name'], $pid));

                    if ($pid) {
                        // Set relation to parent category
                        static::$relations[$id][$pid] = 1;
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
                static::$categories = new \Ds\Map();
                static::$instance->build();
            }

            return static::$instance;
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