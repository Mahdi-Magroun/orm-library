<?php 

class DataBaseConnection{
// connection property 
private $host="127.0.0.1";
private $usr="root";
private $pwd="mmagrounmahdi@gmail.com";
private $db="hr"; 
private static $connection; 

private function  __construct(){

    $dsn ='mysql:host='.$this->host.';dbname='.$this->db.';charset=utf8';
    try{
    DataBaseConnection::$connection= new PDO($dsn, $this->usr, $this->pwd, 
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch(PDOException $e){
        echo 'connection error'.$e->getMessage();
    }

}


public static function getConnection(){
    if(DataBaseConnection::$connection==null){
         new DataBaseConnection();
    }
    new DataBaseConnection();
    return DataBaseConnection::$connection;

}

}