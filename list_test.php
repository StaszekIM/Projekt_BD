<?php

include "CategoriesProxy.php";
$proxy = CategoriesProxy::get();
echo $proxy -> list_hierarchy_up("Czarne");

?>