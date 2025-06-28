<?php

function validateEmployee($data) {
    $errors = [];
    
    // Required fields
    $required = ['nom', 'prenom', 'cin', 'categorie', 'classe', 'dateNaissance', 'dateRecrutement'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            $errors[] = "Le champ " . ucfirst($field) . " est requis";
        }
    }

    // CIN format (example: AB123456)
    if (!empty($data['cin']) && !preg_match('/^[A-Z]{2}[0-9]{6}$/', $data['cin'])) {
        $errors[] = "Format CIN invalide (exemple: AB123456)";
    }

    // Date validations
    if (!empty($data['dateNaissance'])) {
        $dob = new DateTime($data['dateNaissance']);
        $now = new DateTime();
        $age = $dob->diff($now)->y;
        
        if ($age < 18) {
            $errors[] = "L'employé doit avoir au moins 18 ans";
        }
    }

    return $errors;
}

function validateLeave($data) {
    $errors = [];

    // Required fields
    if (empty($data['type'])) {
        $errors[] = "Le type de congé est requis";
    }

    if (empty($data['dateDebut']) || empty($data['dateFin'])) {
        $errors[] = "Les dates de début et de fin sont requises";
    }

    // Date validation
    if (!empty($data['dateDebut']) && !empty($data['dateFin'])) {
        $debut = new DateTime($data['dateDebut']);
        $fin = new DateTime($data['dateFin']);
        
        if ($fin < $debut) {
            $errors[] = "La date de fin doit être postérieure à la date de début";
        }
    }

    return $errors;
}

function validateOvertime($data) {
    $errors = [];

    if (empty($data['date'])) {
        $errors[] = "La date est requise";
    }

    if (!isset($data['heures']) || $data['heures'] <= 0) {
        $errors[] = "Le nombre d'heures doit être supérieur à 0";
    }

    if (isset($data['heures']) && $data['heures'] > 12) {
        $errors[] = "Le nombre d'heures ne peut pas dépasser 12 heures par jour";
    }

    return $errors;
}

function validateUser($data, $isRegistration = true) {
    $errors = [];

    if (empty($data['username'])) {
        $errors[] = "Le nom d'utilisateur est requis";
    } elseif (strlen($data['username']) < 3) {
        $errors[] = "Le nom d'utilisateur doit contenir au moins 3 caractères";
    }

    if ($isRegistration) {
        if (empty($data['password'])) {
            $errors[] = "Le mot de passe est requis";
        } elseif (strlen($data['password']) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
        }

        if (empty($data['password_confirmation']) || $data['password'] !== $data['password_confirmation']) {
            $errors[] = "Les mots de passe ne correspondent pas";
        }
    }

    return $errors;
}

function sanitizeInput($data) {
    $sanitized = [];
    foreach ($data as $key => $value) {
        if (is_string($value)) {
            $sanitized[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        } else {
            $sanitized[$key] = $value;
        }
    }
    return $sanitized;
}