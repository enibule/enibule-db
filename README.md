Enibule-db
=============

Enibule-db is an object-relational mapper.

Models are the classes that sit as the business layer in your application. This means that they should be responsible for managing almost everything that happens regarding your data, its validity, interactions and evolution of the information workflow in your domain of work.

To see more information on how to use enibule-db please visite : http://enibule.com or contact me at : contact@enibule.com






Example Finding Your Data
=========================

<pre>
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

</pre>
===== If you want to return data in an array format: 
<pre>
$getAdminUser = $Db->find(array(
	'fields' 	 => array('id', 'username'),
	'conditions' => array('role'=>'2')	
),false);
</pre>
===== Get first data
<pre>
$user = $Db->findFirst();
</pre>




Example Saving Your Data
=========================
<pre>
$Db->save(array(
    'username' => 'HAMZA MASMOUDI',
    'email'    => 'contact@enibule.com',
    'country'  => 'Morocco'    
));
</pre>
===== Get inserted id
<pre>
$insertId = $Db->save(array(
    'username' => 'hamza.masmoudi',
    'email'    => 'contact@enibule.com',
    'country'  => 'Morocco'    
));
</pre>

===== Updating Your data
<pre>
$Db->save(array(
    'id'       => $insertId,
    'username' => 'enibule',
    'email'    => 'hamza@enibule.com'    
));
</pre>




Example Deleting Your Data
=========================
<pre>
$Db->delete($insertId);
</pre>