<?php

namespace models;

use Exception;

class ProductsRepository extends Model
{
    protected string $table = "products";

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Updates a product with the specified ID.
     *
     * @param int $productId The ID of the product to update.
     * @throws Exception if there is an error in filling the fields.
     * @return void
     */
    public function updateProduct(int $productId)
    {
        if (!empty($_POST)) {
            $data = [];
            foreach ($_POST as $product) {
                if (isset($product)) {
                    $data[] = htmlspecialchars(trim($product));
                }
            }

            $query = $this->pdo->prepare("UPDATE products 
            SET name = :name, reference = :reference, stock = :stock, stock_alert = :stock_alert, price_ht = :price_ht 
            WHERE id = :productId");
            $query->execute([
                'productId' => $productId,
                'name' => $data[0],
                'reference' => $data[1],
                'stock' => $data[2],
                'stock_alert' => $data[3],
                'price_ht' => $data[4]
            ]);
        } else {
            throw new Exception("400 : Le remplissage des champs comporte une erreur !");
        }
    }

    /** 
     * Retrieves the stock of a product based on its ID.
     *
     * @param int $productId The ID of the selected product.
     * @return mixed The stock value of the product.
     */
    public function getStock(int $productId)
    {
        $query = $this->pdo->prepare("SELECT stock 
        FROM products 
        WHERE id = :productId");
        $query->execute([
            "productId" => $productId
        ]);
        return $query->fetch();
    }

    /**
     * Updates the stock of a product.
     *
     * @param int $productId The ID of the product.
     * @param int $numberOfProduct The number of products to update the stock by.
     * @return void
     */
    public function updateStock(int $productId, int $numberOfProduct)
    {
        $stock = $this->getStock($productId);
        $newStock = $stock["stock"] - $numberOfProduct;
        $query = $this->pdo->prepare("UPDATE products
            SET stock = :newStock
            WHERE id = :productId");
        $query->execute([
            'productId' => $productId,
            'newStock' => $newStock
        ]);
    }
}
