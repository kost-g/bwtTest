<?php

require __DIR__  . '\DB.php';

require __DIR__ . '\DBQuery.php';

$db = DB::connect('mysql:dbname = bwt_test; host=localhost', 'root', '');

$db2 = DB::connect('mysql:dbname = testnews; host=localhost', 'root', '');

$db1 = DB::connect('mysql:dbname = luna_db; host=localhost', 'root', '');

$db1->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db1->setAttribute( PDO::ATTR_CASE, PDO::CASE_LOWER);
//
//var_dump($db2);
//echo '<br>';
//
//var_dump($db1);
//echo '<br>';

$query = new DBQuery($db);
var_dump($query -> connectInstance);

//var_dump($connectInstance);
//echo '<br>';

//$attributes = array(
//    "AUTOCOMMIT", "ERRMODE", "CASE", "CLIENT_VERSION", "CONNECTION_STATUS",
//    "ORACLE_NULLS", "PERSISTENT", "SERVER_INFO", "SERVER_VERSION",
//);
//
//foreach ($attributes as $val) {
//    echo "PDO::ATTR_$val: ";
//    echo $db->getAttribute(constant("PDO::ATTR_$val")) . ";" . "<br>";
//}
//
//var_dump($db instanceof DBConnectionInterface);
//echo '<br>';
//
//var_dump($attrConnect);
//echo '<br>';
//
//var_dump($db);
//echo '<br>';
//
//$db->reconnect();
//echo "PDO::ATTR_ERRMODE: " . $db->getAttribute(constant("PDO::ATTR_ERRMODE"));
//echo '<br>';
//
//var_dump($db);
//echo '<br>';
//
//$arr = [1,2,3];
//foreach ($arr as $instance){
//    if ($instance >4 ){
//        return $instance;
//    }else{
//        return $arr[] =4;
//    }
//}