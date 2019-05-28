<?php

namespace Model;

class LoginModel{
    
    private $id;
    private $first_name;
    private $last_name;
    private $email;
    private $password;
	
	private $table = "users";
    
    function __construct($campos)
    {
		foreach($campos as $campo => $valor){
			if(property_exists($this, $campo)){
				$this->$campo = $valor;
			}
		}
    }
    
    //getters
	public function getTable(){
		return $this->table;
	}		
	
    public function getId(){
        return $this->id;
    }
    
    public function getFirst_name(){
        return $this->first_name;
    }
    
    public function getLast_name(){
        return $this->last_name;
    }
    
    public function getEmail(){
        return $this->email;
    }
    
    public function getPassword(){
        return $this->password;
    }
    
    //Setters
    public function setId($id){
        $this->id = $id;
    }
    
    public function setFirst_name($first_name){
        $this->first_name = $first_name;
    }
    
    public function setLast_name($last_name){
        $this->last_name = $last_name;
    }
    
    public function setEmail($email){
        $this->email = $email;
    }
    
    public function setPassword($password){
        $this->password = $password;
    }
    
	public function getValues(){
		return array(
				"id" => $this->id,
				"first_name" => $this->first_name,
				"last_name" => $this->last_name,
				"email" => $this->email,
				"password" => password_hash($this->password, PASSWORD_BCRYPT)
				);
	}
	
}


?>