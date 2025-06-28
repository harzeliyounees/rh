<?php

namespace App\Models;

use PDO;
use App\Config\Database;

class Panier {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $query = "INSERT INTO panier (employee_id, date, montant) 
                 VALUES (:employee_id, :date, :montant)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'employee_id' => $data['employee_id'],
            'date' => $data['date'],
            'montant' => $data['montant']
        ]);
    }

    public function getByEmployee($employeeId) {
        $query = "SELECT * FROM panier WHERE employee_id = :employee_id ORDER BY date DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['employee_id' => $employeeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add other necessary methods here
}