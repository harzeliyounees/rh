<?php

namespace App\Controllers;

use App\Models\Overtime;
use App\Models\Employee;

class OvertimeController {
    private $overtime;
    private $employee;

    public function __construct() {
        if (!isAuthenticated()) {
            redirect('/login');
        }
        $this->overtime = new Overtime();
        $this->employee = new Employee();
    }

    public function index() {
        $records = isAdmin() ? 
            $this->overtime->getAll() : 
            $this->overtime->getByEmployee($_SESSION['user']->getId());
        require_once __DIR__ . '/../views/heures-supp/index.php';
    }

    public function create() {
        if (!isAdmin()) {
            setFlashMessage('Accès non autorisé', 'danger');
            redirect('/heures-supp');
        }
        $employees = $this->employee->getAll();
        require_once __DIR__ . '/../views/heures-supp/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isAdmin()) {
            redirect('/heures-supp');
        }

        $data = [
            'employee_id' => $_POST['employee_id'] ?? '',
            'date' => $_POST['date'] ?? '',
            'heures' => $_POST['heures'] ?? ''
        ];

        $errors = validateOvertime($data);

        if (!empty($errors)) {
            setFlashMessage(implode('<br>', $errors), 'danger');
            redirect('/heures-supp/create');
        }

        try {
            if ($this->overtime->create($data)) {
                setFlashMessage('Heures supplémentaires enregistrées', 'success');
                redirect('/heures-supp');
            } else {
                setFlashMessage('Erreur lors de l\'enregistrement', 'danger');
                redirect('/heures-supp/create');
            }
        } catch (\Exception $e) {
            setFlashMessage('Une erreur est survenue', 'danger');
            logAction('Overtime Create Error', $e->getMessage());
            redirect('/heures-supp/create');
        }
    }

    public function edit($id) {
        if (!isAdmin()) {
            setFlashMessage('Accès non autorisé', 'danger');
            redirect('/heures-supp');
        }

        $record = $this->overtime->find($id);
        if (!$record) {
            setFlashMessage('Enregistrement non trouvé', 'danger');
            redirect('/heures-supp');
        }

        $employees = $this->employee->getAll();
        require_once __DIR__ . '/../views/heures-supp/edit.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isAdmin()) {
            redirect('/heures-supp');
        }

        $data = [
            'date' => $_POST['date'] ?? '',
            'heures' => $_POST['heures'] ?? ''
        ];

        $errors = validateOvertime($data);

        if (!empty($errors)) {
            setFlashMessage(implode('<br>', $errors), 'danger');
            redirect("/heures-supp/edit/$id");
        }

        try {
            if ($this->overtime->update($id, $data)) {
                setFlashMessage('Heures supplémentaires modifiées', 'success');
                redirect('/heures-supp');
            } else {
                setFlashMessage('Erreur lors de la modification', 'danger');
                redirect("/heures-supp/edit/$id");
            }
        } catch (\Exception $e) {
            setFlashMessage('Une erreur est survenue', 'danger');
            logAction('Overtime Update Error', $e->getMessage());
            redirect("/heures-supp/edit/$id");
        }
    }

    public function delete($id) {
        if (!isAdmin()) {
            setFlashMessage('Accès non autorisé', 'danger');
            redirect('/heures-supp');
        }

        try {
            if ($this->overtime->delete($id)) {
                setFlashMessage('Enregistrement supprimé avec succès', 'success');
            } else {
                setFlashMessage('Erreur lors de la suppression', 'danger');
            }
        } catch (\Exception $e) {
            setFlashMessage('Une erreur est survenue', 'danger');
            logAction('Overtime Delete Error', $e->getMessage());
        }

        redirect('/heures-supp');
    }

    public function export() {
        if (!isAdmin()) {
            redirect('/heures-supp');
        }

        $records = $this->overtime->getAll();
        $data = [
            ['ID', 'Employé', 'Date', 'Heures', 'Créé le']
        ];

        foreach ($records as $record) {
            $data[] = [
                $record->id,
                $record->nom . ' ' . $record->prenom,
                formatDate($record->date),
                $record->heures,
                formatDate($record->created_at)
            ];
        }

        arrayToCSV($data, 'heures_supplementaires_' . date('Y-m-d') . '.csv');
    }
}