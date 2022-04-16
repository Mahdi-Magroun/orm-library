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

    public static function executeQuery(String $content) {
        $connection=DataBaseConnection::getConnection();
        //echo $content." from  sql class.$content";
        try{
            $connection->query($content);
        }
        catch(PDOException $e){
            echo "err in execution ".$e->getMessage();
        }
    }

    public static function showTables():array{
        $connection=DataBaseConnection::getConnection();
       $data=$connection->query(
            'SHOW TABLES'
        );
        return $data->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function extractTableFiled($table){
        $connection=DataBaseConnection::getConnection();
        $DB=DataBaseConnection::getDB();
        try{
        $data=$connection->prepare("SELECT COLUMN_NAME 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE 
            TABLE_SCHEMA =?
        AND TABLE_NAME =?" );
        $data->execute([DataBaseConnection::getDB(),$table]);
         return $data->fetchAll(PDO::FETCH_COLUMN);
        }
        catch(PDOException $e){
            $e->getMessage();
        }
       
    }


}