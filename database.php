<?php
class Database{
    protected $conn;
    public function __construct(){
        $this->conn = new mysqli("localhost","root","","handicraft_mp");
        if($this->conn->error){
            die("Couldn't connect to the database".$this->conn->error);
        }
    }
}
