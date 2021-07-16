<?php

namespace App\Helpers;

use App\Models\Campaign;
use App\Models\Donor;
use Illuminate\Support\Facades\Auth;

class StaticData
{
    public static function myTotalDonation() {
        return Donor::where('user_id', Auth::user()['id'])
            ->where('paid', true)
            ->count() ?? 0;
    }

    public static function myTotalCampaign() {
        return Campaign::where('user_id', Auth::user()['id'])
            ->where('status', 'ACTIVE')
            ->count() ?? 0;
    }
}
