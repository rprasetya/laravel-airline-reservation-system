<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanKeuanganController extends Controller
{
    public function index()
    {
        return view('keuangan.index'); // Ganti dengan view yang sesuai
    }
}
