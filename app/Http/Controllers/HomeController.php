<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donor;
use Illuminate\Http\Request;

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
        return view('app.view', compact('campaign', 'latest', 'donors'));
    }

}
