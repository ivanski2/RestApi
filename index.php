<?php 
declare(strict_types=1);
//autoload register
spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});
//set error handler
set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

//change default to json
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if ($parts[2] != "participant") {
    http_response_code(404);
    exit;
}
$id = $parts[3] ?? null;
//set database connection
$database = new Database("localhost", "participant_db", "ime", "parola!");
//only for development in production need to be create config ......... for test just add yours name and password

$gateway = new ParticipantGateway($database);

//create controller
$controller = new ParticipantController($gateway);
//process request
$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);?>