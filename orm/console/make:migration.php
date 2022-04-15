<?php 
include_once "../console/console.class/dir.php";
include_once "../console/console.class/file.class.php";
include_once "console.class/sql.php";


$pathSql="/var/www/html/php/dataBase_lib/orm/sql";
if(is_dir($pathSql)){
    $files=Dir::fileInDir($pathSql); 
    $content="";
    var_dump($files);
    for($i=0;$i<count($files);$i++){
       $content=$content.File::FileContentIntoString($pathSql."/$files[$i]")."\n\n";
    }
    echo "bbbb".$content;
Sql::executeQuery($content);





}
else{
    echo "make your entites first ";
}