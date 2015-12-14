<?php
class MySQLClass{


	protected $server = "eu-cdbr-azure-north-d.cloudapp.net";
	protected $user = "b3216f07d20ee7";
	protected $pass = "d597404f";
	protected $db = "blog-db";
	protected $port = "3306";

/*
	protected $server = "localhost";
	protected $user = "root";
	protected $pass = "";
	protected $db = "blog-dev";
	protected $port = "3306";
*/

	private $mysqli = null;

	public function getMysqli() {
		return $this->mysqli;
	}

	function __construct() {
		$this->mysqli = new mysqli($this->server, $this->user, $this->pass, $this->db, $this->port);

		if($this->getMysqli()->connect_errno) {
            echo "Database connection failed.";
        }
	}

	public function query($query){
		$result = $this->getMysqli()->query($query);
		if(!$result){
			die('Could not query:' . $this->getMysqli()->error);
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

}
?>