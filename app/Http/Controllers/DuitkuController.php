<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Royryando\Duitku\Http\Controllers\DuitkuBaseController;

class DuitkuController extends DuitkuBaseController
{

    public function returnCallback(Request $request) {
        $orderId = $request->input('merchantOrderId');
        $donor = Donor::where('uuid', $orderId)
            ->first();
        if ($donor) {
            return redirect()
                ->route('index.view', ['code' => $donor->campaign->code]);
        } else {
            abort(404);
        }
    }

    protected function onPaymentSuccess(string $orderId, string $productDetail, int $amount, string $paymentCode, ?string $shopeeUserHash, string $reference, ?string $additionalParam): void
    {
        $donor = Donor::where('uuid', $orderId)
            ->first();
        if ($donor) {
            $donor->amount = $amount;
            $donor->paid = true;
            $donor->paid_at = Carbon::now();
            $donor->save();

            $campaign = Campaign::find($donor->campaign_id);
            if ($campaign) {
                $totalCollected = Donor::where('campaign_id', $campaign->id)
                    ->where('paid', true)
                    ->sum('amount');
                $totalDonors = Donor::where('campaign_id', $campaign->id)
                    ->where('paid', true)
                    ->count();

                $campaign->last_donation = Carbon::now();
                $campaign->collected = $totalCollected;
                $campaign->donors = $totalDonors;
                $campaign->save();
            }
        }
    }

    protected function onPaymentFailed(string $orderId, string $productDetail, int $amount, string $paymentCode, ?string $shopeeUserHash, string $reference, ?string $additionalParam): void
    {
        // do nothing
    }

}
