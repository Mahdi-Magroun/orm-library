<?php 
class Generate {


public static function generateClass(String $entityName,array $champ):String {
    $constractor="public function __construct(";
    for($i=0;$i<count($champ);$i++){
      if(count($champ)-1==$i){
        $constractor=$constractor."$".$champ[$i]."){\n";
            break;
     }
    $constractor=$constractor."$".$champ[$i].",";
     }
    // generate content 
    for($i=0;$i<count($champ);$i++){
      if(count($champ)-1==$i){
           $constractor=$constractor.'$this->'.$champ[$i].'=$'.$champ[$i].";\n}";
        break;
     }
     $constractor=$constractor.'$this->'.$champ[$i].'=$'.$champ[$i].";\n";
    }



    $fileContent="<?php \n\n\n class ".$entityName." { \n\n\n";
    $i=0;
    do{
         $fileContent=$fileContent."public $".$champ[$i].";\n";
         $i++;
    }while(count($champ)!=$i);
    $fileContent=$fileContent.$constractor."\n}\n";
   return $fileContent;

}

public static  function generateSql($entityName,$keyValueChamp){
    $query="create table $entityName (\n";
    $i=0;
    foreach($keyValueChamp as $attribute=>$type){
        if(count($keyValueChamp)-1==$i){
            $query=$query."$attribute  $type  \n );";
            break ;
        }
        $query=$query."$attribute  $type , \n";
        $i++;
    }

    return $query ; 

}



}