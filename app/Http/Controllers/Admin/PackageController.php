<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::latest()->get();
       
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:1',
            'description' => 'required|string',
            'features' => 'nullable|array'
        ]);

        Package::create([
            'name' => $request->name,
            'price' => $request->price,
            'duration_hours' => $request->duration_hours,
            'description' => $request->description,
            'features' => $request->features ? json_encode($request->features) : null,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:1',
            'description' => 'required|string',
            'features' => 'nullable|array'
        ]);

        $package->update([
            'name' => $request->name,
            'price' => $request->price,
            'duration_hours' => $request->duration_hours,
            'description' => $request->description,
            'features' => $request->features ? json_encode($request->features) : null,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.packages.index')
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