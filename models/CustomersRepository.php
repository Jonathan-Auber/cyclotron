<?php

namespace models;

use Exception;

class CustomersRepository extends Model
{
    protected string $table = "customers";

    public function insertCustomer()
    {
        if (!empty($_POST)) {
            $data = [];
            foreach ($_POST as $customer) {
                if (isset($customer)) {
                    $data[] = htmlspecialchars(trim($customer));
                }
            }

            $query = $this->pdo->prepare("INSERT INTO {$this->table} SET firstname = :firstName, lastname = :lastName, address = :address, additional_address = :additionalAddress, zip_code = :zipCode, city = :city, email = :email, phone_number = :phoneNumber");
            $query->execute([
                'lastName' => $data[0],
                'firstName' => $data[1],
                'address' => $data[2],
                'additionalAddress' => $data[3],
                'city' => $data[4],
                'zipCode' => $data[5],
                'email' => $data[6],
                'phoneNumber' => $data[7]
            ]);
        } else {
            throw new Exception("400 : Le remplissage des champs comporte une erreur !");
        }
    }

    /**
     * Updates an existing customer in the database.
     *
     * @param int $customerId The ID of the customer to update.
     * @throws Exception if there is an error in filling the fields.
     */
    public function updateCustomer(int $customerId)
    {
        if (!empty($_POST)) {
            $data = [];
            foreach ($_POST as $customer) {
                if (isset($customer)) {
                    $data[] = htmlspecialchars(trim($customer));
                }
            }

            $query = $this->pdo->prepare("UPDATE customers SET firstname = :firstName, lastname = :lastName, address = :address, additional_address = :additionalAddress, zip_code = :zipCode, city = :city, email = :email, phone_number = :phoneNumber WHERE id = :id");
            $query->execute([
                'id' => $customerId,
                'lastName' => $data[0],
                'firstName' => $data[1],
                'address' => $data[2],
                'additionalAddress' => $data[3],
                'city' => $data[4],
                'zipCode' => $data[5],
                'email' => $data[6],
                'phoneNumber' => $data[7]
            ]);
        } else {
            throw new Exception("400 : Le remplissage des champs comporte une erreur !");
        }
    }

    /**
     * Retrieves customer data for the specified invoice ID.
     *
     * @param int $invoiceId The ID of the invoice associated with the customer.
     * @return array An array containing the customer data.
     */
    public function customerData(int $invoiceId)
    {
        $query = $this->pdo->prepare(" SELECT c.firstname, c.lastname, c.address, c.additional_address,c.zip_code, c.city,c.email, c.phone_number, i.creation_date 
        FROM customers c 
        JOIN invoices i ON i.customer_id = c.id 
        WHERE i.id = :invoiceId");
        $query->execute([
            'invoiceId' => $invoiceId
        ]);
        return $query->fetch();
    }
}
