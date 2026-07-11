<?php
require_once __DIR__ . '/config.php';
$db = getDB();
$results = [];

// 1. Create closed_periods table
try {
    $db->exec("
        CREATE TABLE IF NOT EXISTS closed_periods (
            id INT AUTO_INCREMENT PRIMARY KEY,
            `year_month` VARCHAR(7) NOT NULL UNIQUE,
            closed_by INT NOT NULL,
            notes TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_year_month (`year_month`)
        ) ENGINE=InnoDB
    ");
    $results[] = 'closed_periods table OK';
} catch (Exception $e) {
    $results[] = 'closed_periods: ' . $e->getMessage();
}

// 2. Create pending_changes table
try {
    $db->exec("
        CREATE TABLE IF NOT EXISTS pending_changes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            entity_type VARCHAR(30) NOT NULL,
            entity_id INT DEFAULT NULL,
            action_type VARCHAR(20) NOT NULL,
            change_data JSON NOT NULL,
            description VARCHAR(500) NOT NULL,
            period VARCHAR(7) NOT NULL,
            requested_by INT NOT NULL,
            requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            status VARCHAR(20) NOT NULL DEFAULT 'pending',
            reviewed_by INT DEFAULT NULL,
            reviewed_at DATETIME DEFAULT NULL,
            review_notes TEXT DEFAULT NULL,
            INDEX idx_status (status),
            INDEX idx_period (period)
        ) ENGINE=InnoDB
    ");
    $results[] = 'pending_changes table OK';
} catch (Exception $e) {
    $results[] = 'pending_changes: ' . $e->getMessage();
}

jsonResponse(['success' => true, 'results' => $results]);
