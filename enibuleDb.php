<?php

/**
	 * Author : HAMZA MASMOUDI | contact@enibule.com
	 * WebSite : www.enibule.com
	 * Version : 1.2
**/

class enibuleDb{
    
	static $connections = array();
	public $conf = 'default';
	public $table = false;
	public $db;
	public $primaryKey = 'id';
	public $id;
	public $errors = array();
	public $form;
    	
	/**
	 * construct of class 
	*/	
	public function __construct(){
        
		$conf = enibuleConf::$databases[$this->conf];
		if(isset(enibuleDb::$connections[$this->conf])){
			$this->db = enibuleDb::$connections[$this->conf];
			
			return true;
		}
		
			try{
				$pdo = new PDO('mysql:host='.$conf['host'].';
				                dbname='.$conf['database'].';',
								$conf['login'],
								$conf['password'],
								array(
								PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8'
								));
				enibuleDb::$connections[$this->conf] = $pdo;
				$this->db = $pdo;
			}catch(PDOException $e){
				if(enibuleConf::$debug >=1){
				   die($e->getMessage());
				}else{
				   die('Impossible de se connecter à la base de donnée');	
				}
			}
	}
		
    /**
	 * Find
	 *
	 * @param array $req
	 * @return stdClass Object
	*/	
	public function find($req = array(),$return='OBJ'){
		$sql = 'SELECT ';
		if(isset($req['fields'])){
			if(is_array($req['fields'])){
			   $sql .= implode(', ',$req['fields']);	
			}else{
			   $sql .= $req['fields'];	
			}
		}else{
		    $sql .='*';	
		}
		
		$sql .= ' FROM '.$this->table. ' as '.ucfirst(rtrim($this->table, 's')).' ';
		if(isset($req['join'])){
			if (!is_array($req['join'])) 
				$sql .= ' JOIN  '. $req['join'].' as '.ucfirst(rtrim($req['join'], 's')). ' ';
			else {
				$join = array();
				foreach ($req['join'] as $k => $v) {
					$join[] = "$v JOIN $k as ".ucfirst(rtrim($k, 's'));
				}
				$sql .= implode(' ', $join). ' ';
				
			}

				if(isset($req['on'])){
					$sql .= ' ON ';
					if (is_array($req['on'])) {
						$on = array();
						foreach($req['on'] as $k=>$v){
							$on[] = "$k=$v";
						}
						$sql .= implode(' AND ',$on). ' ';
					}else{
						$sql .= $req['on']. ' ';
					}
				}
		}
		if(isset($req['conditions'])){
			$sql .= 'WHERE ';
			if(!is_array($req['conditions'])){
				$sql .= $req['conditions'];
			}else{
				$cond = array();
				foreach($req['conditions'] as $k=>$v){
					if(!is_numeric($v)){
					    $v = '"'. mysql_escape_string($v).'"';
					}
					$cond[] = "$k=$v";
				}
				$sql .= implode(' AND ',$cond);
			}
		}
		if(isset($req['order'])){
			$sql .= ' ORDER BY '.$req['order'];
		}
		if(isset($req['limit'])){
			$sql .= ' Limit '.$req['limit'];
		}

		$pre = $this->db->prepare($sql);
		$pre->execute();
		if($return=='OBJ'){
		  return $pre->fetchAll(PDO::FETCH_OBJ);
		}else{
		  return $pre->fetchAll(PDO::FETCH_ASSOC);	
		}
	}
	
	/**
	 * Find first Data
	 *
	 * @param array $req
	 * @return stdClass Object
	*/	
	public function findFirst($req=array()){
        return current($this->find($req));
	}
	
	/**
	 * Find first Data
	 *
	 * @param array $conditions
	 * @return integer
	*/	
	public function findCount($conditions){
        $res = $this->findFirst(array(
		           'fields'     => 'COUNT('.$this->primaryKey.') as count',
			       'conditions' => $conditions
		));
		return $res->count;
	}
	
	/**
	 * Delete Data
	 *
	 * @param integer $id
	*/	
	public function delete($id){
		$sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = $id";
		$this->db->query($sql);
	}
	
	/**
	 * Save Data
	 *
	 * @param stdClass Object $data
	 * @return integer
	*/	
	public function save($data){
		$key = $this->primaryKey;
		$fields =array();
		$d = array();
		foreach($data as $k=>$v){
			$fields[]="$k=:$k";
			$d[":$k"]=$v;
	    }
	
		if(isset($data->$key) && !empty($data->$key)){
		    $sql = 'UPDATE '.$this->table.' SET '.implode(', ',$fields).' WHERE '.$key.'=:'.$key;
			$action = 'update';
		}else{
			if(isset($data->$key)) unset($data->$key);
			$sql = 'INSERT INTO '.$this->table.' SET '.implode(', ',$fields);
			$action = 'insert';
		}		
        
        $pre = $this->db->prepare($sql);
		$pre->execute($d);
		return $this->db->lastInsertId();
		
		if($action == 'insert'){
			  $this->id = $this->db->lastInsertId();
		}elseif($action == 'update'){
			$this->id = $data->$key;
		}
	}
	
	/**
	 * validate DATA
	**/
	public function validate($data,$d){
	  $errors = array();
	  foreach($d as $k=>$v){
		   if(!isset($data->$k)){
			   $errors[$k] = $v['message'];
		   }else{
			   if($v['rule'] == 'notEmpty'){
				   if(empty($data->$k)){
				       $errors[$k] = $v['message'];  
				   }
			   }elseif(!preg_match('/^'.$v['rule'].'$/',$data->$k)){
				   $errors[$k] = $v['message'];  
			   }
		   }
	  }
	  
	  if(empty($errors)){
	  	 return true;
	  }else{
	  	 $er;
	     foreach($errors as $k=>$v){
		   $er[]= $v;
	     }
	     return implode('</br>', $er);
	  }
   }
}

?>