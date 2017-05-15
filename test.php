<?php

require __DIR__  . '\DBConnectionInterface.php';

require __DIR__  . '\DB.php';

//require __DIR__ . '\DBQuery.php';

$db = DB::connect('mysql:dbname = bwt_test; host=localhost', 'root', '');

$attrConnect = $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$attributes = array(
    "AUTOCOMMIT", "ERRMODE", "CASE", "CLIENT_VERSION", "CONNECTION_STATUS",
    "ORACLE_NULLS", "PERSISTENT", "SERVER_INFO", "SERVER_VERSION",
);

foreach ($attributes as $val) {
    echo "PDO::ATTR_$val: ";
    echo $db->getAttribute(constant("PDO::ATTR_$val")) . ";" . "<br>";
}

var_dump($db instanceof DBConnectionInterface);
echo '<br>';

var_dump($attrConnect);
echo '<br>';

var_dump($db);
echo '<br>';

$db->reconnect();
echo "PDO::ATTR_ERRMODE: " . $db->getAttribute(constant("PDO::ATTR_ERRMODE"));
echo '<br>';

var_dump($db);
echo '<br>';