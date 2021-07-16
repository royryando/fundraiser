<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
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

}
