<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting; // Assuming a Setting model exists
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // To clear cache after updating settings

class SettingsController extends Controller
{
    /**
     * Display the settings form.
     * Assumes settings are stored as key-value pairs in a 'settings' table.
     */
    public function index()
    {
        // Fetch all settings or specific ones needed for the form
        $settings = Setting::pluck('value', 'key'); // Get as ['key' => 'value'] array

        // Or fetch specific settings:
        // $siteName = Setting::where('key', 'site_name')->value('value');
        // $theme = Setting::where('key', 'theme')->value('value');

        return view('admin.settings.index', compact('settings'));
        // Pass individual variables if fetching separately:
        // return view('admin.settings.index', compact('siteName', 'theme'));
    }

    /**
     * Update the specified settings in storage.
     */
    public function update(Request $request)
    {
        // Validate the incoming settings data
        $validatedData = $request->validate([
            'site_name' => 'nullable|string|max:100',
            'theme' => 'nullable|string|in:light,dark', // Example validation
            'registration_open' => 'nullable|boolean',
            // Add validation for other settings keys...
        ]);

        // Loop through validated data and update/create settings
        foreach ($validatedData as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? ''] // Store empty string if value is null/missing maybe?
            );
        }

        // Clear relevant cache if settings are cached
        Cache::forget('app_settings'); // Example cache key

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}