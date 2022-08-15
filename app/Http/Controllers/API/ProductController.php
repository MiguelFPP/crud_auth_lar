<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Termwind\Components\Dd;

class ProductController extends Controller
{
    /**
     * It returns a JSON response containing all the products in the database
     *
     * @return A JSON object with the data of all the products in the database.
     */
    public function getProducts(): JsonResponse
    {
        $products = Product::select('id', 'name', 'description', 'qty', 'price', 'image')->get();

        return response()->json([
            'data' => $products
        ], 200);
    }

    /**
     * > This function returns a JSON response with a message if the product is not found, or a JSON
     * response with the product data if it is found
     *
     * @param int id The id of the product to be retrieved
     *
     * @return JsonResponse A JSON response with the product data.
     */
    public function getProduct(int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }
        return response()->json([
            'data' => $product
        ], 200);
    }

    /**
     * It takes a request, validates the request, creates a product, and returns a response
     *
     * @param Request request The request object.
     *
     * @return JsonResponse A JSON response with the data of the product that was created.
     */
    public function createProduct(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'qty' => 'required|integer',
            'price' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);
        /* upload image */
        $image = $request->file('image');
        $imagePath = $image->store('images', 'public');

        $product = Product::create(
            [
                'name' => $data['name'],
                'description' => $data['description'],
                'qty' => $data['qty'],
                'price' => $data['price'],
                'image' => $imagePath
            ]
        );

        return response()->json([
            'data' => $product
        ], 201);
    }

    /**
     * It updates a product in the database
     *
     * @param Request request The request object.
     * @param int id The id of the product to be updated.
     *
     * @return JsonResponse A JSON response with the updated product.
     */
    public function updateProduct(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'qty' => 'required|integer',
            'price' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        /* change image */
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('images', 'public');
            $product->image = $imagePath;
        }

        $product->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'qty' => $data['qty'],
            'price' => $data['price'],
            'image' => $imagePath ?? $product->image
        ]);
        return response()->json([
            'data' => $product
        ], 200);
    }

    /**
     * It finds a product by its id, if it doesn't exist, it returns a 404 response, if it does exist,
     * it deletes it and returns a 200 response
     *
     * @param int id The id of the product to be deleted
     *
     * @return JsonResponse A JSON response with a message.
     */
    public function deleteProduct(int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }
        /* delete image */
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return response()->json([
            'message' => 'Product deleted'
        ], 200);
    }
}
