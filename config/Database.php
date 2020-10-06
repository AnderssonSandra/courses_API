<?php
//class Database
class Database {
    //database settings 
    private $host = 'localhost';
    private $db_name = 'moment5';
    private $username = 'moment5';
    private $password = 'moment5password';
    private $conn;

    //connect to database
    public function connect() {
        //close connections
        $this->conn = null;
        //connect with PDO
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //Connection error
        } catch(PDOExcetion $e) {
            echo "Connection Error" . $e->getMessage();
        }
        //return
        return $this->conn;
    }
    //close connection to database
    public function close() {
        $this->conn = null;
    }
}
?>