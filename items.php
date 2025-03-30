<?php
class Item {
    private $conn;
    private $table_name = "items"; 

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getItemsByIdRange($item_id, $name, $frm_amount, $to_endamount) { 
        $idArray = explode(",", $item_id);
        $placeholders = implode(",", array_fill(0, count($idArray), "?"));

        // Base query with ID condition
        $query = "SELECT id, name, barcode, amount, making, nw 
                  FROM " . $this->table_name . " 
                  WHERE id IN ($placeholders)";

        $params = $idArray; // Start with ID parameters

        if (!empty($name)) {
            $query .= " AND TRIM(`name`) = ?";
            $params[] = trim($name);
        }

        if (!empty($frm_amount) && !empty($to_endamount)) {
            $query .= " AND CAST(`amount` AS DECIMAL(10,2)) BETWEEN ? AND ?";
            $params[] = (float)$frm_amount;
            $params[] = (float)$to_endamount;
        }

        $stmt = $this->conn->prepare($query);

        // Bind all parameters
        foreach ($params as $index => $param) {
            $stmt->bindValue($index + 1, $param, PDO::PARAM_STR);
        }

        if (!$stmt->execute()) {
            print_r($stmt->errorInfo());
            exit;
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($result)) {
            return ["success" => false, "message" => "No items found"];
        }

        return ["success" => true, "data" => $result];
    }
}
?>
