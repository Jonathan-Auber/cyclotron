<?php

namespace models;

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
            $this->session->setFlashMessage("Création de la fiche effectué !");
        } else {
            $this->session->setFlashMessage("Une erreur s'est produite, la demande n'a pas aboutie !");
        }
    }

    /**
     * Updates an existing customer in the database.
     *
     * @param int $customerId The ID of the customer to update.
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
            $this->session->setFlashMessage("Mise à jour effectuée !");
        } else {
            $this->session->setFlashMessage("Une erreur s'est produite, la demande n'a pas aboutie !");
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
