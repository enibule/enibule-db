<?php

include('enibuleConf.php');
include('enibuleDb.php');

enibuleConf::$databases = array(
		     'default' => array(
					  'host'     => 'localhost',
					  'database' => 'enibule',
					  'login'    => 'root',
					  'password' => ''
));

$Db = new enibuleDb();
$Db->table='users';

$user = $Db->find();
//print_r($user);

$user = $Db->find(array(),'array');
//print_r($user);

$user = $Db->findFirst();
//print_r($user);

//Add data
$insertId = $Db->save(array(
    'username' => 'HAMZA MASMOUDI',
    'email'    => 'contact@enibule.com'    
));

//Update data
$Db->save(array(
    'id'       => $insertId,
    'username' => 'NEW USER',
    'email'    => 'email@domaine.com'    
));

//Delete data
$Db->delete($insertId);