<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "CategoriesProxy.php";
$proxy = CategoriesProxy::get();
echo $proxy -> list_hierarchy_up("Czarne");

?>