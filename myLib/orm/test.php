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

/*test insert
ORM::insert(new Tclass(1,"jacojaco@gmail.com","mahdimahdi01"),"user");
*/

/* test update
ORM::update(new Tclass(1,"update@gmail.com","updato"),["id >"=>20],"user");
*/

/* test delete
ORM::delete(new Tclass(1,"update@gmail.com","mahdimahdi01"),["id"=>1],"user");
 */
/*
$result = ORM::search(new Tclass(1,"update@gmail.com","mahdimahdi01"),["id<="=>3],"user");
var_dump($result);
*/