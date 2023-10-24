<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Credit;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    //
    public function index(){
        dd(request()->all());
    }

    public function store($creditsInfo, $subscriptionplanId){
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
                $sessionCredit["subscriptionplan_id"] = $subscriptionplanId;
                Credit::create($sessionCredit);
            }
        }
        if(isset($creditsInfo["facilityCredits"])){
            foreach ($creditsInfo["facilityCredits"] as $facilityCredit) {
                $facilityCredit["subscriptionplan_id"] = $subscriptionplanId;
                Credit::create($facilityCredit);
            }
        }
        return;
}
}
