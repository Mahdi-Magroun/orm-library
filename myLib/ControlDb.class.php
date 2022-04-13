<?php
declare(strict_types=1);
include_once "/var/www/html/php/dataBase_lib/myLib/conf/DataBaseConnection.php";


class ControlDb{

   private static $database;

    // ajouter une table 
    
    private static function verifyConnection(){
        ControlDb::$database =DataBaseConnection::getConnection();
    }

    // good for now 
    public static function selectAll($tableName):array{
        ControlDb::verifyConnection();
       $query= ControlDb::$database->query("SELECT * FROM $tableName");
        $data=$query->fetchAll(PDO::FETCH_ASSOC);
       // print_r ($data[0]);
        return $data;
    }


    // select all elements of the table 
    public static function select($tableName,...$parmeter):array{
        ControlDb::verifyConnection();
        $fetch=array();
        // prepare the query 
        $str="*";
        if(count($parmeter)>0){
            $str="";
        for($i=0;$i<count($parmeter);$i++){
            if($i==count($parmeter)-1){
                $str=$str.$parmeter[$i];
                break;

            }
            $str=$str.$parmeter[$i].",";

        }
    }
 
    // try to execute the query 
    try{
        $result = ControlDb::$database->query("SELECT $str FROM $tableName");
    }
    catch(PDOException $e)
    {
        echo "error in parameter : (select) ".$e->getMessage();
        return false;
    }

    return $result->fetchAll(PDO::FETCH_ASSOC);
}


     



    public static function  insert(array $champs,String $tableName):bool{
        ControlDb::verifyConnection();
        $str_key="`";
        $str_value="";

        // preparing the query 
        $i=0;
        $tab=array();
        // prepare the query 
        foreach($champs as $key=>$value)
            
        {
            
            if($i==count($champs)-1){
                $str_key=$str_key.$key."`";
                $str_value=$str_value."?";
                $tab[$i]=$value;
              break;

            }
            $str_key=$str_key.$key."`,`";
            $str_value=$str_value."?,"; 
            $tab[$i]=$value;
            $i++;
        }
        // try to execute the query 
        try{
        $result = ControlDb::$database->prepare("INSERT INTO $tableName ($str_key )VALUES ($str_value)");
        return $result->execute($tab);
        }
        catch(PDOException $e){
            echo "err in insert query (insert)".$e->getMessage();
        }
    }


    // delete query 
    /**
     * $filter : el champ eli bech na3mel bih rsearch al hajet eli bech nfasakhha 
     * $log->delete(['name'=>$user1['name'],'pseudo'=>$user1['pseudo'],'password'=>$user1['password']])
     */
    public static function  delete(array $filter,string $tableName):bool{
        ControlDb::verifyConnection();
        $filter_key=" WHERE ";
        $i=0;
        // prepare the query 
        foreach($filter as $key=>$value){
            if($i==count($filter)-1){
            $filter_key=$filter_key.$key."  ?";
            $tab2[$i]=$value;
            
            break;
        }
        $filter_key=$filter_key.$key."? ";
        $tab2[$i]=$value;

            $i++;
        }
        // try to execute the query 
        try{
            $result = ControlDb::$database->prepare("DELETE FROM $tableName $filter_key");
            return $result->execute($tab2);
            }
            catch(PDOException $e){
                echo "err in delete Query : ".$e->getMessage();
            }

    }
    
    // update query  
/*
$chapms : champs jdid mta3i
$filter : champs le9dim mta3i 
 *$log->update(['name'=>'zaza','pseudo'=>'bbcha','password'=>'shit01'],['name'=>$user1['name'],'pseudo'=>$user1['pseudo'],'password'=>$user1['password']])
 * 
*/ 
    public static function update( array $champs,array $filter,String $table):bool{
        ControlDb::verifyConnection();
        $str_key="";
        $str_value="";
        $filter_key=" Where ";
        $i=0;
        // prepare query key value
        foreach($champs as $key=>$value){
            if($i==count($champs)-1){
            $str_key=$str_key.$key."=?";
            $tab[$i]=$value;
            break;
        }
        $str_key=$str_key.$key."=?,";
        $tab[$i]=$value;
            $i++;
        }
        // prepare query filter;
        $i=0;
        foreach($filter as $key=>$value){
            if($i==count($filter)-1){
            $filter_key=$filter_key.$key."?";
            $tab2[$i]=$value;
            
            break;
        }
        $filter_key=$filter_key.$key."? ";
        $tab2[$i]=$value;

            $i++;
        }
        // join table for query preparing variable
        $tab=array_merge($tab,$tab2);
        // try to execute the query 
        try{
        $result = ControlDb::$database->prepare("UPDATE $table SET $str_key $filter_key");
        return $result->execute($tab);
        }
        catch(PDOException $e){
            echo "err in update query : " .$e->getMessage();
        }
    }


// get table attribute
public static function getCol(array $tableName):array {
    ControlDb::verifyConnection();
    try {
    // get column names
    $query =ControlDb::$database->prepare("DESCRIBE $tableName");
    $query->execute();
    $table_names = $query->fetchAll(PDO::FETCH_COLUMN);
    return $table_names;

    } catch(PDOException $e) {
      echo $e->getMessage();
    }
 

}

    

    
// incode to json 
public static function json(array $array):string{
    return json_encode($array);
}


public static function search(array $champs,array $filter,String $table,int $mode=PDO::FETCH_ASSOC){
    ControlDb::verifyConnection();
    $champKey="SELECT ";
    $filter_key="";
    for($i=0;$i<count($champs);$i++){
        if($i==count($champs)-1){
            $champKey.=$champs[$i]." FROM ". $table. " WHERE " ;
        }
        else{
            $champKey.=$champs[$i].' , ' ;   
        }
       
    }
    $i=0;
    foreach($filter as $key=>$value){
        if($i==count($filter)-1){
        $filter_key=$filter_key.$key."?";
        $tab2[$i]=$value;
        
        break;
    }
    $filter_key=$filter_key.$key."? ";
    $tab2[$i]=$value;

        $i++;
    }

    $query=$champKey.$filter_key;
    try{
        $result =ControlDb::$database->prepare($query);
        $result->execute($tab2);
        }
        catch(PDOException $e){
            echo "erreur dans l'accé au base de donné (update)" .$e->getMessage();
             
        }


        if($mode==PDO::FETCH_ASSOC)
        return $result->fetchAll($mode);
        else if($mode==PDO::FETCH_CLASS){
            return $result;
        }
 }
    }


?>