<?php 
include_once "./ORM.class.php";
include_once "./Testclass.php";
/*test select 


try{
ORM::select("Tclass","user");
} 
catch(Exception $e){
    echo $e->getMessage();
}*/


ORM::insert(new Tclass(1,"jacojaco@gmail.com","mahdimahdi01"),"user");