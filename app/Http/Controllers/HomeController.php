<?php

namespace App\Http\Controllers;

use App\Models\Pricing;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\RenewalReminderMail;
use Carbon\Carbon;
use App\Mail\InvoiceSignupMail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $pricings = Pricing::where('email', Auth::user()->email)->get();
        $packages = Package::all();

        return view('home', compact('pricings', 'packages'));
    }


    public function create($id)
    {
        if ($id == 1) {
            $namapaket = 'Basic';
        } elseif ($id == 2) {
            $namapaket = 'Pro';
        } else {
            $namapaket = 'Premium';
        }
        return view('pricing.create', compact('namapaket', 'id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'        => 'required|email',
            'codepaket'    => 'required|exists:packages,id',
            'harga_paket'  => 'required|numeric',
            'durasi'       => 'required|integer|min:1',
        ]);

        // Ambil paket
        $package = Package::findOrFail($request->codepaket);

        // Hitung total
        $totalPrice = $request->harga_paket * $request->durasi;
        $durationDays = $request->durasi * 30;

        // Simpan pricing
        $pricing = Pricing::create([
            'email'        => $request->email,
            'codepaket'    => $package->id,
            'namapaket'    => $package->name,
            'harga_paket'  => $request->harga_paket,
            'durasi'       => $request->durasi,
            'notes'        => $request->notes,
            'status'       => 'Pending',
        ]);

        // Kirim Email Invoice Signup
        Mail::to($pricing->email)->send(
            new InvoiceSignupMail($pricing, $package, $totalPrice, $durationDays)
        );

        return redirect()
            ->route('pricing.transfer', ['id' => $pricing->id])
            ->with('success', 'Pendaftaran Paket ' . $pricing->namapaket . ' berhasil. Invoice telah dikirim ke email.');
    }
}
