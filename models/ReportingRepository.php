<?php

namespace models;

use utils\MyFunctions;

class ReportingRepository extends Model
{
    protected MyFunctions $function;

    public function __construct()
    {
        parent::__construct();
        $this->function = new MyFunctions();
    }

    /**
     * Retrieves sales by period and optionally by user.
     *
     * @param string $period The period (SQL function) to group the sales by (e.g., "MONTH" or "YEAR").
     * @param string|null $andUserId Optional additional clause for the SQL query.
     * @param int|null $userId The optional user ID to filter the sales by user.
     * @return array An associative array containing sales by product for the specified period and the total sales.
     */
    public function salesBy(string $period, ?string $andUserId = "", ?int $userId = null)
    {
        $query = $this->pdo->prepare("SELECT p.name, SUM(l.quantity * p.price_ht) AS total_price
        FROM invoices i
        JOIN invoice_lines l ON i.id = l.invoice_id
        JOIN products p ON p.id = l.product_id
        WHERE YEAR(i.creation_date) = YEAR(NOW()) AND $period(i.creation_date) = $period(NOW()) $andUserId
        GROUP BY l.product_id");

        if ($userId) {
            $query->execute([
                $userId
            ]);
        } else {
            $query->execute();
        }

        $period = $query->fetchAll();
        $totalByPeriod = 0;
        foreach ($period as $total) {
            $totalByPeriod += $total['total_price'];
        }

        return compact("period", "totalByPeriod");
    }

    /**
     * Retrieves products by month with VAT and their corresponding total VAT amount.
     *
     * @return array An associative array containing the products sold by month, their VAT amounts, and the total VAT amount.
     */
    public function productByMonthWithVAT()
    {
        $query = $this->pdo->prepare("SELECT p.name, v.rate,
        MONTH(i.creation_date) AS month,
        SUM(l.quantity * p.price_ht) AS total_price
        FROM invoices i
        JOIN invoice_lines l ON i.id = l.invoice_id
        JOIN products p ON p.id = l.product_id
        JOIN vat v ON v.id = p.vat_id
        WHERE YEAR(i.creation_date) = YEAR(NOW())
        GROUP BY p.name, v.rate, MONTH(i.creation_date)
        ");

        $query->execute();
        $results = $query->fetchAll();

        $productsVAT = [];
        $totalVAT = 0;
        foreach ($results as &$result) {
            $result["month"] = $this->function->convertMonth($result["month"]);
            $productsVAT[] = [
                'VAT' => ($result["total_price"] * $result['rate']) / 100
            ];
            $totalVAT += ($result["total_price"] * $result['rate']) / 100;
        }

        return compact("results", "productsVAT", "totalVAT");
    }

    /**
     * Retrieves products by month with VAT and calculates the total VAT amount.
     *
     * @return array An associative array containing the products sold by month, their VAT rates, and the total VAT amount.
     */
    public function salesBySeller(int $userId)
    {
        $query = $this->pdo->prepare("SELECT u.username, 
        MONTH(i.creation_date) AS month,
        SUM(i.amount_et) AS total 
        FROM invoices i 
        JOIN users u ON u.id = i.user_id 
        WHERE YEAR(i.creation_date) = YEAR(NOW()) AND u.id = :userId
        GROUP BY u.username, month");

        $query->execute([
            'userId' => $userId
        ]);

        $resultByMonth = $query->fetchAll();
        $resultByYear = 0;
        foreach ($resultByMonth as &$result) {
            $result["month"] = $this->function->convertMonth($result["month"]);
            $resultByYear += $result["total"];
        }

        return compact("resultByMonth", "resultByYear");
    }
}
