<?php

namespace controllers;

use models\CustomersRepository;
use models\InvoiceRepository;
use utils\Render;

class CustomersController extends Controller
{
    protected $modelName = CustomersRepository::class;
    protected $invoice;

    public function __construct()
    {
        parent::__construct();
        $this->invoice = new InvoiceRepository();
    }

    public function displayCustomers()
    {
        $this->session->isConnected();
        $customers = $this->model->findAll();
        $pageTitle = "Fichier client";
        Render::render("customers", compact("pageTitle", "customers"));
    }

    public function customerManagement(?int $id = null)
    {
        $this->session->isConnected();
        if ($id) {
            $customer = $this->model->find($id);
            $pageTitle = "Editer un client";
            Render::render("customerManagement", compact("pageTitle", "customer"));
        } else {
            $pageTitle = "Ajout client";
            Render::render("customerManagement", compact("pageTitle"));
        }
    }

    public function createOrUpadateCustomer(?int $customerId = null)
    {
        if ($customerId) {
            $this->model->updateCustomer($customerId);
            $pageTitle = "Fichier client";
        } else {
            $this->model->insertCustomer();
            $pageTitle = "Fichier client";
        }
        $customers = $this->model->findAll();
        header("Location:/cyclotron/Customers/displayCustomers");
    }

    public function invoiceByCustomer(int $customerId)
    {
        $this->session->isConnected();
        $customer = $this->model->find($customerId);
        $invoiceData = $this->invoice->invoiceData($customerId);
        $pageTitle = "Liste des factures client";
        Render::render("invoiceByCustomer", compact("pageTitle", "customer", "invoiceData"));
    }
}
