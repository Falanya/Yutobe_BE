<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function createPaymentLink(Request $request){
        $FE_DOMAIN = env("URL_FE");
        $YOUR_DOMAIN = $request->getSchemeAndHttpHost();
        $data = [
            "orderCode" => intval(substr(strval(microtime(true) * 10000), -6)),
            "amount" => 2000,
            "description" => "Thanh toán đơn hàng",
            // "returnUrl" => "https://yutube.huylab.click/product",
            "returnUrl" => $YOUR_DOMAIN . "/success.html",
            "cancelUrl" => $YOUR_DOMAIN . "/cancel.html"
        ];
        error_log($data['orderCode']);

        try {
            $response = $this->payOS->createPaymentLink($data);
            return redirect($response['checkoutUrl']);
        } catch (\Throwable $th) {
            return $this->handleException($th);
        }

    }
}
