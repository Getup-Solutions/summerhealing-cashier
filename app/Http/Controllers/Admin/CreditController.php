<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Credit;
use App\Models\Subscriptionplan;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    //
    public function index(){
        dd(request()->all());
    }

    public function store($creditsInfo, $subscriptionplanId){
        // dd($creditsInfo);
        if(isset($creditsInfo["sessionGenCredits"])){
            $creditsInfo["sessionGenCredits"]["subscriptionplan_id"] = $subscriptionplanId;
            Credit::create($creditsInfo["sessionGenCredits"]);
        }
        if(isset($creditsInfo["facilityGenCredits"])){
            $creditsInfo["facilityGenCredits"]["subscriptionplan_id"] = $subscriptionplanId;
            Credit::create($creditsInfo["facilityGenCredits"]);
        }
        if(isset($creditsInfo["sessionCredits"])){
            foreach ($creditsInfo["sessionCredits"] as $sessionCredit) {
                if($sessionCredit['creditable_id'] > 0){
                    $sessionCredit["subscriptionplan_id"] = $subscriptionplanId;
                    Credit::create($sessionCredit);
                }
            }
        }
        if(isset($creditsInfo["facilityCredits"])){
            foreach ($creditsInfo["facilityCredits"] as $facilityCredit) {
                if($facilityCredit['creditable_id'] > 0){
                $facilityCredit["subscriptionplan_id"] = $subscriptionplanId;
                Credit::create($facilityCredit);
                }
            }
        }
        return;
}

public function update($creditsInfo, $subscriptionplanId){
    // dd($creditsInfo);
    Subscriptionplan::find($subscriptionplanId)->credits()->delete();
    if(isset($creditsInfo["sessionGenCredits"])){
        // $creditsInfo["sessionGenCredits"]["subscriptionplan_id"] = $subscriptionplanId;
        unset($creditsInfo["sessionGenCredits"]['id']);
        Credit::create($creditsInfo["sessionGenCredits"]);
    }
    if(isset($creditsInfo["facilityGenCredits"])){
        // $creditsInfo["facilityGenCredits"]["subscriptionplan_id"] = $subscriptionplanId;
        unset($creditsInfo["facilityGenCredits"]['id']);
        Credit::create($creditsInfo["facilityGenCredits"]);
    }
    if(isset($creditsInfo["sessionCredits"])){
        foreach ($creditsInfo["sessionCredits"] as $sessionCredit) {
            // $sessionCredit["subscriptionplan_id"] = $subscriptionplanId;
            unset($sessionCredit['id']);
            Credit::create($sessionCredit);
        }
    }
    if(isset($creditsInfo["facilityCredits"])){
        foreach ($creditsInfo["facilityCredits"] as $facilityCredit) {
            // $facilityCredit["subscriptionplan_id"] = $subscriptionplanId;
            unset($facilityCredit['id']);
            Credit::create($facilityCredit);
        }
    }
    return;
}
}
