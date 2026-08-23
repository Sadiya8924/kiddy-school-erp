<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use App\Models\Yayasan;

class SettingController extends Controller
{
    // ── OWNER ONLY: Edit landing page utama (global) ──────────────────
    public function editLandingPage()
    {
        abort_unless(auth()->user()->isOwner(), 403, 'Hanya Owner yang dapat mengedit landing page utama.');
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('settings.landing', compact('settings'));
    }

    public function updateLandingPage(Request $request)
    {
        abort_unless(auth()->user()->isOwner(), 403, 'Hanya Owner yang dapat mengedit landing page utama.');

        $imageFields = ['yayasan_image', 'tk_image', 'paud_image', 'site_logo', 'hero_image'];

        // 1. Save all text/textarea inputs — exclude file fields, CSRF, and delete flags
        $textData = $request->except(array_merge(['_token', '_method', 'delete_hero_image'], $imageFields));
        foreach ($textData as $key => $value) {
            Setting::set($key, $value);
        }

        // 2. Handle hero_image delete request (revert to GSAP illustration)
        if ($request->input('delete_hero_image') == '1') {
            $oldPath = Setting::get('hero_image');
            if ($oldPath && Storage::disk('public')->exists(str_replace('/storage/', '', $oldPath))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $oldPath));
            }
            Setting::set('hero_image', null);
        }

        // 3. Handle each image field separately with old-file cleanup
        foreach ($imageFields as $field) {
            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                // Delete old file if exists
                $oldPath = Setting::get($field);
                if ($oldPath && Storage::disk('public')->exists(str_replace('/storage/', '', $oldPath))) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $oldPath));
                }
                // Store new file
                $path = $request->file($field)->store('landing', 'public');
                Setting::set($field, '/storage/' . $path);
            }
            // If no new file uploaded, keep existing value (don't touch it)
        }

        return redirect()->back()->with('success', 'Konten landing page utama berhasil diperbarui!');
    }


    // ── SUPER ADMIN: Edit landing page yayasan sendiri ────────────────
    public function editYayasanLanding()
    {
        $user = auth()->user();
        abort_unless($user->isPureSuperAdmin() || $user->isOwner(), 403, 'Akses ditolak.');

        $yayasanId = session('active_yayasan_id') ?: $user->yayasan_id;
        $yayasan = Yayasan::find($yayasanId) ?: Yayasan::firstOrFail();
        $content = $yayasan->landing_content ?? [];

        return view('superadmin.landing', compact('yayasan', 'content'));
    }

    public function updateYayasanLanding(Request $request)
    {
        $user = auth()->user();
        abort_unless($user->isPureSuperAdmin() || $user->isOwner(), 403, 'Akses ditolak.');

        $yayasanId = session('active_yayasan_id') ?: $user->yayasan_id;
        $yayasan = Yayasan::find($yayasanId) ?: Yayasan::firstOrFail();
        $content = $yayasan->landing_content ?? [];

        // Sync main yayasan name column if site_title is updated
        if ($request->filled('site_title')) {
            $yayasan->nama_yayasan = $request->site_title;
        }

        // Save all text inputs
        $fileKeys = ['yayasan_image', 'tk_image', 'paud_image', 'site_logo', 'hero_image', 'galeri_1_image', 'galeri_2_image', 'galeri_3_image', 'galeri_4_image', 'galeri_5_image', 'galeri_6_image'];
        $inputs = $request->except(array_merge(['_token', '_method'], $fileKeys));
        foreach ($inputs as $key => $val) {
            $content[$key] = $val;
        }

        // Save image uploads
        foreach ($fileKeys as $field) {
            if ($request->hasFile($field)) {
                if (!empty($content[$field])) {
                    $old = str_replace('/storage/', '', $content[$field]);
                    Storage::disk('public')->delete($old);
                }
                $path = $request->file($field)->store('landing/yayasan', 'public');
                $content[$field] = '/storage/' . $path;
            }
        }

        $yayasan->landing_content = $content;
        $yayasan->save();

        return redirect()->back()->with('success', 'Konten landing page yayasan berhasil diperbarui!');
    }
}
