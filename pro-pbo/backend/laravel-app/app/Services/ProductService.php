<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator; // Kita bisa meletakkan validasi di sini atau tetap di controller/form request

class ProductService implements ProductServiceInterface
{
    /**
     * Get all products.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllProducts(): Collection
    {
        // Logika tambahan bisa ditambahkan di sini
        // Misalnya, filter, sort, pagination sebelum mengambil dari database
        return Product::all();
    }

    /**
     * Find a product by its ID.
     *
     * @param int $id
     * @return \App\Models\Product
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getProductById(int $id): Product
    {
        // Logika tambahan bisa ditambahkan di sini
        // Misalnya, cek visibilitas, izin akses, dll.
        return Product::findOrFail($id);
    }

    /**
     * Create a new product.
     *
     * @param array $data
     * @return \App\Models\Product
     */
    public function createProduct(array $data): Product
    {
        // Validasi data bisa dilakukan di sini atau di tempat lain (misal FormRequest)
        $validatedData = $this->validateProductData($data, 'create');

        return Product::create($validatedData);
    }

    /**
     * Update an existing product.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Product
     */
    public function updateProduct(int $id, array $data): Product
    {
        $product = $this->getProductById($id); // Ambil produk, akan throw exception jika tidak ditemukan

        // Validasi data bisa dilakukan di sini atau di tempat lain
        $validatedData = $this->validateProductData($data, 'update');

        $product->update($validatedData);

        return $product->fresh(); // Kembalikan instance produk yang diperbarui dari database
    }

    /**
     * Delete a product by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteProduct(int $id): bool
    {
        $product = $this->getProductById($id); // Ambil produk, akan throw exception jika tidak ditemukan

        // Logika tambahan bisa ditambahkan sebelum menghapus
        // Misalnya, cek apakah produk bisa dihapus, soft delete, log, dll.
        return $product->delete();
    }

    /**
     * Validate product data based on action (create/update).
     *
     * @param array $data
     * @param string $action
     * @return array
     */
    private function validateProductData(array $data, string $action): array
    {
        $rules = match($action) {
            'create' => [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ],
            'update' => [
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
            ],
            default => [] // Atau lempar exception jika action tidak dikenal
        };

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            // Jika validasi gagal, Laravel biasanya mengembalikan RedirectResponse dengan error.
            // Di service, kita bisa melempar ValidationException atau mengembalikan error.
            // Untuk kesederhanaan dalam konteks ini, kita gunakan fail().catch() di controller nanti,
            // atau biarkan melempar exception dan tangani di level Exception Handler jika perlu secara global.
            $validator->validate(); // Ini akan melempar ValidationException jika gagal
        }

        return $validator->validated();
    }
}