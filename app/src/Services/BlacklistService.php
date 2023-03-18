<?php

namespace src\Services;

use PDO;

require_once __DIR__ . '/../inc/config.php';

class BlacklistService
{

    private PDO $db;

    public function __construct()
    {
        // Initialize database connection
        $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    }

    public function isBlacklisted(string $personalId): bool
    {
        // Check if the personal ID is blacklisted
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM Blacklist WHERE personal_id = ?");
        $stmt->execute([$personalId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'] > 0;
    }
}