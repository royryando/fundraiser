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

        if ((int)$target < 1000000) {
            return redirect()
                ->back()
                ->with([
                    'msg_type' => 'warning',
                    'msg' => 'The minimum target of donation is Rp1.000.000'
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
            $arr = explode("\n", $description);
            foreach ($arr as $key => $value) {
                $arr[$key] = "\n" . $arr[$key];
            }
            $x = implode("\n", $arr);

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
            $campaign->description = $x;
            $campaign->target = (int)$target;
            $campaign->location = $location;
            $campaign->target_date = $target_date;
            $campaign->thumbnail = $fileLocation;
            $campaign->views = 0;
            $campaign->save();

            return redirect()
                ->route('account.my-campaigns')
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

    public function deleteCampaign(Request $request) {
        $id = $request->input('id');

        $campaign = Campaign::find($id);
        if (!$campaign) {
            return response()
                ->json([
                    'success' => false,
                    'message' => 'Campaign not found'
                ]);
        }

        $donors = Donor::where('campaign_id', $campaign->id)
            ->count();
        if ($campaign->collected > 0 || $donors > 0) {
            return response()
                ->json([
                    'success' => false,
                    'message' => 'Campaign have funds inside or already waiting for payment transfer, deletion canceled'
                ]);
        }

        $campaign->delete();
        return response()
            ->json([
                'success' => true,
                'message' => 'Campaign successfully deleted'
            ]);
    }

    public function editCampaign($id) {
        $campaign = Campaign::find($id);
        if (!$campaign) {
            return redirect()
                ->route('account.my-campaigns')
                ->with([
                    'msg_type' => 'warning',
                    'msg' => 'Campaign not found'
                ]);
        }
        $arr = explode("\n", $campaign->description);
        foreach ($arr as $key => $value) {
            $arr[$key] = str_replace("\n\n", "\n", $arr[$key]);
        }
        $campaign->description = implode("", $arr);
        return view('app.account.edit_campaign', compact('campaign'));
    }

    public function postEditCampaign($id, Request $request) {
        $campaign = Campaign::find($id);
        if (!$campaign) {
            return redirect()
                ->route('account.my-campaigns')
                ->with([
                    'msg_type' => 'warning',
                    'msg' => 'Campaign not found'
                ]);
        }

        $title = $request->input('title');
        $thumbnail = $request->file('thumbnail');
        $location = $request->input('location');
        $target = $request->input('target');
        $target_date = $request->input('target_date');
        $description = $request->input('description');

        if (!$title || !$location || !$target || !$target_date || !$description) {
            return redirect()
                ->back()
                ->with([
                    'msg_type' => 'warning',
                    'msg' => 'Please fill all the blank'
                ]);
        }

        if ((int)$target < 1000000) {
            return redirect()
                ->back()
                ->with([
                    'msg_type' => 'warning',
                    'msg' => 'The minimum target of donation is Rp1.000.000'
                ]);
        }

        if (!empty($thumbnail) && !in_array(strtolower($thumbnail->clientExtension()), ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp'])) {
            return redirect()
                ->back()
                ->with([
                    'msg_type' => 'warning',
                    'msg' => 'Only png, jpg, jpeg, gif, bmp, webp format supported for the thumbnail'
                ]);
        }

        try {
            $arr = explode("\n", $description);
            foreach ($arr as $key => $value) {
                $arr[$key] = "\n" . $arr[$key];
            }
            $x = implode("\n", $arr);

            if (!empty($thumbnail)) {
                $img = Image::make($thumbnail->getRealPath());
                $img->resize(1500, 1000, function ($constraint) {
                    //$constraint->aspectRatio();
                });

                $img->stream();

                $fileLocation = $campaign->code.'-'.time().'.'.$thumbnail->clientExtension();
                Storage::disk('local')->put('public/thumbnails/'.$fileLocation, $img, 'public');

                $campaign->thumbnail = $fileLocation;
            }

            $campaign->title = $title;
            $campaign->description = $x;
            $campaign->target = (int)$target;
            $campaign->location = $location;
            $campaign->target_date = $target_date;
            $campaign->save();

            return redirect()
                ->route('account.my-campaigns')
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
