<?php

//require files
require 'config/Database.php';
require 'classes/Courses.php';

// Settings for headers 
header('Content-Type: application/json;'); //json data
header('Access-Control-Allow-Origin: *'); //reach from every domain 
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT'); //allow metods
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Access-Control-Allow-Origin, Authorization, X-Requested-With'); //allow headers 

//Store requested method in a variable 
$method = $_SERVER['REQUEST_METHOD'];

//Create variable "id" if there is any id
if(isset($_GET['id'])) {
    $id = $_GET['id'];
}

//Create Database object and connect
$database = new Database();
$db = $database->connect();

//Create an instance of the class "Courses" and send the db connection as a parameter 
$courses = new Courses($db);

//swish that us the encloses method
switch($method) {
    //GET
    case 'GET':
        if(isset($id)) {
            //if there is an id, get specific course
            $result =$courses->getOne($id);
        } else {
            //to get all courses
            $result = $courses->getAll();
        }
        //chech if result contain any data
        if(sizeof($result) > 0) {
            http_response_code(200); //OK 
        } else {
            http_response_code(404); //can´t find data
            $result = array("message" => "Hittade inga kurser");
        }
        break;
        //POST
    case 'POST':
        //read submitted data and make php objects
        $data = json_decode(file_get_contents("php://input"));
        
        //send data to props in class "Courses" if it isn't empty
        if(
            !empty($data->code) &&
            !empty($data->name) &&
            !empty($data->progression) &&
            !empty($data->syllabus)
        ){
            $courses->code = $data->code;
            $courses->name = $data->name;
            $courses->progression = $data->progression;
            $courses->syllabus = $data->syllabus;
        
            //create course
            if($courses->create()) {
                http_response_code(201); //created
                $result = array("message" => "Kursen är skapad");
            } else {
                http_response_code(503); //Server error
                $result = array("message" => "Det gick tyvärr inte att skapa kursen");
            };
        }
        break;
        //PUT
        case 'PUT':
            //error because no id
            if(!isset($id)) {
                http_response_code(510); //not extended
                $result = array("message" => "kunde inte uppdatera kursen eftersom inget id skickades med");
            } else {
                $data = json_decode(file_get_contents("php://input"));

                //send data to props in class "Courses"
                $courses->code = $data->code;
                $courses->name = $data->name;
                $courses->progression = $data->progression;
                $courses->syllabus = $data->syllabus;

                //update 
                if($courses->update($id)) {
                    http_response_code(200); //ok
                    $result = array("message" => "kursen är uppdaterad");
                } else {
                    http_response_code(503); //server error
                    $result = array("message" => "det gick inte att uppdatera kursen");
                }
            }
            break;
            case 'DELETE':
                //error because no id
                if(!isset($id)) {
                    http_response_code(510); 
                    $result = array("message" => "kunde inte radera kursen eftersom det inte hittades");
                } else {
                    //delete course with a specific id
                    if($courses->delete($id)) {
                        http_response_code(200); //ok
                        $result = array("message" => "kursen är raderad");
                    } else {
                        http_response_code(503); //server error
                        $result = array("message" => "kursen är inte raderad");
                    }
                }
                break;

}

//Return result as JSON
echo json_encode($result);

//close database-connection 
$db = $database->close();

?>