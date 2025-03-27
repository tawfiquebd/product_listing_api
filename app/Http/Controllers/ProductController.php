<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $products = Product::query()
                ->with('category')
                ->latest()
                ->paginate();

            return response()->json([
                'data' => $products,
                'message' => 'Products loaded successfully!',
            ], ResponseAlias::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => "Something went wrong {$e->getMessage()}",
                'line' => $e->getLine(),
                'error' => 'Failed to load products'
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(ProductRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $product = Product::query()
                ->create($request->validated());
            DB::commit();

            return response()->json([
                'data' => $product->load('category'),
                'message' => 'Data stored successfully!'
            ], ResponseAlias::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Something went wrong {$e->getMessage()}",
                'line' => $e->getLine(),
                'error' => 'Failed to create product!'
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Product $product): JsonResponse
    {
        try {
            return response()->json([
                'data' => $product->load('category'),
                'message' => 'Product fetched successfully!'
            ], ResponseAlias::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Product not found!'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
    }

    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        DB::beginTransaction();
        try {
            $product->update($request->validated());
            DB::commit();

            return response()->json([
                'data' => $product->load('category'),
                'message' => 'Product updated successfully!'
            ], ResponseAlias::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to update product!'
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Product $product): JsonResponse
    {
        DB::beginTransaction();
        try {
            $product->delete();
            DB::commit();

            return response()->json([
                'message' => 'Product deleted successfully'
            ], ResponseAlias::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to delete product!'
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
