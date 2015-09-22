<?php
     
    class DB_Functions {
     
        private $db;
     
        //put your code here
        // constructor
        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->db = new DB_Connect();
            $this->db->connect();
        }
     
        // destructor
        function __destruct() {
             
        }
     
        /**
         * Storing new user
         * returns user details
         */
        public function storeUser($name, $nric, $phone, $email, $username, $password) {
            // $uuid = uniqid('', true);
            // $hash = $this->hashSSHA($password);
            // $encrypted_password = $hash["encrypted"]; // encrypted password
            // $salt = $hash["salt"]; // salt
            mysql_query("INSERT INTO accounts(name, nric, phone, email, username, password) VALUES('$name', '$nric', '$phone', 'email', 'username', 'password')");
            // check for successful store
            if ($result) {
                // get user details 
                $uid = mysql_insert_id(); // last inserted id
                $result = mysql_query("SELECT * FROM accounts WHERE account_id = $uid");
                // return user details
                return mysql_fetch_array($result);
            } else {
                return false;
            }
        }
     
        /**
         * Get user by email and password
         */
        public function getAccountByUsernameAndPassword($username, $password) {
            $result = mysql_query("SELECT * FROM users WHERE username = '$username' AND password = '$password'") or die(mysql_error());
            // check for result 
            $no_of_rows = mysql_num_rows($result);
            if ($no_of_rows > 0) {
                return $result;
            } else {
                // user not found
                return false;
            }
        }
     
        /**
         * Check user is existed or not
         */
        public function isAccountExisted($username) {
            $result = mysql_query("SELECT email from users WHERE username = '$username'");
            $no_of_rows = mysql_num_rows($result);
            if ($no_of_rows > 0) {
                // user existed 
                return true;
            } else {
                // user not existed
                return false;
            }
        }
     
    }
 
?>