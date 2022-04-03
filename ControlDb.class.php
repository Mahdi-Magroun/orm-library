<?php
declare(strict_types=1);

class ControlDb{

public function __construct($config){

    $this->Db=$config['database'];
    $this->host=$config['host']; 
    $this->databaseUser=$config['user'];
    $this->Password=$config['password'];
    $this->connect();
}
    private $Db;
    private $database;
    private $databaseUser;
    private $Password ;



    public function connect(){
        $test=true;
        $dsn ='mysql:host='.$this->host.';dbname='.$this->Db.';charset=utf8';
        try{
        $this->database= new PDO($dsn, $this->databaseUser, $this->Password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch(PDOException $e){
            echo 'Error connecting to the data base ====> check connect()'.$e->getMessage();
        }
    }




    // ajouter une table 

    public function setTableName(string $tb){
        
        // verifie the exixtance of the table 
        try {
            $result = $this->database->query("SELECT 1 FROM $tb LIMIT 1");
        } catch (Exception $e) {
            // We got an exception == table not found
            echo $e->getMessage();
            return FALSE;
        }
        // affect table name to the property 
         $this->table_name=$tb;
    }




    // get table name 
    public function getTableName(){
        return $this->table_name;
    }


    
    // select all elements of the table 
    public function select(...$parmeter){
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
        $result = $this->database->query("SELECT $str FROM $this->table_name");
    }
    catch(PDOException $e)
    {
        echo "error in parameter : (select) ".$e->getMessage();
        return false;
    }
       // fetch  data base object
       $j=0; 
       if($str!="*"){
     while($data=$result->fetch()){
           
               // insert data in fech table to retur it 
               for($i=0;$i<count($parmeter);$i++)
               {
               $fetch_ex[$parmeter[$i]]=$data[$parmeter[$i]];
           }
           $fetch[$j]=$fetch_ex;
           $j++;
             
       }
    }
    else{
        $tab=array();
        $tab=$this->getCol();
        while($data=$result->fetch()){
            for($i=0;$i<count($tab);$i++){
                $fetch_ex[$tab[$i]]=$data[$tab[$i]];
            }
            $fetch[$j]=$fetch_ex;
           $j++;

        }
    }

           return $fetch; 
    }


     



    public function insert($champs,$table){
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
        $result = $this->database->prepare("INSERT INTO $table ($str_key )VALUES ($str_value)");
        $result->execute($tab);
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
    public function delete($filter,$table){
        $filter_key=" WHERE ";
        $i=0;
        // prepare the query 
        foreach($filter as $key=>$value){
            if($i==count($filter)-1){
            $filter_key=$filter_key.$key."=?";
            $tab2[$i]=$value;
            
            break;
        }
        $filter_key=$filter_key.$key."=? AND ";
        $tab2[$i]=$value;

            $i++;
        }
        // try to execute the query 
        try{
            $result = $this->database->prepare("DELETE FROM $table $filter_key");
            $result->execute($tab2);
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
    public function update($champs,$filter,$table){
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
            $filter_key=$filter_key.$key."=?";
            $tab2[$i]=$value;
            
            break;
        }
        $filter_key=$filter_key.$key."=? AND ";
        $tab2[$i]=$value;

            $i++;
        }
        // join table for query preparing variable
        $tab=array_merge($tab,$tab2);
        // try to execute the query 
        try{
        $result = $this->database->prepare("UPDATE $table SET $str_key $filter_key");
        $result->execute($tab);
        }
        catch(PDOException $e){
            echo "erreur dans l'accé au base de donné (update)" .$e->getMessage();
        }
    }


// get table attribute
public function getCol() {
    try {
    // get column names
    $query = $this->database->prepare("DESCRIBE $this->table_name");
    $query->execute();
    $table_names = $query->fetchAll(PDO::FETCH_COLUMN);
    print_r($table_names);
    return $table_names;

    } catch(PDOException $e) {
      echo $e->getMessage();
    }
 

}

    // print table :: debuging method
    public function affiche_fetched_array($array){
       foreach ($array as $val1=>$val2){
           foreach($val2 as $key=>$value)
           {
               echo $key ."===>  ".$value."<br>";
           }
           echo "<br> <br>";
       }
    }

    public function convertNormalTable($array){
        $dataTab=array();
        $fetched=array();
        $i=0;
        foreach ($array as $val1=>$val2){
            foreach($val2 as $key=>$value)
            {
               $dataTab[$key] =$value;
               
            }
            $i++;
            $fetched[$i]=$dataTab;
    }
    return $fetched;
}


// incode to json 
public function json(array $array){
    return json_encode($array);
}


public function search($champs,$filter){
    $champKey="SELECT ";
    $filter_key="";
    for($i=0;$i<count($champs);$i++){
        if($i==count($champs)-1){
            $champKey.=$champs[$i]." FROM ". $this->table_name. " WHERE " ;
        }
        else{
            $champKey.=$champs[$i].' , ' ;   
        }
       
    }
    $i=0;
    foreach($filter as $key=>$value){
        if($i==count($filter)-1){
        $filter_key=$filter_key.$key."=?";
        $tab2[$i]=$value;
        
        break;
    }
    $filter_key=$filter_key.$key."=? AND ";
    $tab2[$i]=$value;

        $i++;
    }

    $query=$champKey.$filter_key;
    try{
        $result = $this->database->prepare($query);
        $result->execute($tab2);
        }
        catch(PDOException $e){
            echo "erreur dans l'accé au base de donné (update)" .$e->getMessage();
             
        }


        // fetching the data
        $j=0;
        $fetch=array();
        while($data=$result->fetch()){
           
            // insert data in fech table to retur it 
            for($i=0;$i<count($champs);$i++)
            {
            $fetch_ex[$champs[$i]]=$data[$champs[$i]];
        }
        $fetch[$j]=$fetch_ex;
        $j++;
          
    }
        return $fetch;
 }
    }


?>