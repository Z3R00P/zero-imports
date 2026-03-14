<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Product/Index', [
            'products' => Product::with('images')->where('stock', '>', 0)->get(),
        ]);
    }

    public function admin()
    {
        return Inertia::render('Dashboard', [
            'products' => Product::with('images')->latest()->get(),
        ]);
    }

    public function show(Product $product)
    {
        $product->load('images');

        return Inertia::render('Product/Show', [
            'product' => $product,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'images'      => 'nullable|array',
            'images.*' => 'file|mimetypes:image/jpeg,image/png,image/webp,image/avif|max:2048',
        ];

        $messages = [
            'images.array'       => 'O campo de imagens precisa ser um array.',
            'images.*.file'      => 'Cada item enviado em imagens precisa ser um arquivo.',
            'images.*.mimetypes' => 'Cada arquivo precisa ser uma imagem (jpeg, png, webp ou avif).',
            'images.*.max'       => 'Cada imagem não pode ser maior que 2MB.',
        ];

        $data = $request->validate($rules, $messages);

        $product = Product::create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'price'       => $data['price'],
            'stock'       => $data['stock'],
        ]);

        if ($request->hasFile('images')) {
            $manager = new ImageManager(new Driver());

            foreach ($request->file('images') as $photo) {
                $image = $manager->read($photo->getRealPath())->toPng();

                $filename = 'products/' . uniqid() . '.png';
                Storage::disk('public')->put($filename, $image);

                $product->images()->create(['path' => $filename]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Produto criado com sucesso!');
    }

    public function update(Request $request, Product $product)
    {
        $rules = [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'images'      => 'nullable|array',
            'images.*' => 'file|mimetypes:image/jpeg,image/png,image/webp,image/avif|max:2048',
        ];

        $messages = [
            'images.array'       => 'O campo de imagens precisa ser um array.',
            'images.*.file'      => 'Cada item enviado em imagens precisa ser um arquivo.',
            'images.*.mimetypes' => 'Cada arquivo precisa ser uma imagem (jpeg, png, webp ou avif).',
            'images.*.max'       => 'Cada imagem não pode ser maior que 2MB.',
        ];

        $data = $request->validate($rules, $messages);

        $product->update([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'price'       => $data['price'],
            'stock'       => $data['stock'],
        ]);

        if ($request->hasFile('images')) {
            $manager = new ImageManager(new Driver());

            foreach ($request->file('images') as $photo) {
                $image = $manager->read($photo->getRealPath())->toPng();

                $filename = 'products/' . uniqid() . '.png';
                Storage::disk('public')->put($filename, $image);

                $product->images()->create(['path' => $filename]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Product $product)
    {
        $product->load('images');

        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->path);
        }

        $product->delete();

        return redirect()->route('dashboard')->with('success', 'Produto excluído com sucesso!');
    }
}
