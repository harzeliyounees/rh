<?php

namespace App\Models;

use PDO;
use App\Config\Database;

class Overtime {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $query = "INSERT INTO heures_supplementaires (employee_id, date, heures) 
                 VALUES (:employee_id, :date, :heures)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'employee_id' => $data['employee_id'],
            'date' => $data['date'],
            'heures' => $data['heures']
        ]);
    }

    public function find($id) {
        $query = "SELECT * FROM heures_supplementaires WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $query = "UPDATE heures_supplementaires 
                 SET date = :date, heures = :heures 
                 WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'id' => $id,
            'date' => $data['date'],
            'heures' => $data['heures']
        ]);
    }

    public function delete($id) {
        $query = "DELETE FROM heures_supplementaires WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    public function getByEmployee($employeeId, $month = null, $year = null) {
        $query = "SELECT * FROM heures_supplementaires WHERE employee_id = :employee_id";
        $params = ['employee_id' => $employeeId];

        if ($month && $year) {
            $query .= " AND MONTH(date) = :month AND YEAR(date) = :year";
            $params['month'] = $month;
            $params['year'] = $year;
        }

        $query .= " ORDER BY date DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalHours($employeeId, $month, $year) {
        $query = "SELECT SUM(heures) as total 
                 FROM heures_supplementaires 
                 WHERE employee_id = :employee_id 
                 AND MONTH(date) = :month 
                 AND YEAR(date) = :year";

        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'employee_id' => $employeeId,
            'month' => $month,
            'year' => $year
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function validate($data) {
        $errors = [];

        if (empty($data['employee_id'])) {
            $errors[] = "L'identifiant de l'employé est requis";
        }

        if (empty($data['date'])) {
            $errors[] = "La date est requise";
        }

        if (!isset($data['heures']) || $data['heures'] <= 0) {
            $errors[] = "Le nombre d'heures doit être supérieur à 0";
        }

        return $errors;
    }
}