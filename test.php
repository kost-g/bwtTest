<?php

require __DIR__  . '\DB.php';

require __DIR__ . '\DBQuery.php';

$db = DB::connect('mysql:host=localhost;dbname=bwt_test', "root", "");

//$db2 = new PDO('mysql:host=localhost;dbname=bwt_test', "root", "");
//
//$db1 = DB::connect('mysql:host=localhost;dbname=luna_db', "root", "");

$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,TRUE);

$query = new DBQuery($db);

print_r($query->queryAll('SELECT * FROM users'));

print_r($query->queryRow('SELECT * FROM users limit 1'));

print_r($query->queryColumn('SELECT email FROM users'));

echo $query->queryScalar('SELECT email FROM users');

$data = [
    'email' => 'zotov_mv+' . rand(1,99999) . '@groupbwt.com',
    'password' => password_hash('qwerty' . time() ,PASSWORD_DEFAULT)
];

$rowCount = $query->execute("INSERT INTO `users` (`email`, `password`) VALUES (:email, :password)", $data);

echo "\ncount inserts row -> " . $rowCount . "\n";

$lastId = $db->getLastInsertID();
print_r($lastId);
echo '</br>';

print_r($query->queryRow('SELECT * FROM users where id = :id', ['id' => $lastId]));

$updateData = [
    'password' => password_hash('qwerty' . time() ,PASSWORD_DEFAULT),
    'id' => $lastId
];

$rowCountUpdate = $query->execute("Update `users` SET password = :password where id = :id", $updateData);

echo "\ncount update row -> " . $rowCountUpdate . "\n";

$rowCountDelete = $query->execute("DELETE FROM `users` where id = :id", ['id' => $lastId]);

echo "\ncount delete row -> " . $rowCountDelete . "\n";

echo "\nlast query execution time -> ".$query->getLastQueryTime() . "\n";

$db->reconnect();