<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function editLandingPage()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('settings.landing', compact('settings'));
    }

    public function updateLandingPage(Request $request)
    {
        $data = $request->except('_token', '_method');

        $imageFields = ['yayasan_image', 'tk_image', 'paud_image', 'site_logo', 'hero_image'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $path = $file->store('landing', 'public');
                $data[$field] = '/storage/' . $path;
            } else {
                unset($data[$field]);
            }
        }

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->back()->with('success', 'Konten landing page berhasil diperbarui!');
    }
}
