<?php

include('enibuleConf.php');
include('enibuleDb.php');

enibuleConf::$databases = array(
		     'default' => array(
					  'host'     => '192.168.1.10',
					  'database' => 'cg_dev',
					  'login'    => 'cg_dev',
					  'password' => 'cty486dv'
));

$Db = new enibuleDb();
$Db->table='users';

/*$user = $Db->find(array(
	'fields' 	 => 'id, filename',
	'conditions' => array('id>'=>'1'),
	'order'		 => 'id DESC',
	'limit'	     => '0,3'
));*/

$user = $Db->find(array(
	'join' => array(
		array(
			'INNER' => array(
				'from' => 'apartments',
				'on'   => array(
					'id'=>'Apartment.user_id'
				)	
			)
		)
	),
	'conditions' => 'firstname != ""',
	'order'		 => 'User.id DESC',
	'limit'	     => '0,3'
));
print_r($user);die;

$user = $Db->find(array(
	'fields' => 'id, filename',
	'conditions' => array('id'=>'1')	
));
//print_r($user);die;

$user = $Db->find(1,false);
//print_r($user);

$user = $Db->findFirst();
print_r($user);

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