  <?php
  public function paymentRequest(Request $request)
    {
        $order_id = $request->input('order_id');
        $payment = Payment::find($request->input('payment_type'));
        $mobile = $request->input('mobile');//---------------required for stc pay only-------

        //-------------------first get user address information ------------------------------
        if ($address_id == NULL) {
            return response(['error' => '1', 'msg' => 'Please enter all mandatory fields']);
        }

        $address = Address::find($request->input('address_id'));
        if ($address) {
            $country     = $address->country;
            $city        = $address->city;
            $state       = $address->state;
            $postal_code = $address->postal_code;
            $street = $address->street;
            $phone = $address->phone;  
            $fullname = $address->first_name;
            $last_name = $address->last_name;
              

        } else {
            return response(['error' => '1', 'msg' => 'address not found']);
        }

        //-----------------secound get order information ----------------------------------------
        if (!$order_id)
        return response(['error' => '2', 'msg' => 'Order  doesnot exist']);

        $order = Order::find($order_id);
        if($order){
                $amount = $order->total;
        }else{
            return response(['error' => '1', 'msg' => 'order not found']); 
        }

       
      //----------------third get payment Method information ------------------------------------
        if (!$payment)
            return response(['error' => '3', 'msg' => 'Payment Method doesnot exist']);            
        $brand      = $payment->code;
        $entityId   = $payment->entityId;
        $secret_key = $payment->secret_key;

        $email = auth('api')->user()->email;

        //------------------------------------------------------------------
        $url = "https://oppwa.com/v1/checkouts";
        $data = "entityId=$entityId" .
            "&amount=$amount" .
            "&currency=SAR" .
            "&paymentType=DB";
        $data .= "&billing.street1=$street" .
            "&billing.city=$city" .
            "&billing.state=$state" .
            "&billing.country=SA" . // $country
            "&billing.postcode=$postal_code" .
            "&customer.givenName=$fullname" .
            "&customer.surname=$last_name" .
            "&customer.email=$email" .
            "&merchantTransactionId=" .$order_id
        // print_r($data);die();
        if($brand === "STCPAY" )
        {
            if (!$mobile)
            return response(['error' => '2', 'msg' => 'mobile  doesnot exist']);
    
            $data .="&customParameters[SHOPPER_payment_mode]=mobile";
            $data .= "&customer.mobile=$mobile"; // STCPAY mobile number 05xxxxxxxx
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer ' . $secret_key
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return response(['error' => '0', 'msg' => $responseData]);
    }

    public function paymentStatus(Request $request)
    {

        $id = $request->checkout_id;
        $brand = $request->payment_type;
     
        if ($id == NULL || $brand == NULL) {
            return response(['error' => '0', 'msg' => 'Please enter all mandatory fields']);
        }

        $payment    = Payment::find($request->brand);
        $brand      = $payment->code;
        $entityId   = $payment->entityId;
        $secret_key = $payment->secret_key;

        $url = "https://oppwa.com/v1/checkouts/" . $id . "/payment";
        $url .= "?entityId=$entityId";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer ' . $secret_key
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            $result =  'Error:' . curl_error($ch);
            return response(['error' => '1', 'msg' => $result]);
        }
        curl_close($ch);
        
        //-----------------check payment status from response code ------------------------------------------
        $data = json_decode($responseData);
        
        $code = $this->check_payment($data->result->code);
        if ($code === "successful")
            $payment_status = "PAID";
        else 
            $payment_status = "NOT_PAID";

           todo::update payment status in orders table;
           return response(['error' => '0', 'msg' => $payment_status]);


    }




    protected function checkPayment($code){
        $successRegExp = ("(000\.000\.|000\.100\.1|000\.[36]|000\.400\.0[^3]|000\.400\.[0-1]{2}0)");
        $pendingRegExp = ("(000\.200|800\.400\.5|100\.400\.500)");
           // $code = "000.400.090";
       
           if (preg_match($successRegExp,$code)) {
               return "successful";
           } else if (preg_match($pendingRegExp,$code)) {
               return "pending";
           } else {
               return "rejected";

       
           }
    }
 