<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Repositories\Setting\SettingRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    protected $repository;

    public function __construct(SettingRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setting = SiteSetting::first();
        return view('admin.settings.index', compact('setting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SiteSetting $siteSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SiteSetting $siteSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $setting = SiteSetting::findOrFail($id);

        // ✅ Validation rules
        $validated = $request->validate([
            'site_name'       => 'nullable|string|max:255',
            'contact_email'   => 'nullable|email|max:255',
            'contact_phone'   => 'nullable|string|max:50',
            'contact_address' => 'nullable|string|max:500',
            'working_hours'   => 'nullable|string|max:255',
            'currency'        => 'nullable|string|max:10',
            'facebook_link'   => 'nullable|url|max:255',
            'instagram_link'  => 'nullable|url|max:255',
            'site_logo'       => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
            'favicon'         => 'nullable|image|mimes:jpg,jpeg,png,ico|max:1024',
            'default_image'   => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
        ]);

        // ✅ Handle file uploads (loop for DRY code)
        // foreach (['site_logo', 'favicon', 'default_image'] as $field) {
        //     if ($request->hasFile($field)) {
        //         // Delete old file if exists
        //         if ($setting->$field && Storage::disk('public')->exists($setting->$field)) {
        //             Storage::disk('public')->delete($setting->$field);
        //         }

        //         // Store new file
        //         $path = $request->file($field)->store('uploads/settings', 'public');
        //         $validated[$field] = $path;
        //     }
        // }

        foreach (['site_logo', 'favicon', 'default_image'] as $field) {
            // Case 1: New file uploaded
            if ($request->hasFile($field)) {
                if ($setting->$field && Storage::disk('public')->exists($setting->$field)) {
                    Storage::disk('public')->delete($setting->$field);
                }
                $path = $request->file($field)->store('uploads/settings', 'public');
                $validated[$field] = $path;
            }
            // Case 2: Remove checkbox checked → delete + fallback
            elseif ($request->boolean('remove_' . $field)) {
                if ($setting->$field && Storage::disk('public')->exists($setting->$field)) {
                    Storage::disk('public')->delete($setting->$field);
                }
                $validated[$field] = 'storage/default.png'; // ✅ Fallback instead of null
            }
            // Case 3: Do nothing → keep old
        }


        // ✅ Update settings
        $setting->update($validated);

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SiteSetting $siteSetting)
    {
        //
    }
}
