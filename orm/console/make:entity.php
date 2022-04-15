<?php
include_once "./console.class/sql.php";
include_once "./console.class/generate.php";
$pathEntity="../../Entity";
$pathSql="../sql";
$champ=array();
 $next; 
echo "entity name : \n "; 
fscanf(STDIN,"%s",$entityName);
$sql= new Sql($entityName);

// chack entity exist
 
 // get entity data from user 
 $i=0;
do{
    //scan champ ; 
    echo "attribute name : \n ";
    fscanf(STDIN,'%s',$champ[$i]);
    // scan attribute type
    do{
        echo Sql::getTypesInString();
        echo "\n type:";
        fscanf(STDIN,'%s',$type);
        echo "\n size";
        fscanf(STDIN,'%d',$size);
    }while(! $sql->addAttribute($champ[$i],$type,$size));

   


    $i++;
    //scan next ; 
    echo "press (y) to add an attribute any key to exit : ";
    fscanf(STDIN,'%c',$next0);
    if($next0=="y"){
        $next=true;
    }
    else{
        $next=false;
    }
 } while ($next==true);

 








$fileContent =Generate::generateClass($entityName,$champ);
$sql->generateQuery();
 





 
  // find dir 
  if(!file_exists($pathEntity)){
    mkdir($pathEntity,0777);
  }
  $fl=$handle = fopen("$pathEntity/$entityName.php", "w");
  fwrite($fl,$fileContent);
  fclose($fl);
  if(! file_exists($pathSql)){
    mkdir($pathSql,0777);
  }
  $sql->createSqlFileAndAddContent($pathSql."/$entityName");

