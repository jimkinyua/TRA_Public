<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 3/6/2015
 * Time: 7:43 PM
 */

class PaymentController extends BaseController {

    public function getPaymentStatus($account)
    {
        $invoice = Invoice::findOrFail($account);
        $required = $invoice->total();

        $amount = MPESA::FindByAccount($account)->sum('Amount');


        if (!$amount){
            $data['code'] = 400;
            $data['message'] = "No payment has  been made";
            $data['balance'] = $required - $amount;
        }elseif ($amount < $required){
            $data['code'] = 300;
            $data['message'] = "Please complete your payment, balance is ".($required - $amount);
            $data['balance'] = $required - $amount;
        }elseif ($amount >= $required){
            $data['code'] = 200;
            $data['message'] = "Payment has been made successfully. Your permit will be generated";
            $data['balance'] = $required - $amount;
        }

        return Response::json($data);
    }
}