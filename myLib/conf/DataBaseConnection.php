<?php
class DataBaseConnection{
// connection property 
private String $host="127.0.0.1";
private String $usr="root";
private String  $pwd="mmagrounmahdi@gmail.com";
private static String  $db='green-tow'; 
private static PDO $connection; 

private function  __construct(){

    $dsn ='mysql:host='.$this->host.';dbname='. DataBaseConnection::$db.';charset=utf8';
    try{
    DataBaseConnection::$connection= new PDO($dsn, $this->usr, $this->pwd, 
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch(PDOException $e){
        echo 'connection error';
    }

}


public static function getConnection():PDO{
    if(DataBaseConnection::$connection==null){
         new DataBaseConnection();
    }
    new DataBaseConnection();
    return DataBaseConnection::$connection;

}

public static function getDB():String{
    return DataBaseConnection::$db;
}

}