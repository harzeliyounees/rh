<?php

namespace App\Models;

use PDO;
use App\Config\Database;

class Leave {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $query = "INSERT INTO conges (employee_id, type, date_debut, date_fin, statut) 
                 VALUES (:employee_id, :type, :date_debut, :date_fin, :statut)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'employee_id' => $data['employee_id'],
            'type' => $data['type'],
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'statut' => 'pending'
        ]);
    }

    public function find($id) {
        $query = "SELECT c.*, e.nom, e.prenom 
                 FROM conges c
                 JOIN employees e ON c.employee_id = e.id 
                 WHERE c.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $query = "UPDATE conges 
                 SET type = :type, 
                     date_debut = :date_debut, 
                     date_fin = :date_fin, 
                     statut = :statut 
                 WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'id' => $id,
            'type' => $data['type'],
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'statut' => $data['statut']
        ]);
    }

    public function delete($id) {
        $query = "DELETE FROM conges WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    public function getByEmployee($employeeId) {
        $query = "SELECT * FROM conges WHERE employee_id = :employee_id ORDER BY date_debut DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['employee_id' => $employeeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingLeaves() {
        $query = "SELECT c.*, e.nom, e.prenom 
                 FROM conges c
                 JOIN employees e ON c.employee_id = e.id 
                 WHERE c.statut = 'pending'
                 ORDER BY c.date_debut ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE conges SET statut = :statut WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'id' => $id,
            'statut' => $status
        ]);
    }

    public function validate($data) {
        $errors = [];

        if (empty($data['employee_id'])) {
            $errors[] = "L'identifiant de l'employé est requis";
        }

        if (empty($data['type'])) {
            $errors[] = "Le type de congé est requis";
        }

        if (empty($data['date_debut'])) {
            $errors[] = "La date de début est requise";
        }

        if (empty($data['date_fin'])) {
            $errors[] = "La date de fin est requise";
        }

        if (!empty($data['date_debut']) && !empty($data['date_fin'])) {
            if (strtotime($data['date_fin']) < strtotime($data['date_debut'])) {
                $errors[] = "La date de fin doit être postérieure à la date de début";
            }
        }

        return $errors;
    }
}