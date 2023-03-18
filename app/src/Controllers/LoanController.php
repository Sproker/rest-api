<?php

namespace src\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response as Response;
use src\Models\Loan;
use src\Services\BlacklistService;
use src\Services\LoanService;


class LoanController
{

    private LoanService $loanService;
    private BlacklistService $blacklistService;

    public function __construct()
    {
        $this->loanService = new LoanService();
        $this->blacklistService = new BlacklistService();
    }

    public function home(Request $request, Response $response): Response
    {
        return $response->withStatus(200)->withJson(['message' => 'Service is up and running.']);
    }

    public function apply(Request $request, Response $response): Response
    {

        $jsonParams = $request->getBody();
        $params = json_decode($jsonParams, true);
        $personalId = $params['personal_id'] ?? null;
        $name = $params['name'] ?? null;
        $loanAmount = $params['amount'] ?? null;
        $term = $params['term'] ?? null;

        // Check if any of the required parameters are empty
        if (empty($personalId) || empty($name) || empty($loanAmount) || empty($term)) {
            $missingParams = [];
            if (empty($personalId)) {
                $missingParams[] = 'personal_id';
            }
            if (empty($name)) {
                $missingParams[] = 'name';
            }
            if (empty($loanAmount)) {
                $missingParams[] = 'amount';
            }
            if (empty($term)) {
                $missingParams[] = 'term';
            }
            $message = 'Bad Request. Required parameters missing: ' . implode(', ', $missingParams);
            return $response->withStatus(400)->withJson(compact('message'));
        }

        // Check if the borrower is blacklisted
        if ($this->blacklistService->isBlacklisted($personalId)) {
            return $response->withStatus(403)->withJson(['message' => 'Loan application rejected. Borrower is blacklisted.']);
        }

        // Check if there have been too many loan applications from this personal ID in the last 24 hours
        if ($this->loanService->isOverLimit($personalId)) {
            return $response->withStatus(429)->withJson(['message' => 'Loan application rejected. Too many applications from this personal ID.']);
        }

        // Total amount
        $totalAmount = $this->loanService->calculateTotalAmount($loanAmount, $term);

        // Apply for loan
        $loan = new Loan($personalId, $name, $loanAmount, $term, $totalAmount);
        $this->loanService->applyForLoan($loan);

        return $response->withJson(['message' => 'Loan application successful.']);
    }

    public function list(Request $request, Response $response, array $args): Response
    {
        $personalId = $args['personal_id'];

        // Fetch loans by personal ID from the LoanService
        $loans = $this->loanService->getByPersonalId($personalId);

        if (empty($loans)) {
            $errorMessage = "No loans found for personal ID: " . $personalId;
            return $response->withStatus(404)->withJson(['error' => $errorMessage]);
        }

        return $response->withStatus(200)->withJson($loans);
    }
}