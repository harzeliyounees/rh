<?php

namespace App\Controllers;

use App\Models\Leave;
use App\Models\Employee;

class LeaveController {
    private $leave;
    private $employee;

    public function __construct() {
        if (!isAuthenticated()) {
            redirect('/login');
        }
        $this->leave = new Leave();
        $this->employee = new Employee();
    }

    public function index() {
        $leaves = isAdmin() ? 
            $this->leave->getAll() : 
            $this->leave->getByEmployee($_SESSION['user']->getId());
        require_once __DIR__ . '/../views/conges/index.php';
    }

    public function create() {
        $employees = isAdmin() ? $this->employee->getAll() : null;
        require_once __DIR__ . '/../views/conges/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/conges');
        }

        $data = [
            'employee_id' => isAdmin() ? ($_POST['employee_id'] ?? '') : $_SESSION['user']->getId(),
            'type' => $_POST['type'] ?? '',
            'date_debut' => $_POST['date_debut'] ?? '',
            'date_fin' => $_POST['date_fin'] ?? '',
        ];

        $errors = validateLeave($data);

        if (!empty($errors)) {
            setFlashMessage(implode('<br>', $errors), 'danger');
            redirect('/conges/create');
        }

        try {
            if ($this->leave->create($data)) {
                setFlashMessage('Demande de congé créée avec succès', 'success');
                redirect('/conges');
            } else {
                setFlashMessage('Erreur lors de la création de la demande', 'danger');
                redirect('/conges/create');
            }
        } catch (\Exception $e) {
            setFlashMessage('Une erreur est survenue', 'danger');
            logAction('Leave Create Error', $e->getMessage());
            redirect('/conges/create');
        }
    }

    public function updateStatus($id) {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/conges');
        }

        $status = $_POST['status'] ?? '';
        if (!in_array($status, ['approved', 'rejected'])) {
            setFlashMessage('Statut invalide', 'danger');
            redirect('/conges');
        }

        try {
            if ($this->leave->updateStatus($id, $status)) {
                $leave = $this->leave->find($id);
                $message = $status === 'approved' ? 'approuvée' : 'refusée';
                setFlashMessage("Demande de congé $message", 'success');
                
                // Log the action
                logAction('Leave Status Update', "Leave ID: $id, Status: $status");
            } else {
                setFlashMessage('Erreur lors de la mise à jour du statut', 'danger');
            }
        } catch (\Exception $e) {
            setFlashMessage('Une erreur est survenue', 'danger');
            logAction('Leave Status Update Error', $e->getMessage());
        }

        redirect('/conges');
    }

    public function delete($id) {
        if (!isAdmin()) {
            setFlashMessage('Accès non autorisé', 'danger');
            redirect('/conges');
        }

        try {
            if ($this->leave->delete($id)) {
                setFlashMessage('Demande de congé supprimée avec succès', 'success');
            } else {
                setFlashMessage('Erreur lors de la suppression', 'danger');
            }
        } catch (\Exception $e) {
            setFlashMessage('Une erreur est survenue', 'danger');
            logAction('Leave Delete Error', $e->getMessage());
        }

        redirect('/conges');
    }

    public function export() {
        if (!isAuthenticated()) {
            redirect('/login');
        }

        $leaves = isAdmin() ? 
            $this->leave->getAll() : 
            $this->leave->getByEmployee($_SESSION['user']->getId());

        $data = [
            ['ID', 'Employé', 'Type', 'Date Début', 'Date Fin', 'Statut', 'Créé le']
        ];

        foreach ($leaves as $leave) {
            $data[] = [
                $leave->id,
                $leave->nom . ' ' . $leave->prenom,
                $leave->type,
                formatDate($leave->date_debut),
                formatDate($leave->date_fin),
                $leave->statut,
                formatDate($leave->created_at)
            ];
        }

        arrayToCSV($data, 'conges_' . date('Y-m-d') . '.csv');
    }
}