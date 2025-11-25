<?php

namespace App\Services;

use App\Models\Product;

interface ProductServiceInterface
{
    /**
     * Get all products.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllProducts();

    /**
     * Find a product by its ID.
     *
     * @param int $id
     * @return \App\Models\Product
     */
    public function getProductById(int $id): Product;

    /**
     * Create a new product.
     *
     * @param array $data
     * @return \App\Models\Product
     */
    public function createProduct(array $data): Product;

    /**
     * Update an existing product.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Product
     */
    public function updateProduct(int $id, array $data): Product;

    /**
     * Delete a product by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteProduct(int $id): bool;
}