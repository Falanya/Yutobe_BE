<?php

namespace App\Http\Controllers\API\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutControlle extends Controller
{
    public function createPaymentLink(Request $request){
        $auth = auth()->user();
        $FE_DOMAIN = env("URL_FE");
        $data = [
            "orderCode" => intval(substr(strval(microtime(true) * 10000), -6)),
            "amount" => 2000,
            "description" => "UserID $auth->id",
            // "returnUrl" => "https://yutube.huylab.click/product",
            "returnUrl" => $FE_DOMAIN . "productdetail/67d1c1aaa8152",
            "cancelUrl" => $FE_DOMAIN . "productdetail/67d1c1fa33721",
        ];
        error_log("Order Code: " . $data['orderCode']);
        try{
            $response = $this->payOS->createPaymentLink($data);
            return response()->json([
                "success" => true,
                "checkoutUrl" => $response['checkoutUrl'],
            ]);
        }catch(\Throwable $th){
            return $this->handleException($th);
        }
    }
}
