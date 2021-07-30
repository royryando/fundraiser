<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Royryando\Duitku\Facades\Duitku;

class HomeController extends Controller
{

    public function home() {
        $tops = Campaign::where('status', 'ACTIVE')
            ->orderBy('views', 'DESC')
            ->limit(3)
            ->get();
        return view('app.home', compact('tops'));
    }

    public function browse() {
        $campaigns = Campaign::where('status', 'ACTIVE')
            ->limit(30)
            ->orderBy('views', 'DESC')
            ->get();
        return view('app.browse', compact('campaigns'));
    }

    public function view($code) {
        $campaign = Campaign::where('code', $code)
            ->first();
        if (!$campaign) {
            abort(404);
        }
        $latest = Donor::where('campaign_id', $campaign->id)
            ->whereNotNull('paid_at')
            ->orderBy('paid_at', 'DESC')
            ->limit(5)
            ->get();
        $donors = Donor::where('campaign_id', $campaign->id)
            ->whereNotNull('paid_at')
            ->count();
        $payment_methods = Duitku::paymentMethods(10000);
        return view('app.view', compact('campaign', 'latest', 'donors', 'payment_methods'));
    }

    public function donate($code, Request $request) {
        $campaign = Campaign::where('code', $code)
            ->first();
        if (!$campaign) {
            abort(404);
        }
        if (!Auth::check()) {
            abort(401);;
        }

        // requests
        $isAnonymous = $request->input('send-anonymous') == '1';
        $amount = (int)str_replace('.', '', $request->input('amount'));
        $paymentMethod = $request->input('payment_method');

        if ($amount < 10000) {
            // user miss the validation
            return redirect()
                ->back();
        }

        if (!$paymentMethod) {
            return redirect()
                ->back();
        }

        $user = Auth::user();
        $user = User::find($user['id']);
        if (!$user) {
            return redirect()
                ->back();
        }

        $donor = new Donor();
        $donor->user()->associate($user);
        $donor->campaign()->associate($campaign);
        $donor->anonymous = $isAnonymous;
        $donor->amount = $amount;
        $donor->uuid = Str::uuid()->toString();
        $donor->expired_at = Carbon::now()->addDay();
        $donor->payment_method = $paymentMethod;

        $invoice = Duitku::createInvoice($donor->uuid, $donor->amount, $donor->payment_method,
            $campaign->title, $user->name, $user->email, 24 * 60);
        if ($invoice['success']) {
            $donor->ref_id = $invoice['reference'];
            $donor->payment_url = $invoice['payment_url'];
            $donor->payment_json = json_encode($invoice);
            $donor->save();
            return redirect()->away($donor->payment_url);
        } else {
            return redirect()
                ->back();
        }
    }

}
