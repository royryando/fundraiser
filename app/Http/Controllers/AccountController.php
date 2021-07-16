<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{

    public function dashboard() {
        return view('app.account.dashboard');
    }

    public function createFundraiser() {
        return view('app.account.create_fundraiser');
    }

    public function myCampaigns() {
        $campaigns = Campaign::where('user_id', Auth::user()['id'])
            ->orderByRaw('FIELD(status, "ACTIVE", "INACTIVE")')
            ->orderBy('views')
            ->get();
        return view('app.account.my_campaigns', compact('campaigns'));
    }

    public function myDonations() {
        return view('app.account.my_donations');
    }

}
