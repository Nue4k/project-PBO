<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
// Hapus impor Product Model, kita gunakan dari Service
use App\Services\ProductServiceInterface; // Impor Interface
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException; // Impor exception jika perlu penanganan khusus

class ProductController extends Controller
{
    protected ProductServiceInterface $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $products = $this->productService->getAllProducts();
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $product = $this->productService->createProduct($request->all());
            return response()->json($product, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika validasi gagal, kembalikan error
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($id);
            return response()->json($product);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $product = $this->productService->updateProduct($id, $request->all());
            return response()->json($product);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika validasi gagal, kembalikan error
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $deleted = $this->productService->deleteProduct($id);
            if ($deleted) {
                return response()->json(null, 204);
            } else {
                // Ini seharusnya tidak terjadi jika getProductById berhasil, kecuali ada race condition
                return response()->json(['message' => 'Product not found'], 404);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }
}
