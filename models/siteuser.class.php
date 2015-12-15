<?php
require_once ("/../utils/mysql.php");
require_once 'country.class.php';


class SiteUser
{
    public static $salt = "Ct4adbUeU8";
    private $mySQL;
    private $loggedIn;

    private $username;
    private $user_id;
    private $type;
    private $country;
    private $verified;

    public function isLoggedIn(){
        return $_SESSION['siteuser_logged_in'];
    }

    public function getUsername(){
        if(!$this->isLoggedIn())
            return null;

        return $this->username;
    }

    public function getType() {
        if(!$this->isLoggedIn())
            return null;

        return $this->type;
    }

    public function isVerified(){
        if(!$this->isLoggedIn())
            return null;

        if($this->verified == 1)
            return true;

        return false;
    }

    public function getUserId(){
        if(!$this->isLoggedIn())
            return null;

        return $this->user_id;
    }

    public function getCountry(){
        if(!$this->isLoggedIn())
            return null;

        return $this->country;
    }

    function __construct($mySQL)
    {
        $this->mySQL = $mySQL;
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if(isset($_SESSION['siteuser_logged_in'])) {
            if ($_SESSION['siteuser_logged_in'] == true) {
                $this->username = $_SESSION['siteuser_username'];
                $this->loadUserData();
            }
            else
                $_SESSION['siteuser_logged_in'] = false;
        }
        else {
            $_SESSION['siteuser_logged_in'] = false;
        }

        /*
        if(isset($_COOKIE['username']) && isset($_COOKIE['login_token']))
        {
            $result = $this->mySQL->query("SELECT * FROM users WHERE username = '{$_COOKIE['username']}' AND login_token='{$_COOKIE['login_token']}'");
            if($result != false)
            {
                $this->username = $_COOKIE['username'];
                $this->loadUserData();
                $this->loggedIn = true;
            }
        }
        else
        {
            $this->loggedIn = false;
        }
        */
    }

    public function logIn($username, $password)
    {
        $this->logOut();

        $result = $this->mySQL->query("SELECT * FROM users WHERE username = '". $username ."'");

        $row = $result->fetch_assoc();

        if(password_verify($password . SiteUser::$salt, $row['password']))
        {
            $_SESSION['siteuser_logged_in'] = true;
            $_SESSION['siteuser_username'] = $username;

            $this->username = $username;
            $this->loadUserData();

                                           // login for 2 weeks
            //setcookie("username",$username,time() + 3600 * 24 * 14,'/');
            //$login_token = md5(uniqid(rand(),TRUE));
            //setcookie("login_token",$login_token,time() + 3600 * 24 * 14,'/');
            //$this->mySQL->query("UPDATE users SET login_token='{$login_token}' WHERE username='{$username}'");

            return true;
        }
        else
        {
            return false;
        }
    }

    public function logOut()
    {
        //setcookie("username",'',time(),'/');
        //setcookie("login_token",'',time(),'/');
        $_SESSION['siteuser_logged_in'] = false;
        $_SESSION['siteuser_username'] = null;

        $this->username = null;
        $this->user_id = null;
        $this->type = null;
        $this->country = null;
        $this->verified = null;
    }

    private function loadUserData(){

        $data = $this->mySQL->query("SELECT * FROM users WHERE username = '". $this->username ."'");
        if($data->num_rows == 0) {
            return null;
        }
        $row = $data->fetch_assoc();
        $this->country = new Country((int)$row['country_id']);
        $this->verified = $row['verified'];
        $this->type = $row['type'];
        $this->user_id = $row['user_id'];
        //$this->loadAdventures();
    }

    // returns true if registration successful
    // otherwise returns array with all errors
    public static function register($username, $password, $cppassword, $name, $country, $mySQL)
    {
        $username = htmlentities($username);
        $name = htmlentities($name);
        $country = htmlentities($country);

        $registrationResult = array();

        if (empty($username)) {
            array_push($registrationResult,"Username field is required.");
        } else if (strlen($username) < 3) {
            array_push($registrationResult,"Username must be at least 6 characters long.");
        } else if (strlen($username) > 50) {
            array_push($registrationResult,"Username can be maximum 50 characters long.");
        } else if ($mySQL->selectUser($username)) {
            array_push($registrationResult,"Username is already taken");
        } else if (!preg_match("/^[a-zA-Z1-9]*$/", $username)) {
            array_push($registrationResult,"Username can only contain letters and digits.");
        }


        if (empty($password)) {
            array_push($registrationResult,"Password field is required.");
        } else if (strlen($password) < 6) {
            array_push($registrationResult,"Password must be at least 6 characters long.");
        } else if (strlen($password) > 50) {
            array_push($registrationResult,"Password cannot be longer than 50 characters.");
        }

        if ($password != $cppassword) {
            array_push($registrationResult,"Passwords do not match.");
        }

        if (strlen($name) > 50) {
            array_push($registrationResult,"Name can be maximum 50 characters long.");
        } else if (!preg_match("/^[a-zA-Z1 ]*$/", $name)) {
            array_push($registrationResult, "Name can only contain letters.");
        }

        if (!empty($registrationResult)) {
            return $registrationResult;
        }

        $hash = password_hash($password . SiteUser::$salt, PASSWORD_DEFAULT);

        if (($id = getCountryID($country, $mySQL)) == null) {
            array_push($registrationResult,"An error occurred. Please try again later.");
            return $registrationResult;
        }

        $type = "Reader";
        $stmt = $mySQL->prepare("INSERT INTO users (username, password, name, country_id, type) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssis",$username,$hash,$name,$id,$type);
        $stmt->execute();

        return true;
    }
}