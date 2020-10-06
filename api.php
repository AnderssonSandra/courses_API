<?php

//hämta filer
require 'config/Database.php';
require 'classes/Courses.php';

//Inställningar med headers- webbtjänsten tillgänglig från alla domäner
header('Content-Type:application/json; charset=UTF-8'); //json format förväntas att hämtas
header('Access-Control-Allow-Origin: *'); //kommer åt webbtjänsten från alla domäner
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT'); //tillåter dessa metoder
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With'); //tillåter att headers används

//tar in metod som är inhämtad vid anropet och lagra i variabel
$method = $_SERVER['REQUEST_METHOD'];

//om id finns i url:en, skapar i variabel "id"
if(isset($_GET['id'])) {
    $id = $_GET['id'];
}

//skapa ny klass av databasen och koppla till den
$database = new Database();
$db = $database->connect();

//skapa en instans av klassen för att skicka SQL frågor till
//skickar med databas-anslutning som parameter
$courses = new Courses($db);

//swish-sats som kollar vilken metod som är inskickad
switch($method) {
    case 'GET':
        if(isset($id)) {
            //kör denna om det finns ett specifikt angivet id
            $result =$courses->getOne($id);
        } else {
            //kör denna om hela tabellen ska läsas
            $result = $courses->getAll();
        }
        //kontollerar så att resultatet innehåller några rader
        if(sizeof($result) > 0) {
            http_response_code(200); //OK resultat
        } else {
            http_response_code(404); //hittar ej data
            $result = array("message" => "Hittade tyvärr inga kurser");
        }
        break;
        //POST
    case 'POST':
        //läser in data som är inskickad och gör till php objekt
        $data = json_decode(file_get_contents("php://input"));
        
        //kolla om data är tom, annas skickar den data till propsen i klassen courses
        if(
            !empty($data->code) &&
            !empty($data->name) &&
            !empty($data->progression) &&
            !empty($data->syllabus)
        ){
            //skickar in till properies i klassen courses. tar bort tags och specialtecken inför lagring i databas. 
            $courses->code = $data->code;
            $courses->name = $data->name;
            $courses->progression = $data->progression;
            $courses->syllabus = $data->syllabus;
        
            //kör funktion för att skapa kurs
            if($courses->create()) {
                http_response_code(201); //created
                $result = array("message" => "Kursen är skapad");
            } else {
                http_response_code(503); //Server error
                $result = array("message" => "Det gick tyvärr inte att skapa kursen");
            }
        }
        break;
        //PUT
        case 'PUT':
            //error om id inte skickas med
            if(!isset($id)) {
                http_response_code(510); //not extended
                $result = array("message" => "kunde inte uppdatera kursen eftersom inget id skickades med");
                //om id finns så kör denna
            } else {
                $data = json_decode(file_get_contents("php://input"));

                //skickar in till properies i klassen courses. tar bort tags och specialtecken inför lagring i databas. 
                $courses->code = $data->code;
                $courses->name = $data->name;
                $courses->progression = $data->progression;
                $courses->syllabus = $data->syllabus;

                //kör funktion för att uppdatera
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
                //om id inte skickas med
                if(!isset($id)) {
                    http_response_code(510); //gick ej
                    $result = array("message" => "kunde inte radera kursen eftersom det inte hittades");
                    //om id skickas
                } else {
                    //kör funktion för att radera rad
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

//Returnera resultatet som JSON
echo json_encode($result);

//stäng databas-kopplingen 
$db = $database->close();

?>