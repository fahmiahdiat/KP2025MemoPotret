<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::query()->latest();

        // 🔍 SEARCH
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 🟢 FILTER STATUS
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_active', false);
            }
        }

        // 📄 PAGINATION
        $packages = $query->paginate(9)->withQueryString();

        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:1',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string|max:255',
        ]);

        // ✅ Filter fasilitas kosong
        $features = $request->features
            ? array_values(array_filter($request->features))
            : null;

        // ✅ Upload thumbnail
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('packages', 'public');
        }

        Package::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'duration_hours' => $validated['duration_hours'],
            'description' => $validated['description'],
            'thumbnail' => $validated['thumbnail'] ?? null,
            'features' => $features,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
{
    $validated = $request->validate([
        'name'           => 'required|string|max:255',
        'price'          => 'required|numeric|min:0',
        'duration_hours' => 'required|integer|min:1',
        'description'    => 'required|string',
        'thumbnail'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'features'       => 'nullable|array',
        'features.*'     => 'nullable|string|max:255',
    ]);

    // ✅ Filter fasilitas kosong
    $features = $request->features
        ? array_values(array_filter($request->features))
        : null;

    // ✅ Jika upload thumbnail baru
    if ($request->hasFile('thumbnail')) {

        // 🔥 Hapus thumbnail lama (jika ada)
        if ($package->thumbnail && Storage::disk('public')->exists($package->thumbnail)) {
            Storage::disk('public')->delete($package->thumbnail);
        }

        // 🔥 Upload thumbnail baru
        $validated['thumbnail'] = $request->file('thumbnail')
            ->store('packages', 'public');
    }

    // ✅ Update data
    $package->update([
        'name'           => $validated['name'],
        'price'          => $validated['price'],
        'duration_hours' => $validated['duration_hours'],
        'description'    => $validated['description'],
        'thumbnail'      => $validated['thumbnail'] ?? $package->thumbnail,
        'features'       => $features,
        'is_active'      => $request->boolean('is_active'),
    ]);

    return redirect()
        ->route('admin.packages.index')
        ->with('success', 'Paket berhasil diperbarui.');
}


    public function destroy(Package $package)
    {
        // Check if package has bookings
        if ($package->bookings()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus paket yang sudah memiliki booking.');
        }

        $package->delete();
        return back()->with('success', 'Paket berhasil dihapus.');
    }
}