<?php 

class Dir{

public static function fileInDir(String $pathDir): array{
    $files=scandir($pathDir);
    if($files==false) throw new Exception("check your directory path in dir class ");
    $e=0;
    for($i=0;$i<count($files);$i++){
        if($files[$i]=="."||$files[$i]==".."){
            $e++;
            unset($files[$i]);
            if($e==2)break;
        }
    }
    return array_values($files);
}

}