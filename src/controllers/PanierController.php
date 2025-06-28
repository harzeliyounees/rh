<?php

namespace App\Controllers;

use App\Models\Panier;
use App\Models\Employee;

class PanierController {
    private $panier;
    private $employee;

    public function __construct() {
        if (!isAuthenticated()) {
            redirect('/login');
        }
        $this->panier = new Panier();
        $this->employee = new Employee();
    }

    public function index() {
        $records = isAdmin() ? 
            $this->panier->getAll() : 
            $this->panier->getByEmployee($_SESSION['user']->getId());
        require_once __DIR__ . '/../views/panier/index.php';
    }

    // Add other necessary methods here
}