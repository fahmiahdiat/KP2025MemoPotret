<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'company_name' => config('app.name', 'Memo Potret'),
            'contact_email' => Cache::get('setting.contact_email', 'info@memopotret.com'),
            'contact_phone' => Cache::get('setting.contact_phone', '081234567890'),
            'dp_percentage' => Cache::get('setting.dp_percentage', 50),
            'cancellation_days' => Cache::get('setting.cancellation_days', 30),
            'instagram_url' => Cache::get('setting.instagram_url', 'https://instagram.com/memopotret'),
            'facebook_url' => Cache::get('setting.facebook_url', 'https://facebook.com/memopotret'),
        ];

        return view('owner.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'dp_percentage' => 'required|integer|min:10|max:100',
            'cancellation_days' => 'required|integer|min:1|max:90',
            'instagram_url' => 'nullable|url',
            'facebook_url' => 'nullable|url',
        ]);

        foreach ($validated as $key => $value) {
            Cache::forever("setting.{$key}", $value);
        }

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}