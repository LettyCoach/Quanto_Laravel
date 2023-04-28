<?php
    
namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Card;
use App\Models\Customer;
use App\Models\Mail;
use App\Models\Order;
use App\Models\Product;
use App\Models\Survey;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Str;
use Stripe;

     
class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe()
    {
        return view('frontend.stripe');
    }
    
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {
        if (empty(Session::get('cart'))) {
            return back()->withInput($request->input());
        }
        if (!$request->session()->has('customer_id')) {
            return back()->withInput($request->input());
        }

        $customer = Customer::where('id', Session::get('customer_id'))->first();    

        if (empty(Session::get('order_id'))) {

            $survey = Survey::where('id', Session::get('survey_id'))->first();

            $order = new Order();
            $order->customer_id = $customer->id;
            $order->name = $customer->name;
            $order->email = $customer->email;
            $order->user_id = $survey->user_id;
            $order->survey_id = $survey->id;
            $order->survey_title = $survey->title;
            $order->save();

            $order->order_id =  "Q" . ($order->user_id + 1000) . "-" . $order->id;
            $order->save();

            $request->session()->put('order_id', $order->id);

        }else{

            $survey = Survey::where('id', Session::get('survey_id'))->first();

            $order = Order::where('id', Session::get('order_id'))
                ->where('survey_id', $survey->id)->first();

            if(empty($order)){
                Session::pull('cart');
                Session::pull('order_id');
                Session::pull('survey_id');
                return redirect('show/' . $survey->token);
            }else{
                Product::where('order_id', $order->id)->delete();
            }    

        }

        $session_cart = Session::get('cart');
        $metadata = "";
        $quantity=0;
        $total=0;
        $items = [];
        if ($session_cart) {
            foreach ($session_cart as $key => $each) {

                if ($each['quantity'] > 0 and $each['price'] > 0) {
                    $products = Str::replace('<strong>', '', $each['products']);
                    $products = Str::replace('</strong>', '', $products);
                    $products = Str::replace('<br>', ' ', $products);
                    $metadata .= $products . ' => ' . $each['quantity'] . ' , ';

                    $product = new Product();
                    $product->order_id = $order->id;
                    $product->product_id = $each['id'];
                    $product->product_name = $products; 
                    $product->price= $each['price'];
                    $product->units = $each['quantity'];
                    $product->save();

                    $items[] = $product;

                    $quantity += $each['quantity'];
                    $total += ($each['price'] * $each['quantity']);                        
                }
            }
            $order->units = $quantity;
            $order->amount = $total;
            $order->save();
        }

        // if($total<100){
        //     Session::flash('error_message', 'お支払いが失敗しました.');
        //     return back()->withInput($request->input());
        // }

        $survey_settings = json_decode($survey->settings, true);

        if ($request->pay_method == 'お振込') {
            $order->payment_method = 2;
            $payMethod= "銀行振り込み";
        }
        elseif ($request->pay_method == '代引き') {
            $order->payment_method = 3;
            $payMethod = "代引き交換";
        }
        elseif ($request->pay_method == '店舗受取り') {
            $order->payment_method = 4;
            $payMethod = "店舗受取り";
        }else{
            $payMethod = "クレジットカード";
            require_once(base_path() . '/vendor/stripe/stripe-php/init.php');

            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            if ($request->stripeToken !== NULL) {

                if (empty($customer->stripe_id)) {
                    try {
                        $newCustomer = \Stripe\Customer::create([
                            'email' => $customer->email,
                            'name' => $customer->name,
                            'metadata' => ['CustomerId' => $customer->id]
                        ]);
                    } catch (\Exception $e) {
                    }
                    if (!isset($newCustomer['id'])) {
                        Session::flash('error_message', 'カード登録が失敗しました.');
                        return back()->withInput($request->input());
                    }
                    $customer->stripe_id = $newCustomer['id'];
                    $customer->save();
                }

                try {
                    $newCard = \Stripe\Customer::createSource(
                        $customer->stripe_id,
                        ['source' => $request->stripeToken]
                    );
                } catch (\Exception $e) {
                }

                if (!isset($newCard['id'])) {
                    Session::flash('error_message', 'カード登録が失敗しました.');
                    return back()->withInput($request->input());
                }

                $card = new Card();
                $card->customer_id = $customer->id;
                $card->name = $request->card_name;
                $card->card_id = $newCard['id'];
                $card->last = $newCard['last4'];
                $card->expiry = $newCard['exp_year'] . "-" . $newCard['exp_month'];
                $card->save();
            }

            if ($request->card !== NULL && $request->card > 0) {
                $card = Card::find($request->card);
            }

            if (!empty($card)) {
                $prevCard = Card::where('customer_id', $customer->id)
                    ->where('status', 1)->first();

                if (!empty($prevCard)) {
                    if($prevCard->id != $card->id){
                        $prevCard->status = 0;
                        $prevCard->save();
                        $default = \Stripe\Customer::retrieve($customer->stripe_id);
                        $default->default_source = $card->card_id;
                        $default->save();

                        $card->status = 1;
                        $card->save();
                    }
                }else{
                    
                    $default = \Stripe\Customer::retrieve($customer->stripe_id);
                    $default->default_source = $card->card_id;
                    $default->save();
                    $card->status = 1;
                    $card->save();
                }

                sleep(2);

                try {
                    $charge = Stripe\Charge::create([
                        "amount" => $request->pay_total,
                        "currency" => "jpy",
                        "customer" => $customer->stripe_id,
                        "description" => "注文ID : Q".($order->user_id + 1000)."-". $order->id . " (" . $customer->name . " - " . $customer->email . ")",
                        "metadata" =>  [
                            "住所" => $request->address . $request->address2 . $request->address3,
                            "郵便番号" => $request->postcodeFirst . '-' . $request->postcodeLast,
                            "電話" => $request->phone,
                            "注文内容" => $metadata,
                        ]
                    ]);
                } catch (\Exception $e) {
                    echo $e;
                    return;
                }
                if (!isset($charge['id'])) {
                    Session::flash('error_message', 'お支払いが失敗しました.');
                    return back()->withInput($request->input());
                }

                $order->payment_id = $charge["id"];
                $order->payment_method = 1;
                $order->payment_status = 1;
                $order->paid_at = date("Y-m-d H:i:s");

            } else {
                Session::flash('error_message', 'お支払いが失敗しました.');
                return back()->withInput($request->input());
            }
        }



        if ($request->billingAddress > 0) {
            $address = Address::where('id', $request->billingAddress)->first();
        } else {
            $prevAddress = Address::where('customer_id', $customer->id)
                ->where('type', 1)
                ->where('status', 1)->first();

            if (!empty($prevAddress)) {
                $prevAddress->status = 0;
                $prevAddress->save();
            }

            $address = new Address();
            $address->customer_id = $customer->id;
            $address->name = $customer->name;
            $address->kana = $customer->kana;
            $address->postcode = $request->postcodeFirst . '-' . $request->postcodeLast;
            $address->address = $request->address;
            $address->address2 = $request->address2;
            $address->address3 = $request->address3;
            $address->phone = $request->phone;
            $address->type = 1;
            $address->status = 1;
            $address->save();
        }

        if ($request->shippingAddress == "same") {
            $shipping_address = $address;
        } else {
            if ($request->get('shippingAddress') > 0) {
                $shipping_address = Address::where('id', $request->shippingAddress)->first();
            } else {
                $prevShippingAddress = Address::where('customer_id', $customer->id)
                    ->where('type', 2)
                    ->where('status', 1)->first();

                if (!empty($prevShippingAddress)) {
                    $prevShippingAddress->status = 0;
                    $prevShippingAddress->save();
                }
                $shipping_address = new Address();
                $shipping_address->customer_id = $customer->id;
                $shipping_address->name = $request->nameNew;
                $shipping_address->kana = $request->kanaNew;
                $shipping_address->postcode = $request->postcodeFirstNew . '-' . $request->postcodeLastNew;
                $shipping_address->address = $request->addressNew;
                $shipping_address->address2 = $request->addressNew2;
                $shipping_address->address3 = $request->addressNew3;
                $shipping_address->phone = $request->phoneNew;
                $shipping_address->type = 2;
                $shipping_address->status = 1;
                $shipping_address->save();

            }
        }
        $date = Str::replace(['年','月'],'-',$request->date . " " . $request->time."00");
        $date =Str::replace(['時','分'],':',$date);
        $date = Str::replace(['日'],'',$date);

        $order->postcode = $shipping_address->postcode;
        $order->address_id = $address->id;
        $order->address = $address->address;
        $order->address2 = $address->address2;
        $order->address3 = $address->address3;
        $order->phone = $address->phone;
        $order->shipping_address_id = $shipping_address->id;
        $order->shipping_address = $shipping_address->address;
        $order->shipping_address2 = $shipping_address->address2;
        $order->shipping_address3 = $shipping_address->address3;
        $order->shipping_name = $shipping_address->name;
        $order->shipping_kana = $shipping_address->kana;
        $order->shipping_phone = $shipping_address->phone;
        $order->units = $quantity;
        $order->amount = $total;
        $order->accept_method = $request->delivery_method;
        $order->accept_time = $date;

        $order->save();

        $itemList ="";
        foreach ($items as $item) {
            $itemList .= "<p>" . $item->product_name . "　X　" . $item->units . "　＝　" . number_format($item->price * $item->units) . "円</p>";
        }

        $shippingItemList = "";
        foreach ($items as $item) {
            $shippingItemList .= "<p>" . $item->product_name . "　X　" . $item->units . "</p>";
        }

        $sub_total = $total;
        $tracking_code= "";

        $email = Mail::where('name', '注文受付自動通知（管理者・事業者）')->first();

        $content = nl2br($email->content);
        $content = Str::replace('[[order_id]]', "Q" . ($order->user_id + 1000) . "-" . $order->id, $content);
        $content = Str::replace('[[date_time]]', $order->created_at, $content);
        $content = Str::replace('[[name]]', $order->name, $content);
        $content = Str::replace('[[items]]',$itemList, $content);
        $content = Str::replace('[[sub_total]]', $sub_total, $content);
        $content = Str::replace('[[payment_method]]', $payMethod, $content);
        $content = Str::replace('[[accept_date_time]]', date("m月d日 H時i分頃",strtotime($order->accept_time)), $content);
        $content = Str::replace('[[profile_id]]',  ($order->user_id + 1000) , $content);
        $content = Str::replace('[[survey_id]]', $survey->id, $content);
        $content = Str::replace('[[date+time]]', date("YmdHis", strtotime($order->created_at)), $content);
        $content = Str::replace('[[title]]', $survey->title, $content);
        $content = Str::replace('[[total]]', $total, $content);
        $content = Str::replace('[[tracking_code]]', $tracking_code, $content);
        $content = Str::replace('[[shipping_name]]', $order->shipping_name, $content);
        $content = Str::replace('[[shipping_postcode]]', $order->shipping_postcode, $content);
        $content = Str::replace('[[shipping_address]]', $order->shipping_address. $order->shipping_address2. $order->shipping_address3, $content);
        $content = Str::replace('[[shipping_items]]', $shippingItemList, $content);

        $to_email = "hosokawa@quanto.xbiz.jp";
        $to_name = "細川";

        $this->sendMail($to_email,$to_name,$email->subject, $content);

        $user = User::find($order->user_id);

        $this->sendMail($user->email, $user->name, $email->subject, $content);

        if($survey_settings['autoSendMail']) {

            if($order->payment_method==2 && $order->accept_method ==1){

                $email = Mail::where('name', '銀行振り込み後店舗自動受付メール')->first();

                $content = nl2br($email->content);
                $content = Str::replace('[[order_id]]', "Q" . ($order->user_id + 1000) . "-" . $order->id, $content);
                $content = Str::replace('[[date_time]]', $order->created_at, $content);
                $content = Str::replace('[[name]]', $order->name, $content);
                $content = Str::replace('[[items]]', $itemList, $content);
                $content = Str::replace('[[sub_total]]', $sub_total, $content);
                $content = Str::replace('[[payment_method]]', $payMethod, $content);
                $content = Str::replace('[[accept_date_time]]', date("m月d日 H時i分頃", strtotime($order->accept_time)), $content);
                $content = Str::replace('[[profile_id]]', ($order->user_id + 1000), $content);
                $content = Str::replace('[[survey_id]]', $survey->id, $content);
                $content = Str::replace('[[date+time]]', date("YmdHis", strtotime($order->created_at)), $content);
                $content = Str::replace('[[title]]', $survey->title, $content);
                $content = Str::replace('[[total]]', $total, $content);
                $content = Str::replace('[[tracking_code]]', $tracking_code, $content);
                $content = Str::replace('[[shipping_name]]', $order->shipping_name, $content);
                $content = Str::replace('[[shipping_postcode]]', $order->shipping_postcode, $content);
                $content = Str::replace('[[shipping_address]]', $order->shipping_address . $order->shipping_address2 . $order->shipping_address3, $content);
                $content = Str::replace('[[shipping_items]]', $shippingItemList, $content);

                $this->sendMail($order->email, $order->name, $email->subject, $content);

            }elseif($order->accept_method==2){

                $email = Mail::where('name', 'デリバリー自動受付メール')->first();

                $content = nl2br($email->content);
                $content = Str::replace('[[order_id]]', "Q" . ($order->user_id + 1000) . "-" . $order->id, $content);
                $content = Str::replace('[[date_time]]', $order->created_at, $content);
                $content = Str::replace('[[name]]', $order->name, $content);
                $content = Str::replace('[[items]]', $itemList, $content);
                $content = Str::replace('[[sub_total]]', $sub_total, $content);
                $content = Str::replace('[[payment_method]]', $payMethod, $content);
                $content = Str::replace('[[accept_date_time]]', date("m月d日 H時i分頃", strtotime($order->accept_time)), $content);
                $content = Str::replace('[[profile_id]]', ($order->user_id + 1000), $content);
                $content = Str::replace('[[survey_id]]', $survey->id, $content);
                $content = Str::replace('[[date+time]]', date("YmdHis", strtotime($order->created_at)), $content);
                $content = Str::replace('[[title]]', $survey->title, $content);
                $content = Str::replace('[[total]]', $total, $content);
                $content = Str::replace('[[tracking_code]]', $tracking_code, $content);
                $content = Str::replace('[[shipping_name]]', $order->shipping_name, $content);
                $content = Str::replace('[[shipping_postcode]]', $order->shipping_postcode, $content);
                $content = Str::replace('[[shipping_address]]', $order->shipping_address . $order->shipping_address2 . $order->shipping_address3, $content);
                $content = Str::replace('[[shipping_items]]', $shippingItemList, $content);

                $this->sendMail($order->email, $order->name, $email->subject, $content);

            }elseif($order->payment_method ==2 && $order->accept_method !=1){
                $email = Mail::where('name', '銀行振り込み自動受付メール')->first();

                $content = nl2br($email->content);
                $content = Str::replace('[[order_id]]', "Q" . ($order->user_id + 1000) . "-" . $order->id, $content);
                $content = Str::replace('[[date_time]]', $order->created_at, $content);
                $content = Str::replace('[[name]]', $order->name, $content);
                $content = Str::replace('[[items]]', $itemList, $content);
                $content = Str::replace('[[sub_total]]', $sub_total, $content);
                $content = Str::replace('[[payment_method]]', $payMethod, $content);
                $content = Str::replace('[[accept_date_time]]', date("m月d日 H時i分頃", strtotime($order->accept_time)), $content);
                $content = Str::replace('[[profile_id]]', ($order->user_id + 1000), $content);
                $content = Str::replace('[[survey_id]]', $survey->id, $content);
                $content = Str::replace('[[date+time]]', date("YmdHis", strtotime($order->created_at)), $content);
                $content = Str::replace('[[title]]', $survey->title, $content);
                $content = Str::replace('[[total]]', $total, $content);
                $content = Str::replace('[[tracking_code]]', $tracking_code, $content);
                $content = Str::replace('[[shipping_name]]', $order->shipping_name, $content);
                $content = Str::replace('[[shipping_postcode]]', $order->shipping_postcode, $content);
                $content = Str::replace('[[shipping_address]]', $order->shipping_address . $order->shipping_address2 . $order->shipping_address3, $content);
                $content = Str::replace('[[shipping_items]]', $shippingItemList, $content);

                $this->sendMail($order->email, $order->name, $email->subject, $content);

            }else{

                $email = Mail::where('name', '自動受付メール')->first();

                $content = nl2br($email->content);
                $content = Str::replace('[[order_id]]', "Q" . ($order->user_id + 1000) . "-" . $order->id, $content);
                $content = Str::replace('[[date_time]]', $order->created_at, $content);
                $content = Str::replace('[[name]]', $order->name, $content);
                $content = Str::replace('[[items]]', $itemList, $content);
                $content = Str::replace('[[sub_total]]', $sub_total, $content);
                $content = Str::replace('[[payment_method]]', $payMethod, $content);
                $content = Str::replace('[[accept_date_time]]', date("m月d日 H時i分頃", strtotime($order->accept_time)), $content);
                $content = Str::replace('[[profile_id]]', ($order->user_id + 1000), $content);
                $content = Str::replace('[[survey_id]]', $survey->id, $content);
                $content = Str::replace('[[date+time]]', date("YmdHis", strtotime($order->created_at)), $content);
                $content = Str::replace('[[title]]', $survey->title, $content);
                $content = Str::replace('[[total]]', $total, $content);
                $content = Str::replace('[[tracking_code]]', $tracking_code, $content);
                $content = Str::replace('[[shipping_name]]', $order->shipping_name, $content);
                $content = Str::replace('[[shipping_postcode]]', $order->shipping_postcode, $content);
                $content = Str::replace('[[shipping_address]]', $order->shipping_address . $order->shipping_address2 . $order->shipping_address3, $content);
                $content = Str::replace('[[shipping_items]]', $shippingItemList, $content);

                $this->sendMail($order->email, $order->name, $email->subject, $content);
                
            }

        }

        Session::pull('cart');
        Session::pull('order_id');
        //Session::pull('survey_id');
        Session::flash('success_message', 'ご注文ありがとうございました。');

        return redirect('thank-you/' . $survey->token);

    }

    private function sendMail($to_email, $to_name ,$subject, $content)
    {

        $mail = new PHPMailer(true);

        $mail->CharSet = 'UTF-8';
        $mail->XMailer = ' ';
        //Enable SMTP debugging. 
        //$mail->SMTPDebug = 2;
        //Set PHPMailer to use SMTP.
        $mail->isSMTP();
        //Set SMTP host name                          
        $mail->Host = env('MAIL_HOST');
        //Set this to true if SMTP host requires authentication to send email
        $mail->SMTPAuth = true;
        //Provide username and password     
        $mail->Username =  env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');
        //If SMTP requires TLS encryption then set it
        $mail->SMTPSecure = 'ssl';
        //Set TCP port to connect to 
        $mail->Port = env('MAIL_PORT');

        //From email address and name
        $mail->From = env('MAIL_FROM_ADDRESS');
        $mail->FromName = env('MAIL_FROM_NAME');

        //To address and name
        $mail->addAddress($to_email, $to_name);
        // $mail->addAddress("recepient1@example.com"); //Recipient name is optional

        //Address to which recipient will reply
        $mail->addReplyTo('hosokawa@quanto.xbiz.jp', "Reply");

        //CC and BCC
        // $mail->addCC("cc@example.com");
        // $mail->addBCC("bcc@example.com");

        //Send HTML or Plain Text email
        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $content;
        $mail->AltBody = $content;

        //Attachments
        //$mail->addAttachment("file.txt", "File.txt");
        // $mail->addAttachment("images/profile.png"); //Filename is optional


        if (!$mail->send()) {
            //  Log::info("Mailer Error: " . $mail->ErrorInfo);
            //echo "Mailer Error: " . $mail->ErrorInfo;
            return;
        } else {
            //  Log::info("Message has been sent successfully");
            //echo "Message has been sent successfully";
            return;
        }
    }

}