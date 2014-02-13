Enibule-db
=============

Enibule-db is an object-relational mapper.

Models are the classes that sit as the business layer in your application. This means that they should be responsible for managing almost everything that happens regarding your data, its validity, interactions and evolution of the information workflow in your domain of work.

To see more information on how to use enibule-db please visite : http://enibule.com or contact me at : contact@enibule.com






Example Finding Your Data
=========================


$Db = new enibuleDb();
$Db->table='users';



$getUsersId = $Db->find(array(
	'fields'  => 'id',
	'order'	  => 'id DESC',
	'limit'	  => '0,10'
));

$getUsersAssets = $Db->find(array(
	'fields'  => 'id, username, email',
	'join' => array(
		array(
			'INNER' => array(
				'from' => 'assets',
				'on'   => array(
					'id'=>'Asset.user_id'
				)	
			)
		)
	),
	'conditions' => 'Asset.online = 1',
	'order'		 => 'User.id DESC',
	'limit'	     => '0,10'
));

$getAdminUser = $Db->find(array(
	'fields' 	 => array('id', 'username'),
	'conditions' => array('role'=>'2')	
));


===== If you want to return data in an array format: 

$getAdminUser = $Db->find(array(
	'fields' 	 => array('id', 'username'),
	'conditions' => array('role'=>'2')	
),false);

===== Get first data

$user = $Db->findFirst();





Example Saving Your Data
=========================

$Db->save(array(
    'username' => 'HAMZA MASMOUDI',
    'email'    => 'contact@enibule.com',
    'country'  => 'Morocco'    
));

===== Get inserted id

$insertId = $Db->save(array(
    'username' => 'hamza.masmoudi',
    'email'    => 'contact@enibule.com',
    'country'  => 'Morocco'    
));


===== Updating Your data

$Db->save(array(
    'id'       => $insertId,
    'username' => 'enibule',
    'email'    => 'hamza@enibule.com'    
));





Example Deleting Your Data
=========================

$Db->delete($insertId);