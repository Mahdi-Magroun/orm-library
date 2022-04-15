<?php 
include_once "generate.php";
include_once "/var/www/html/php/dataBase_lib/orm/conf/DataBaseConnection.php";
class Sql {

    private static $type=array('int','varchar','char');
    private $attribute; 
    private $Content;
    private $entityName;
    
    public function __construct($entityName)
    {
        $this->entityName=$entityName;
    }


    public function generateQuery(){
        if(count($this->attribute)==0) throw new Exception("add attribute name and type first");
         $this->content=Generate::generateSql($this->entityName,$this->attribute);
         return $this->content;
    }

    public function addAttribute($attributeName,$type,$size){
        if($this->verifyType($type)&&$size<=255){
            $this->attribute[$attributeName]=$type."($size)";
            return true ; 
        }
        else return false ;
       
    }

    public function verifyType($type){
        if(in_array($type,Sql::$type)){
            return true ;
        }
        else return false ; 
    }

    public function createSqlFileAndAddContent($path){
        $fl=$handle = fopen("$path.sql", "w");
        fwrite($fl,$this->content);
        fclose($fl); 
    }

    public static function runCreateQuery($path){
        $connection=DataBaseConnection::getConnection();
        // read file content 
        // put file content into query 
    }

    public static function getTypesInString(){
        $content="";
        for($i=0;$i<count(Sql::$type);$i++){
            $content=$content." ".Sql::$type[$i];
        }
        return $content;
    }

    public function getAttribute(){
        return $this->attribute;
    }



}