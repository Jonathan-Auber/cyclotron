<?php

namespace models;

use models\ProductsRepository;
use Exception;

class InvoiceRepository extends Model
{
    protected string $table = "invoices";
    protected string $invoiceLinesTable;
    protected string $usersTable;
    protected string $customersTable;

    public function __construct()
    {
        parent::__construct();
        $this->invoiceLinesTable = "invoice_lines";
        $this->usersTable = "users";
        $this->customersTable = "customers";
    }

    /**
     * Retrieves invoice data for the specified customer ID.
     *
     * @param int $customerId The ID of the customer.
     * @return array An array containing the invoice data.
     */
    public function invoiceData(int $customerId)
    {
        $query = $this->pdo->prepare("SELECT *
        FROM {$this->table}
        WHERE customer_id = :customerId
        ORDER BY creation_date DESC");
        $query->execute([
            'customerId' => $customerId
        ]);

        return $query->fetchAll();
    }

    /**
     * Inserts a new invoice into the database.
     *
     * @param int $customerId The ID of the customer.
     * @param array $results An array of invoice line items.
     * @param ProductsRepository $productRepository The instance of ProductsRepository.
     * @return int The ID of the inserted invoice.
     */
    public function insertInvoice(
        int $customerId,
        array $results,
        ProductsRepository $productRepository
    ) {
        if (!empty($results)) {
            foreach ($results as $result) {
                if (isset($result["productId"]) && $result["numberOfProducts"] > 0) {
                    $product = $productRepository->find($result["productId"]);
                    if ($product['stock'] >= 0 && $product['stock'] > $result["numberOfProducts"]) {
                    } else {
                        $this->session->setFlashMessage("Il n'y a pas assez de stock pour " . $product['name']);
                        header("Location: /cyclotron/Invoice/createInvoice/" . $customerId);
                        return;
                    }
                } else {
                    $this->session->setFlashMessage("Un problème est survenu !");
                    header("Location: /cyclotron");
                    return;
                }
            }
        } else {
            $this->session->setFlashMessage("Un problème est survenu !");
            header("Location: /cyclotron");
            return;
        }


        $query = $this->pdo->prepare("INSERT INTO {$this->table} 
        SET customer_id = :customer, user_id = :user, amount_et = :excluded_tax, amount_it = :included_tax");
        $query->execute([
            'customer' => $customerId,
            'user' => $_SESSION['id'],
            'excluded_tax' => 0,
            'included_tax' => 0
        ]);
        $invoiceId = $this->pdo->lastInsertId();
        return $invoiceId;
    }

    /**
     * Updates an invoice with the specified ID by setting the excluded tax amount and included tax amount.
     *
     * @param int $invoiceId The ID of the invoice to update.
     * @param float $totalInvoice The total amount of the invoice excluding tax.
     * @param float $totalWithVat The total amount of the invoice including tax.
     * @return void
     */
    public function updateInvoice(int $invoiceId, float $totalInvoice, float $totalWithVat)
    {
        $query = $this->pdo->prepare("UPDATE {$this->table} 
            SET amount_et = :excluded_tax, amount_it = :included_tax
            WHERE id = :invoiceId");
        $query->execute([
            'invoiceId' => $invoiceId,
            'excluded_tax' => $totalInvoice,
            'included_tax' => $totalWithVat
        ]);
    }

    /**
     * Process and retrieve data from the POST request related to products and their quantities.
     *
     * This function processes the data sent via POST request to extract product IDs and corresponding quantities.
     * It constructs an array containing product information based on the received data.
     *
     * @return array Returns an array containing product information with 'productId' and 'numberOfProducts'.
     */
    public function postDataProcessing(int $customerId)
    {
        $result = [];
        for ($i = 1; $i <= count($_POST) / 2; $i++) {
            if ($_POST["product_$i"] === "Selectionnez un produit") {
                $this->session->setFlashMessage("Vous n'avez pas sélectionné de produit en ligne " . $i);
                header("Location: /cyclotron/Invoice/createInvoice/" . $customerId);
                return;
            }
            if (intval($_POST["numberOfProducts_$i"]) === 0) {
                $this->session->setFlashMessage("Vous n'avez pas indiqué de quantité pour le produit en ligne " . $i);
                header("Location: /cyclotron/Invoice/createInvoice/" . $customerId);
                return;
            }
            $result[] = [
                'productId' => $_POST["product_$i"],
                'numberOfProducts' => $_POST["numberOfProducts_$i"]
            ];
        }
        return $result;
    }

    /**
     * Retrieves invoice lines data for the specified invoice ID.
     *
     * @param int $invoiceId The ID of the invoice.
     * @return array An array containing the invoice lines data.
     */
    public function invoiceLinesData($invoiceId)
    {
        $queryData = $this->pdo->prepare("SELECT p.name, p.price_ht, l.quantity, v.rate
        FROM products p 
        JOIN invoice_lines l ON p.id = l.product_id 
        JOIN vat v ON p.vat_id = v.id 
        WHERE l.invoice_id = :invoiceId;
        ");

        $queryData->execute([
            'invoiceId' => $invoiceId
        ]);

        $invoiceData = $queryData->fetchAll();

        $invoiceLines = [];
        $totalInvoice = 0;
        $totalInvoiceVat = 0;
        foreach ($invoiceData as $line) {
            $invoiceLines[] = [
                'name' => $line['name'],
                'quantity' => $line['quantity'],
                'unit_price' => $line['price_ht'],
                'total_price' => $line['quantity'] * $line['price_ht'],
            ];
            $totalInvoice += floatval($line['quantity'] * $line['price_ht']);
            $totalInvoiceVat += floatval((($line['quantity'] * $line['price_ht']) * $line['rate']) / 100);
        }

        $totalWithVat = floatval($totalInvoice) + floatval($totalInvoiceVat);
        return compact('invoiceLines', 'totalInvoice', 'totalInvoiceVat', 'totalWithVat');
    }

    /**
     * Inserts invoice line items into the database.
     *
     * @param array $results An array of invoice line items.
     * @param int $invoiceId The ID of the invoice.
     * @param ProductsRepository $productRepository The instance of ProductsRepository.
     * @return void
     */
    public function insertInvoiceLines(array $results, int $invoiceId, ProductsRepository $productRepository)
    {
        $query = $this->pdo->prepare("INSERT INTO {$this->invoiceLinesTable} 
        SET invoice_id = :invoiceId, product_id = :productId, quantity = :quantity");
        foreach ($results as $line) {
            $query->execute([
                'invoiceId' => htmlspecialchars(intval($invoiceId)),
                'productId' => htmlspecialchars($line['productId']),
                'quantity' => htmlspecialchars(intval($line['numberOfProducts']))
            ]);
            $productRepository->updateStock($line['productId'], $line['numberOfProducts']);
        }
    }
}
