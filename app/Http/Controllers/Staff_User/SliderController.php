<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Slider;

class SliderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'slider_name' => 'required|string|max:255',
            'description'   => 'required|string',
            'slider_type'   => 'required|string',
            'documents'     => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ], [
            'slider_name.required' => 'Nama slider wajib diisi.',
            'slider_name.string'   => 'Nama slider harus berupa teks.',
            'slider_name.max'      => 'Nama slider maksimal 255 karakter.',

            'description.required'   => 'Deskripsi slider wajib diisi.',
            'description.string'     => 'Deskripsi harus berupa teks.',
            
            'slider_type.required'   => 'Jenis slider wajib dipilih.',
            'slider_type.string'     => 'Jenis slider tidak valid.',
            
            'documents.required'     => 'Dokumen pendukung wajib diunggah.',
            'documents.file'         => 'File dokumen tidak valid.',
            'documents.mimes'        => 'Dokumen harus berupa file dengan format: JPG/PNG',
            'documents.max'          => 'Ukuran dokumen maksimal 2MB.',

        ]);

        // Simpan file
        $file = $request->file('documents');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents', $filename, 'public');

        // Simpan data license
        $ad = Ad::create([
            'ad_name' => $request->ad_name,
            'ad_type'   => $request->ad_type,
            'description'   => $request->description,
            'documents'     => $filePath,
        ]);

        // Simpan ke pivot ad_user
        $ad->users()->attach(auth()->id(), [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('slider.index')->with('success', 'Pengajuan slider berhasil dikirim!');
    }

    public function create()
    {
        return view('user_staff.slider.create');
    }

    public function destroy($id)
    {
        $ad = Ad::findOrFail($id);

        // Hapus file dokumen jika ada
        $documentPath = public_path('uploads/' . $ad->documents);
        if (file_exists($documentPath)) {
            unlink($documentPath);
        }

        // Hapus relasi user jika menggunakan pivot
        $ad->users()->detach();

        // Hapus ad
        $ad->delete();

        return redirect()->route('slider.index')->with('success', 'Pengajuan berhasil dihapus.');    }


    public function index()
    {
        $sliders = Slider::latest()->get();
        return view('user_staff.slider.index', compact('sliders'));     
    }

    public function show($id)
    {
        $ad = Ad::with('users')->findOrFail($id);
        return view('user_staff.slider.show', compact('ad'));
    }

    public function approve($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->submission_status = 'disetujui';
        $ad->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->submission_status = 'ditolak';
        $ad->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}
