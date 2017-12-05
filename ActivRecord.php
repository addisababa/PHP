<?php
class ActivRecord{
	public static function db(){
		return Db::getInstance()->conn;		
	}
	public static function getAll($param=""){
		$conn = self::db();
		$q = $conn->query("select * from ".static::$table." {$param}");
		$q->setFetchMode(PDO::FETCH_CLASS,get_called_class());
		$output = [];
		while($rw=$q->fetch()){
			$output[]=$rw;
		}
		return $output;
	}
	public static function get($id){
		$conn = self::db();
		$q = $conn->query("select * from ".static::$table." where ".static::$key."={$id}");
		$q->setFetchMode(PDO::FETCH_CLASS,get_called_class());
		$res = $q->fetch();
		return $res;
	}
	public function save(){
		$conn = self::db();
		$q = "insert into ".static::$table;
		$filds = get_object_vars($this);
		$array_keys = array_keys($filds);
		$array_filds = array_values($filds);
		$q.="(".implode($array_keys,',').") values ";
		$q.= "('".implode($array_filds,"','")."')";
		$conn->exec($q);
		$conn->lastInsertId();		
	}
	public function update(){
		$conn = self::db();
		$q = "update ".static::$table." set ";
		$filds = get_object_vars($this);
		foreach($filds as $k=>$v){
			if($k==static::$key)continue;
			$q.= $k."= '".$v."' ,";
		}
		$q = rtrim($q,',');
		$id = static::$key;
		$q.= " where ".$id."=".$this->$id;
		$conn->exec($q);
	}
	public static function delete($id){
		$conn = self::db();
		$q = "delete from ".static::$table." where ".static::$key."={$id}";
		$conn->exec($q);
	}
}