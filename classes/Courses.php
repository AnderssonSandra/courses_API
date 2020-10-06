<?php
class Courses {
    //database connection
    private $conn;

    //props
    public $id;
    public $code;
    public $name;
    public $progression;
    public $syllabus;

    //constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    //get all courses from database
    function getAll() {
        $query = "SELECT * FROM courses";

        //prepare and execute statement
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    //get one specific course from database
    function getOne($id) {
        $query = "SELECT * FROM courses WHERE id = $id";
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    //create course
    function create() {
        $query = "INSERT INTO courses 
        SET 
        code=:code,
        name=:name,
        progression=:progression,
        syllabus=:syllabus
        ";

        //prepare query
        $statement= $this->conn->prepare($query);

        //sanitaze from tags 
        $this->code=htmlspecialchars(strip_tags($this->code));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->progression=htmlspecialchars(strip_tags($this->progression));
        $this->syllabus=htmlspecialchars(strip_tags($this->syllabus));

        //bind values
        $statement->bindParam(":code", $this->code);
        $statement->bindParam(":name", $this->name);
        $statement->bindParam(":progression", $this->progression);
        $statement->bindParam(":syllabus", $this->syllabus);

        //execute query
        if($statement->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //update course
    function update($id) {
        $query= "UPDATE courses
        SET 
        code=:code,
        name=:name,
        progression=:progression,
        syllabus=:syllabus
        WHERE
        id = $id
        ";

        //prepare query
        $statement= $this->conn->prepare($query);

        //sanitaze from tags 
        $this->code=htmlspecialchars(strip_tags($this->code));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->progression=htmlspecialchars(strip_tags($this->progression));
        $this->syllabus=htmlspecialchars(strip_tags($this->syllabus));

        //bind values 
        $statement->bindParam(":code", $this->code);
        $statement->bindParam(":name", $this->name);
        $statement->bindParam(":progression", $this->progression);
        $statement->bindParam(":syllabus", $this->syllabus);

        //execute query
        if($statement->execute()) {
            return true;
        } else {
            return false;
        } 
    }

    //delete a specific course
    function delete($id) {
        $query= "DELETE FROM courses WHERE id = $id";
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        return $statement;
    }
}
?>