<?php

namespace App\Config;

class Config {
    // Application settings
    const APP_NAME = 'RH Management';
    const APP_VERSION = '1.0.0';
    const APP_URL = 'http://localhost:8000';
    
    // Session configuration
    const SESSION_LIFETIME = 7200; // 2 hours
    const SESSION_NAME = 'rh_session';
    
    // Pagination settings
    const ITEMS_PER_PAGE = 10;
    
    // File upload settings
    const UPLOAD_DIR = __DIR__ . '/../../public/uploads';
    const MAX_FILE_SIZE = 5242880; // 5MB
    const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'pdf'];
    
    // Email settings
    const SMTP_HOST = 'smtp.gmail.com';
    const SMTP_PORT = 587;
    const SMTP_SECURE = 'tls';
    const SMTP_FROM = 'noreply@rhmanagement.com';
    const SMTP_FROM_NAME = 'RH Management';
    
    // Leave settings
    const MAX_LEAVE_DAYS = 30;
    const MIN_LEAVE_NOTICE = 3; // days
    
    // Overtime settings
    const MAX_OVERTIME_HOURS = 12;
    const OVERTIME_RATE = 1.5;
    const NIGHT_HOURS_RATE = 1.25;
    
    // Log settings
    const LOG_DIR = __DIR__ . '/../../logs';
    const LOG_FILE = 'app.log';
    
    // Initialize application settings
    public static function init() {
        // Set timezone
        date_default_timezone_set('Africa/Casablanca');
        
        // Configure session
        ini_set('session.gc_maxlifetime', self::SESSION_LIFETIME);
        session_name(self::SESSION_NAME);
        
        // Create required directories
        self::createDirectories();
        
        // Error reporting in development
        if (self::isDevelopment()) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else {
            error_reporting(0);
            ini_set('display_errors', 0);
        }
    }
    
    // Check if application is in development mode
    public static function isDevelopment() {
        return getenv('APP_ENV') === 'development';
    }
    
    // Create required directories
    private static function createDirectories() {
        $directories = [
            self::UPLOAD_DIR,
            self::LOG_DIR
        ];
        
        foreach ($directories as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
        }
    }
    
    // Get configuration value
    public static function get($key) {
        return defined("self::$key") ? constant("self::$key") : null;
    }
}

// Initialize configuration
Config::init();