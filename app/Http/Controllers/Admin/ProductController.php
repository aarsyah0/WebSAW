<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Criteria;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('criterias')
            ->when($request->search, fn ($q, $v) => $q->where('name', 'like', "%{$v}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $criterias = Criteria::orderBy('weight_order')->get();
        return view('admin.products.create', compact('criterias'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $criteriaValues = [
            'Kualitas' => $data['kualitas'] ?? 0,
            'Keamanan' => $data['keamanan'] ?? 0,
            'Edukasi' => $data['edukasi'] ?? 0,
            'Popularitas' => $data['popularitas'] ?? 0,
        ];
        unset($data['kualitas'], $data['keamanan'], $data['edukasi'], $data['popularitas']);

        $product = Product::create($data);

        $criterias = Criteria::all();
        $sync = [];
        foreach ($criterias as $c) {
            $val = $c->name === 'Harga' ? $product->price : ($criteriaValues[$c->name] ?? 0);
            $sync[$c->id] = ['value' => $val];
        }
        $product->criterias()->sync($sync);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $product->load('criterias');
        $criterias = Criteria::orderBy('weight_order')->get();
        return view('admin.products.edit', compact('product', 'criterias'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $criteriaValues = [
            'Kualitas' => $data['kualitas'] ?? 0,
            'Keamanan' => $data['keamanan'] ?? 0,
            'Edukasi' => $data['edukasi'] ?? 0,
            'Popularitas' => $data['popularitas'] ?? 0,
        ];
        unset($data['kualitas'], $data['keamanan'], $data['edukasi'], $data['popularitas']);

        $product->update($data);

        $criterias = Criteria::all();
        $sync = [];
        foreach ($criterias as $c) {
            $val = $c->name === 'Harga' ? $product->fresh()->price : ($criteriaValues[$c->name] ?? 0);
            $sync[$c->id] = ['value' => $val];
        }
        $product->criterias()->sync($sync);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diubah.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
