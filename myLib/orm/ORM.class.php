<?php 
include_once "../ControlDb.class.php";
include_once "./test.php";
class ORM {

/** steps 
 * learn more about pdo 
 * make an orm that can (select,update,delete,insert)
 * clsectif : manupuler les clsect 
 */
public function __construct()
{
    
}
 
 public static  function select ($clsName,$tableName){

   $classP=get_class_vars($clsName);
   if(!class_exists($clsName))throw new Exception("class not found in select ORM");
   $verif=ORM::verifyColumns($clsName,$tableName);
   if($verif){
    $cols=ORM::getTableColums($tableName);
    $conn=DataBaseConnection::getConnection();
    $query=$conn->query("SELECT * FROM $tableName");
    $query->setFetchMode(PDO::FETCH_CLASS,$clsName);
    $res=$query->fetchAll();
    return $res; 

   }
   else{
     throw new Exception("class property and $tableName attribute didn't match ");

   }


 }
 public static function search($obj,$filter,$tableName){
  $class = get_class($obj);
  if(!class_exists($class))throw new Exception("class not found in update ORM");
  $classVar=get_class_vars($class);
  $verif=ORM::verifyColumns($class,$tableName);
  if($verif){
   $conn=DataBaseConnection::getConnection();
    $array =(array)$obj;
    $result=ControlDb:: search(array_keys($array),$filter,$tableName,PDO::FETCH_CLASS);
     $result->setFetchMode(PDO::FETCH_CLASS,$class);
     return $result->fetchAll();
  
  }
 }

 
 public static function insert($obj,$tableName){
   $class = get_class($obj);
   if(!class_exists($class))throw new Exception("class not found in insert ORM");
   $classVar=get_class_vars($class);
   $verif=ORM::verifyColumns($class,$tableName);
   if($verif){
    $conn=DataBaseConnection::getConnection();
     $array =(array)$obj;
     unset($array['id']); 
     ControlDb::insert($array,$tableName);
   
   }

 }


 public static function update($obj,$filter,$tableName){
  $class = get_class($obj);
  if(!class_exists($class))throw new Exception("class not found in update ORM");
  $classVar=get_class_vars($class);
  $verif=ORM::verifyColumns($class,$tableName);
  if($verif){
   $conn=DataBaseConnection::getConnection();
    $array =(array)$obj;
    ControlDb:: update($array,$filter,$tableName);
  
  }
 }
 public static function delete($obj,$filter,$tableName){
  $class = get_class($obj);
  if(!class_exists($class))throw new Exception("class not found in delete ORM");
  $classVar=get_class_vars($class);
  $verif=ORM::verifyColumns($class,$tableName);
  if($verif){
   $conn=DataBaseConnection::getConnection();
    $array =(array)$obj;
    ControlDb:: delete($filter,$tableName);

 }
 }


 private static function verifyColumns($cls,$tableName){
   $tableColums=ORM::getTableColums($tableName);
   $clsData=get_class_vars($cls);
   $clsKey=array_keys($clsData);
   $arr=array_intersect($tableColums,$clsKey);
   $nb_intersection=count($arr);
  
  if(count($tableColums)==$nb_intersection ){
  return true;
  }
  else {
  return false;
  }

   
   
 }


 private static function getTableColums($tableName){
   $conn = DataBaseConnection::getConnection();
    $dbName= DataBaseConnection::getDB();
    echo $dbName;
    try{//SELECT column_name from information_schema.columns where table_schema= $dbName and  table_name=$tableName"
    $qr=$conn->prepare("select column_name from information_schema.columns where table_schema=? and  table_name=?");
    $qr->execute([$dbName,$tableName]);
    }
    catch(PDOException $e){
      echo  $e->getMessage();
    }
    return $result=$qr->fetchAll(PDO::FETCH_COLUMN);
 }

}