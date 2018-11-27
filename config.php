<?php
#------------------------------------------------------------
class Posting{
    private $mysqli_host = "localhost";
    private $mysqli_user = "Jared";
    private $mysqli_pass = "291996";
    private $mysqli_database = "hidden_post";

    public function __construct(){
        $this->connect = new mysqli($this->mysqli_host, $this->mysqli_user, $this->mysqli_pass, $this->mysqli_database);
        if($this->connect->connect_error){
        	die($this->connect->connect_error."(<pre>Can't connect to DataBase</pre>)");
        }
        $this->connect->set_charset('utf8');
    }
    public function inputData(){
    	if($_POST["add"]){
    		$nick = $_POST["nick"];
    		$msg = $_POST["msg"];
    		if($nick && $msg !== ""){
                $result = $this->connect->query("SELECT * FROM messages");
                    var_dump($result);
                    while($row = $result->fetch_assoc()){
                        print_r($row);

                    }
    		}else{
    			die("Cant write this data to database.");
    		}
    	}
    }
}

$objSql = new Posting();
$objSql->inputData();











































?>