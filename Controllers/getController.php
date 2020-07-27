<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\banks;

class getController extends Controller
{
    public function get(){

        $banks = banks::all();
        foreach ($banks as $bank) {
            $MerchantID = $bank->token;
            $Description = $bank->description;
            $Email = $bank->email;
            $Mobile = $bank->mobile;
        }

        try {

            $gateway = app('gateway')->zarinpal();
            $gateway->setCallback(url('/bank/response'));
            $gateway->setInformation($MerchantID,$Email,$Mobile,$Description);
            $gateway->price(1000)
                // setShipmentPrice(10) // optional - just for paypal
                // setProductName("My Product") // optional - just for paypal
                ->ready();

            $refId =  $gateway->refId(); // شماره ارجاع بانک
            $transID = $gateway->transactionId(); // شماره تراکنش

            // در اینجا
            //  شماره تراکنش  بانک را با توجه به نوع ساختار دیتابیس تان
            //  در جداول مورد نیاز و بسته به نیاز سیستم تان
            // ذخیره کنید .

            return $gateway->redirect();

        } catch (\Exception $e) {

            echo $e->getMessage();
        }
    }
}
