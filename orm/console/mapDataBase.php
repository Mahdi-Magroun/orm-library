<?php
include_once "console.class/sql.php";
$pathEntity="../../Entity";
if(!file_exists($pathEntity)){
    mkdir($pathEntity,0777);
  }
// recuperation des nom des table et les noms des champ 
$tables=Sql::showTables();
$data=array();
for($i=0;$i<count($tables);$i++){
$data=Sql::extractTableFiled($tables[$i]);
$fileContent =Generate::generateClass($tables[$i],$data);
$fl=$handle = fopen("$pathEntity/".$tables[$i].".php", "w");
  fwrite($fl,$fileContent);
  fclose($fl);
}






