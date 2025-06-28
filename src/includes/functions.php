<?php

/**
 * Helper functions for the RH Management application
 */

function redirect($path) {
    header("Location: $path");
    exit();
}

function setFlashMessage($message, $type = 'info') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}

function getFlashMessage() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $type = $_SESSION['message_type'];
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}

function isAuthenticated() {
    return isset($_SESSION['user']);
}

function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']->role === 'admin';
}

function formatDate($date, $format = 'd/m/Y') {
    return date($format, strtotime($date));
}

function calculateLeaveDuration($dateDebut, $dateFin) {
    $debut = new DateTime($dateDebut);
    $fin = new DateTime($dateFin);
    $interval = $debut->diff($fin);
    return $interval->days + 1;
}

function calculateAge($dateNaissance) {
    $dob = new DateTime($dateNaissance);
    $now = new DateTime();
    return $dob->diff($now)->y;
}

function formatMoney($amount, $decimals = 2) {
    return number_format($amount, $decimals, ',', ' ') . ' DH';
}

function getMonthName($month) {
    $months = [
        1 => 'Janvier', 2 => 'Février', 3 => 'Mars',
        4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
        7 => 'Juillet', 8 => 'Août', 9 => 'Septembre',
        10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
    ];
    return $months[$month] ?? '';
}

function generateReference($prefix, $id) {
    return sprintf('%s%06d', $prefix, $id);
}

function debug($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

function logAction($action, $details = '') {
    $logFile = __DIR__ . '/../../logs/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $user = $_SESSION['user']->username ?? 'System';
    $logEntry = "[$timestamp] $user - $action" . ($details ? ": $details" : '') . PHP_EOL;
    
    error_log($logEntry, 3, $logFile);
}

function getLeaveStatus($status) {
    $statusLabels = [
        'pending' => '<span class="badge bg-warning">En attente</span>',
        'approved' => '<span class="badge bg-success">Approuvé</span>',
        'rejected' => '<span class="badge bg-danger">Refusé</span>'
    ];
    return $statusLabels[$status] ?? $status;
}

function arrayToCSV($array, $filename) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
    
    foreach ($array as $row) {
        fputcsv($output, $row, ';');
    }
    
    fclose($output);
    exit();
}