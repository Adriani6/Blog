<?php
class MySQLClass{


	protected $server = "eu-cdbr-azure-north-d.cloudapp.net";
	protected $user = "b3216f07d20ee7";
	protected $pass = "d597404f";
	protected $db = "blog-db";
	protected $port = "3306";

/*
	protected $server = "127.0.0.1";
	protected $user = "root";
	protected $pass = "";
	protected $db = "blog";
	protected $port = "3306";
*/

	private $mysqli = null;

	public function getMysqli() {
		return $this->mysqli;
	}

	function __construct() {
		$this->mysqli = new mysqli($this->server, $this->user, $this->pass, $this->db, $this->port);
	}

	public function query($query){
		//$this->getMysqli()->refresh();
		$result = $this->getMysqli()->query($query);
		//var_dump($result);
		if(!$result){
			die('Could not query:' . $this->mysqli->error . " Query: ". $query . " " . mysqli_error($result));
		}

		return $result;

	}

    public function selectUser($username) {
        $username = $this->getMysqli()->escape_string($username);
        $result = $this->getMysqli()->query("SELECT * FROM users WHERE username='".$username."'");

        if(!$result){
            die('Could not query:' . $this->getMysqli()->error);
        }

        if($result->num_rows == 0)
            return null;
        else
            return $result->fetch_assoc();
    }

	public function prepare($query) {
		return $this->getMysqli()->prepare($query);
	}
}
?>