<?php

use DataBaseConnection as GlobalDataBaseConnection;

class DataBaseConnection{
// connection property 
private $host="127.0.0.1";
private $usr="root";
private $pwd="mmagrounmahdi@gmail.com";
private static $db='green-tow'; 
private static $connection; 

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


public static function getConnection(){
    if(DataBaseConnection::$connection==null){
         new DataBaseConnection();
    }
    new DataBaseConnection();
    return DataBaseConnection::$connection;

}

public static function getDB(){
    return DataBaseConnection::$db;
}

}