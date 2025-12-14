<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        // Get admins and recent clients
        $admins = User::where('role', 'admin')->latest()->get();
        $clients = User::where('role', 'client')
            ->withCount('bookings')
            ->withSum('bookings', 'total_amount')
            ->latest()
            ->take(50)
            ->get();
        
        return view('owner.users.index', compact('admins', 'clients'));
    }

    /**
     * Show form to create new admin.
     */
    public function createAdmin()
    {
        return view('owner.users.create-admin');
    }

    /**
     * Store a newly created admin.
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return redirect()->route('owner.users.index')
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user)
    {
        // Only allow toggling for admin users
        if ($user->role === 'admin') {
            $user->update([
                'is_active' => !$user->is_active
            ]);
            
            $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return back()->with('success', "Admin {$user->name} berhasil $status.");
        }

        return back()->with('error', 'Aksi tidak valid.');
    }

    /**
     * Show client details.
     */
    public function showClient(User $user)
    {
        // Ensure user is client
        if ($user->role !== 'client') {
            return redirect()->route('owner.users.index');
        }

        $bookings = $user->bookings()
            ->with('package')
            ->latest()
            ->paginate(10);

        return view('owner.users.show-client', compact('user', 'bookings'));
    }
}