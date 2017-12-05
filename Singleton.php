<?php
class Singleton{
	public static $instance = null;
	public $conn;
	private function __construct(){
		$this->conn = new PDO("mysql:host=localhost;dbname=some_db","root","");
	}
	public static function getInstance(){
		if(!self::$instance){
			self::$instance = new Singleton;
		}
		return self::$instance;
	}
}