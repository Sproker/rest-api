<?php

namespace src\Models;

class Loan
{

    private string $personal_id;
    private string $name;
    private float $amount;
    private int $term;
    private float $total_amount;

    public function __construct(string $personal_id, string $name, float $amount, int $term, float $total_amount)
    {
        $this->personal_id = $personal_id;
        $this->name = $name;
        $this->amount = $amount;
        $this->term = $term;
        $this->total_amount = $total_amount;
    }

    public function getPersonalId(): string
    {
        return $this->personal_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLoanAmount(): float
    {
        return $this->amount;
    }

    public function getTerm(): int
    {
        return $this->term;
    }

    public function getTotalAmount(): float
    {
        return $this->total_amount;
    }
}