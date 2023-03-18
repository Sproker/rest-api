<?php

namespace src\Services;

use PDO;
use src\Models\Loan;

require_once __DIR__ . '/../inc/config.php';

class LoanService
{

    private const INTEREST_RATE = 0.05;
    private PDO $db;

    public function __construct()
    {
        // Initialize database connection
        $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    }

    public function applyForLoan(Loan $loan): Loan
    {
        $personalId = $loan->getPersonalId();
        $name = $loan->getName();
        $loanAmount = $loan->getLoanAmount();
        $term = $loan->getTerm();
        $totalAmount = $loan->getTotalAmount();

        // Save loan data to database
        $stmt = $this->db->prepare("INSERT INTO Loans (personal_id, name, amount, term, total_amount) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$personalId, $name, $loanAmount, $term, $totalAmount]);

        return $loan;
    }

    public function calculateTotalAmount(float $loanAmount, int $term): float
    {
        return $loanAmount * (1 + self::INTEREST_RATE) ** $term;
    }

    public function isOverLimit(string $personalId): bool
    {
        // Check if the personal ID has made too many loan applications in the last 24 hours
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM Loans WHERE personal_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)");
        $stmt->execute([$personalId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'] >= 3; // Limit to 3 loan applications per day
    }

    public function getByPersonalId(string $personalId): array
    {
        // Fetch all loans with the specified personal ID from the database
        $stmt = $this->db->prepare("SELECT * FROM Loans WHERE personal_id = ?");
        $stmt->execute([$personalId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}