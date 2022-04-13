<?php 
class Tclass{
    public int $id;
    public String $email;  
    public String $password;
   public function __construct(int $id,String $email,String $password){
        $this->id=$id;
        $this->email=$email;
        $this->password=$password;
    }
}