<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{

    public function createFundraiser() {
        return view('app.account.create_fundraiser');
    }

}
