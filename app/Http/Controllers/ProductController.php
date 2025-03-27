<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * @OA\OpenApi(
 *     openapi="3.0.0",
 *     @OA\Info(
 *         title="Simple Product Listing API",
 *         version="1.0.0",
 *         description="This is an API for a small e-commerce startup",
 *         @OA\Contact(
 *             email="tawfiquegub@gmail.com"
 *         )
 *     ),
 *     @OA\Server(
 *         url="http://localhost:8000/api/v1",
 *         description="Local development server"
 *     )
 * )
 */

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/products",
     *     summary="Get all products",
     *     tags={"Products"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *     )
     * )
     */

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



/**
 * @OA\Post(
 *     path="/products",
 *     summary="Create a new product",
 *     tags={"Products Store"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "price", "category_id"},
 *             @OA\Property(property="name", type="string", example="New Product"),
 *             @OA\Property(property="price", type="number", format="float", example=49.99),
 *             @OA\Property(property="description", type="string", example="A great product"),
 *             @OA\Property(property="category_id", type="integer", example=2)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Product created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", ref="#/components/schemas/Product"),
 *             @OA\Property(property="message", type="string", example="Data stored successfully!")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Something went wrong"),
 *             @OA\Property(property="error", type="string", example="Failed to create product!")
 *         )
 *     )
 * )
 */

    /**
     * @OA\Schema(
     *     schema="Product",
     *     type="object",
     *     required={"name", "description", "price", "category_id"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Product Name"),
     *     @OA\Property(property="description", type="string", example="Product description here."),
     *     @OA\Property(property="price", type="number", format="float", example=99.99),
     *     @OA\Property(property="category_id", type="integer", example=1),
     *     @OA\Property(property="image_url", type="text", example="www.google.com"),
     * )
     */

    public function store(ProductRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $product = Product::query()
                ->create($request->validated());
            DB::commit();

            return response()->json([
                'data' => new ProductResource($product->load('category')),
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

    /**
     * @OA\Get(
     *     path="/products/{id}",
     *     summary="Get a product by ID",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/Product"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function show(Product $product): JsonResponse
    {
        try {
            return response()->json([
                'data' => new ProductResource($product->load('category')),
                'message' => 'Product fetched successfully!'
            ], ResponseAlias::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Product not found!'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
    }


    /**
     * @OA\Put(
     *     path="/products/{id}",
     *     summary="Update a product by ID",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated product",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/Product"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */



    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        DB::beginTransaction();
        try {
            $product->update($request->validated());
            DB::commit();

            return response()->json([
                'data' => new ProductResource($product->load('category')),
                'message' => 'Product updated successfully!'
            ], ResponseAlias::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to update product!'
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/products/{id}",
     *     summary="Delete a product by ID",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
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
