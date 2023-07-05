<?php

namespace controllers;

use utils\Render;

class ProductsController extends Controller
{
    protected $modelName = \models\ProductsRepository::class;

    public function stock()
    {
        $this->session->isConnected();
        $pageTitle = "Stock";
        $products = $this->model->findAll();
        Render::render("stock", compact('pageTitle', 'products'));
    }

    public function formProduct(int $productId)
    {
        $this->session->isAdmin();
        $pageTitle = "Edition des stock";
        $product = $this->model->find($productId);
        Render::render("formProducts", compact("pageTitle", "product"));
    }

    public function updateProduct(int $productId)
    {
        $this->session->isAdmin();
        $this->model->updateProduct($productId);
        $pageTitle = "Stock";
        $products = $this->model->findAll();
        header("Location: /cyclotron/Products/stock");
    }
}
