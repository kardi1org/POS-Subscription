<?php

namespace App\Http\Controllers;

use App\Models\Pricing;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\RenewalReminderMail;
use Carbon\Carbon;

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
            'email' => 'required',
            'codepaket' => 'required'
        ]);

        $pricing = new Pricing;
        $pricing->email = $request->email;
        $pricing->codepaket = $request->codepaket;
        $pricing->namapaket = $request->namapaket;
        $pricing->notes = $request->notes;
        $pricing->status = 'Pending';
        $pricing->save();

        //session()->flash('success', 'Pendaftaran Paket ' . $pricing->namapaket . ' Sukses');
        return redirect()->route('pricing.transfer', ['id' => $pricing->id])->with('success', 'Pendaftaran Paket ' . $pricing->namapaket . ' Sukses');
    }
}
