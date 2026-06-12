<?php

namespace App\Http\Controllers;

use App\Models\RaporTemplate;
use App\Models\RaporTemplateItem;
use Illuminate\Http\Request;

class RaporTemplateController extends Controller
{
    // Menampilkan daftar template
    public function index()
    {
        $templates = RaporTemplate::all();
        return view('admin.template.index', compact('templates'));
    }

    // Menyimpan template baru
    public function store(Request $request)
    {
        RaporTemplate::create(['nama_template' => $request->nama_template]);
        return back()->with('success', 'Template berhasil dibuat!');
    }

    // Mengaktifkan salah satu template
    public function activate($id)
    {
        RaporTemplate::query()->update(['is_active' => false]);
        RaporTemplate::where('id', $id)->update(['is_active' => true]);
        return back()->with('success', 'Template diaktifkan!');
    }

    // Menampilkan detail/isi indikator dari suatu template
    public function show($id)
    {
        $template = RaporTemplate::with('items')->findOrFail($id);
        return view('admin.template.show', compact('template'));
    }

    // SIMPAN OPSI MULOK GLOBAL
    public function updateOpsiMulok(Request $request, $id)
    {
        $template = RaporTemplate::findOrFail($id);
        $opsi = $request->opsi_mulok ? array_map('trim', explode(',', $request->opsi_mulok)) : null;
        $template->update(['opsi_mulok' => $opsi]);
        return back()->with('success', 'Opsi Pilihan Mulok berhasil diperbarui!');
    }

    // SIMPAN INDIKATOR / BAB / SUB-JUDUL
    public function storeItem(Request $request, $id)
    {
        $lastUrutan = \App\Models\RaporTemplateItem::where('template_id', $id)->max('urutan') ?? 0;
        
        $is_bab = $request->jenis_input == 'bab';
        
        if (!$is_bab && empty($request->parent_id)) {
            return back()->with('error', 'Gagal! Silakan buat "BAB Utama" terlebih dahulu.');
        }
        
        $dataSimpan = [
            'template_id' => $id,
            'is_bab' => $is_bab,
            'label' => $request->label,
            'urutan' => $lastUrutan + 1
        ];

        // LOGIKA PENENTUAN TIPE & PARENT
        if (!$is_bab) {
            $dataSimpan['parent_id'] = $request->parent_id;
            
            if ($request->jenis_input == 'isian_narasi') {
                $dataSimpan['tipe'] = 'textarea';
            } elseif ($request->jenis_input == 'isian_mulok') {
                $dataSimpan['tipe'] = 'radio';
            } elseif ($request->jenis_input == 'sub_judul') {
                $dataSimpan['tipe'] = 'sub_judul';
            }
        }
        
        \App\Models\RaporTemplateItem::create($dataSimpan);
        
        // PENTING: withInput() akan mengembalikan pilihan form terakhir!
        return back()->with('success', 'Berhasil ditambahkan!')->withInput($request->only(['jenis_input', 'parent_id']));
    }

    // Menghapus indikator dari template
    public function destroyItem($id)
    {
        RaporTemplateItem::destroy($id);
        return back()->with('success', 'Indikator berhasil dihapus!');
    }
}