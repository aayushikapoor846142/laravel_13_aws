<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::query()->paginate());
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        return new ProductResource(Product::create($this->validatedData($request)));
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    public function update(Request $request, Product $product): ProductResource
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:120'],
            'price' => ['sometimes', 'required', 'numeric', 'min:1'],
            'image' => ['sometimes', 'nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return new ProductResource($product);
    }

    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }

    private function validatedData(StoreProductRequest $request): array
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        return $validated;
    }
}