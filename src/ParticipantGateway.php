<?php
//participant model
class ParticipantGateway
{
   private $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
    
    //function to get all participant
    public function getAll(): array
    {
        $sql = "SELECT *
                FROM participant
                ORDER BY create_at DESC";
                
        $stmt = $this->conn->query($sql);
        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        
            $data[] = $row;
        }
        
        return $data;
    }
    //create participant
    public function create(array $data): string
    {
        $sql = "INSERT INTO participant (name, country, birth_date ,position )
                VALUES (:name, :country, :birth_date, :position)";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(":country", $data["country"] ?? 0, PDO::PARAM_STR);
        $stmt->bindValue(":birth_date", $data["birth_date"] ?? 0, PDO::PARAM_INT);
        $stmt->bindValue(":position", $data["position"] ?? 0, PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $this->conn->lastInsertId();
    }
    //get participant by id
    public function get(int $id): array 
    {
        $sql = "SELECT *
                FROM participant
                WHERE id = :id
                ORDER BY create_at DESC";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data;
    }
    //get participant filtered by country
    public function getByCountry(string $country): array
    {
        $sql = "SELECT *
                FROM participant
                WHERE country = :country
                ORDER BY create_at DESC";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":country", $country, PDO::PARAM_STR);
        
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data;
    }
    //get participant filteret by position
    public function getByPosition(string $position): array
    {
        $sql = "SELECT *
                FROM participant
                WHERE position = :position
                ORDER BY create_at DESC";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":position", $position, PDO::PARAM_STR);
        
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data;
    }
    //update existed participant
    public function update(array $current, array $new): int
    {
        $sql = "UPDATE participant
                SET name = :name, country = :country, birth_date = :birth_date, position = :position
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":name", $new["name"] ?? $current["name"], PDO::PARAM_STR);
        $stmt->bindValue(":country", $new["country"] ?? $current["country"], PDO::PARAM_STR);
        $stmt->bindValue(":birth_date", $new["birth_date"] ?? $current["birth_date"], PDO::PARAM_INT);
        $stmt->bindValue(":position", $new["position"] ?? $current["position"], PDO::PARAM_STR);

      
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->rowCount();
    }
    //delete participant 
    public function delete(string $id): int
    {
        $sql = "DELETE FROM participant
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->rowCount();
    }
}
?>