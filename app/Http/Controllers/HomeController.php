<?php

namespace App\Http\Controllers;

use App\Models\Pricing;
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
        foreach ($pricings as $pricing) {
            if ($pricing->status === 'Aktif' && $pricing->end_date) {
                $daysRemaining = Carbon::now()->diffInDays($pricing->end_date, false);

                // Kirim hanya jika ≤ 3 hari sebelum expired dan belum pernah dikirim hari ini
                if ($daysRemaining <= 3 && $daysRemaining >= 0) {
                    $today = Carbon::today();

                    if (!$pricing->reminder_sent_at || $pricing->reminder_sent_at->lt($today)) {
                        Mail::to($pricing->email)->send(new RenewalReminderMail($pricing, $daysRemaining));

                        $pricing->reminder_sent_at = now(); // pakai save(), bukan update()
                        $pricing->save();
                    }
                }
            }
        }


        return view('home', compact('pricings'));
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
