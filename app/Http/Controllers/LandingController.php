<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil data paket
        $packages = Package::all();

        // Kirim ke view
        return view('landing', compact('packages'));
    }
}
