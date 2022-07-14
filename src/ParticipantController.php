<?php

class ParticipantController
{
    public function __construct(ParticipantGateway $gateway)
    {
       $this->gateway= $gateway;
    }

    //process request
    public function processRequest(string $method, ?string $id): void
    {
        if ($id) {
            
            $this->processResourceRequest($method, $id);
            
        } else {
            
            $this->processCollectionRequest($method);

        }
    }
    //Resource by proces request
    private function processResourceRequest(string $method, string $id): void
    {
        $participant = $this->gateway->get($id);
        
        if ( ! $participant) {
            http_response_code(404);
            echo json_encode(["message" => "participant not found"]);
            return;
        }
        
        switch ($method) {
            case "GET":
                echo json_encode($participant);
                break;
                
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                
                
                $rows = $this->gateway->update($participant, $data);

                echo json_encode([
                    "message" => "Participant $id updated",
                    "rows" => $rows
                ]);
                break;
                
            case "DELETE":
                $rows = $this->gateway->delete($id);
                
                echo json_encode([
                    "message" => "Participant $id deleted",
                    "rows" => $rows
                ]);
                break;
                
            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE");
        }
    }
    //process colection request GET/POST
    private function processCollectionRequest(string $method): void
    {
        switch ($method) {
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;
                
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                
                $errors = $this->getValidationErrors($data);
                
                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                
                $id = $this->gateway->create($data);
                
                http_response_code(201);
                echo json_encode([
                    "message" => "Participant created",
                    "id" => $id
                ]);
                break;
            
            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }
    //validation data error
    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];
        
        if (!isset($data["name"]) || empty($data['name'])) {
            $errors[] = "name is required";
        }

        if (!isset($data["country"]) || empty($data['country'])) {
            $errors[] = "country is required";
        }
        
        if ( isset($data['position']) && ($data["position"]!="goalkeeper") && ($data["position"]!="defender") && ($data["position"]!="midfielder") && ($data["position"]!="forward") ){
            $errors[] = "position can be only goalkeeper or defender or midfielder or forward";
        }
        
        
        return $errors;
    }
}
?>