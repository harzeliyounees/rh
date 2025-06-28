<?php

namespace App\Controllers;

use App\Models\Employee;

class EmployeeController {
    private $employee;

    public function __construct() {
        if (!isAuthenticated()) {
            redirect('/login');
        }
        $this->employee = new Employee();
    }

    public function index() {
        $employees = $this->employee->getAll();
        require_once __DIR__ . '/../views/employees/index.php';
    }

    public function create() {
        if (!isAdmin()) {
            setFlashMessage('Accès non autorisé', 'danger');
            redirect('/employees');
        }
        require_once __DIR__ . '/../views/employees/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isAdmin()) {
            redirect('/employees');
        }

        $data = [
            'nom' => $_POST['nom'] ?? '',
            'prenom' => $_POST['prenom'] ?? '',
            'cin' => $_POST['cin'] ?? '',
            'categorie' => $_POST['categorie'] ?? '',
            'classe' => $_POST['classe'] ?? '',
            'specificite' => $_POST['specificite'] ?? '',
            'dateNaissance' => $_POST['dateNaissance'] ?? '',
            'dateRecrutement' => $_POST['dateRecrutement'] ?? ''
        ];

        $errors = validateEmployee($data);

        if (!empty($errors)) {
            setFlashMessage(implode('<br>', $errors), 'danger');
            redirect('/employees/create');
        }

        try {
            if ($this->employee->create($data)) {
                setFlashMessage('Employé ajouté avec succès', 'success');
                redirect('/employees');
            } else {
                setFlashMessage('Erreur lors de l\'ajout de l\'employé', 'danger');
                redirect('/employees/create');
            }
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                setFlashMessage('Ce CIN existe déjà', 'danger');
            } else {
                setFlashMessage('Une erreur est survenue', 'danger');
                logAction('Employee Create Error', $e->getMessage());
            }
            redirect('/employees/create');
        }
    }

    public function edit($id) {
        if (!isAdmin()) {
            setFlashMessage('Accès non autorisé', 'danger');
            redirect('/employees');
        }

        $employee = $this->employee->find($id);
        if (!$employee) {
            setFlashMessage('Employé non trouvé', 'danger');
            redirect('/employees');
        }

        require_once __DIR__ . '/../views/employees/edit.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isAdmin()) {
            redirect('/employees');
        }

        $data = [
            'nom' => $_POST['nom'] ?? '',
            'prenom' => $_POST['prenom'] ?? '',
            'cin' => $_POST['cin'] ?? '',
            'categorie' => $_POST['categorie'] ?? '',
            'classe' => $_POST['classe'] ?? '',
            'specificite' => $_POST['specificite'] ?? '',
            'dateNaissance' => $_POST['dateNaissance'] ?? '',
            'dateRecrutement' => $_POST['dateRecrutement'] ?? ''
        ];

        $errors = validateEmployee($data);

        if (!empty($errors)) {
            setFlashMessage(implode('<br>', $errors), 'danger');
            redirect("/employees/edit/$id");
        }

        try {
            if ($this->employee->update($id, $data)) {
                setFlashMessage('Employé modifié avec succès', 'success');
                redirect('/employees');
            } else {
                setFlashMessage('Erreur lors de la modification', 'danger');
                redirect("/employees/edit/$id");
            }
        } catch (\PDOException $e) {
            setFlashMessage('Une erreur est survenue', 'danger');
            logAction('Employee Update Error', $e->getMessage());
            redirect("/employees/edit/$id");
        }
    }

    public function delete($id) {
        if (!isAdmin()) {
            setFlashMessage('Accès non autorisé', 'danger');
            redirect('/employees');
        }

        try {
            if ($this->employee->delete($id)) {
                setFlashMessage('Employé supprimé avec succès', 'success');
            } else {
                setFlashMessage('Erreur lors de la suppression', 'danger');
            }
        } catch (\PDOException $e) {
            setFlashMessage('Impossible de supprimer cet employé', 'danger');
            logAction('Employee Delete Error', $e->getMessage());
        }

        redirect('/employees');
    }

    public function export() {
        if (!isAuthenticated()) {
            redirect('/login');
        }

        $employees = $this->employee->getAll();
        $data = [];
        
        // Add headers
        $data[] = ['ID', 'Nom', 'Prénom', 'CIN', 'Catégorie', 'Classe', 'Date Naissance', 'Date Recrutement'];
        
        // Add data
        foreach ($employees as $employee) {
            $data[] = [
                $employee->id,
                $employee->nom,
                $employee->prenom,
                $employee->cin,
                $employee->categorie,
                $employee->classe,
                formatDate($employee->dateNaissance),
                formatDate($employee->dateRecrutement)
            ];
        }

        arrayToCSV($data, 'employees_' . date('Y-m-d') . '.csv');
    }
}