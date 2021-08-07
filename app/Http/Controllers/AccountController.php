<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AccountController extends Controller
{

    public function dashboard() {
        return view('app.account.dashboard');
    }

    public function myCampaigns() {
        $campaigns = Campaign::where('user_id', Auth::user()['id'])
            ->orderByRaw('FIELD(status, "ACTIVE", "INACTIVE")')
            ->orderBy('views')
            ->get();
        return view('app.account.my_campaigns', compact('campaigns'));
    }

    public function myDonations() {
        $donations = Donor::where('user_id', Auth::user()['id'])
            ->where('paid', true)
            ->orderBy('paid_at', 'DESC')
            ->whereNotNull('campaign_id')
            ->whereYear('paid_at', date('Y'))
            ->get();
        return view('app.account.my_donations', compact('donations'));
    }

    public function createCampaign() {
        return view('app.account.create_campaign');
    }

    public function postCreateCampaign(Request $request) {
        $title = $request->input('title');
        $thumbnail = $request->file('thumbnail');
        $location = $request->input('location');
        $target = $request->input('target');
        $target_date = $request->input('target_date');
        $description = $request->input('description');

        $url = preg_replace('~[^\\pL0-9_]+~u', '-', $title);
        $url = trim($url, "-");
        $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
        $url = strtolower($url);
        $url = preg_replace('~[^-a-z0-9_]+~', '', $url);

        if (!$title || !$thumbnail || !$location || !$target || !$target_date || !$description) {
            return redirect()
                ->back()
                ->with([
                    'msg_type' => 'warning',
                    'msg' => 'Please fill all the blank'
                ]);
        }

        if (!in_array(strtolower($thumbnail->clientExtension()), ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp'])) {
            return redirect()
                ->back()
                ->with([
                    'msg_type' => 'warning',
                    'msg' => 'Only png, jpg, jpeg, gif, bmp, webp format supported for the thumbnail'
                ]);
        }

        $check = Campaign::where('code', $url)
            ->first();
        $finalUrl = $url;
        $i = 1;
        while($check != null) {
            $finalUrl = $url.'-'.$i;
            $check = Campaign::where('code', $finalUrl)
                ->first();
            $i++;
        }

        try {
            $img = Image::make($thumbnail->getRealPath());
            $img->resize(1500, 1000, function ($constraint) {
                //$constraint->aspectRatio();
            });

            $img->stream();

            $fileLocation = $finalUrl.'-'.time().'.'.$thumbnail->clientExtension();
            Storage::disk('local')->put('public/thumbnails/'.$fileLocation, $img, 'public');

            $campaign = new Campaign();
            $campaign->user()->associate(Auth::user());
            $campaign->code = $finalUrl;
            $campaign->title = $title;
            $campaign->status = 'ACTIVE';
            $campaign->description = $description;
            $campaign->target = (int)$target;
            $campaign->location = $location;
            $campaign->target_date = $target_date;
            $campaign->thumbnail = $fileLocation;
            $campaign->views = 0;
            $campaign->save();

            return redirect()
                ->back()
                ->with([
                    'msg_type' => 'success',
                    'msg' => 'Campaign successfully created'
                ]);
        } catch (\Exception $ex) {
            return redirect()
                ->back()
                ->with([
                    'msg_type' => 'error',
                    'msg' => $ex->getMessage()
                ]);
        }
    }

}
