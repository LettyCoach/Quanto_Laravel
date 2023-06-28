<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Answer;
use App\Models\AnswerType;
use App\Models\Card;
use App\Models\Client;
use App\Models\Customer;
use App\Models\ClientAnswer;
use App\Models\Mail;
use App\Models\Order;
use App\Models\Product;
use App\Models\Question;
use App\Models\QuestionType;
use App\Models\ReferralInfo;
use App\Models\Status;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class frontendController extends Controller
{
    public function index(Request $request, $id)
    {
        $query = Survey::where('token', $id)->first();
        if (empty($query)) {
            return redirect(301);
        }
        $question = Question::where('survey_id', $query->id)->orderBy('ord')->get();
        $answer = Answer::where('survey_id', $query->id)->get();
        $referral = array();
        foreach ($question as $q) {
            $refer_id = $q->referral_info;

            if ($refer_id != null && !in_array($refer_id, $referral)) {
                $referral[] = (int) $refer_id;
            }
        }
        foreach ($answer as $a) {
            $refer_id = $a->referral_info;

            if ($refer_id != null && !in_array($refer_id, $referral)) {
                $referral[] = (int) $refer_id;
            }
        }
        $referral_info = ReferralInfo::whereIn('id', $referral)->get();
        Log::debug($referral_info);
        $query['referral'] = $referral_info;
        $query['questions'] = $question;
        $query['answers'] = $answer;
        $query['brand_logo_path'] = $query['profile_path'];
        $user_profile_url = '';
        $user_profile_name = '';
        if ($query != null) {
            $user = User::find($query->user_id);
            if ($user != null) {
                $user_profile_url = $user->profile_url;
                $user_profile_name = $user->full_name;
            }
        }
        $query['user_profile_url'] = $user_profile_url;
        $query['user_profile_name'] = $user_profile_name;
        //return response()->json($query);
        $session_cart = Session::get('cart');

        return view('frontend.index', compact('query', 'session_cart'));
    }

    public function thanks(Request $request, $id)
    {
        $query = Survey::where('token', $id)->first();
        if (empty($query)) {
            return redirect(301);
        }
        if (!$request->session()->has('customer_id')) {
            return redirect('show/' . $query->token);
        }
        if (empty(Session::get('success_message'))) {
            return redirect('show/' . $id);
        }

        return view('frontend.thanks', compact('query'));
    }

    public function createCart(Request $request)
    {

        if ((Session::get('survey_id')) !== $request->survey_id) {
            Session::pull('cart');
        }



        $cartData = [
            'id' => $request->product_id,
            'img' => $request->product_img,
            'products' => $request->product_name,
            'quantity' => $request->product_q,
            'price' => $request->product_p,
        ];

        //sessionにcartData配列が「無い」場合、商品情報の配列をcartData(key)という名で$cartDataをSessionに追加
        if (!$request->session()->has('cart')) {
            $request->session()->push('cart', $cartData);
        } else {
            //sessionにcartData配列が「有る」場合、情報取得
            $sessionCartData = $request->session()->get('cart');

            //isSameProductId定義 product_id同一確認フラグ false = 同一ではない状態を指定
            $isSameProductId = false;
            foreach ($sessionCartData as $index => $sessionData) {
                //product_idが同一であれば、フラグをtrueにする → 個数の合算処理、及びセッション情報更新。更新は一度のみ
                if ($sessionData['id'] === $cartData['id']) {
                    $isSameProductId = true;
                    $quantity = $sessionData['quantity'] + $cartData['quantity'];
                    //cartDataをrootとしたツリー状の多次元連想配列の特定のValueにアクセスし、指定の変数でValueの上書き処理
                    $request->session()->put('cart.' . $index . '.quantity', $quantity);
                    break;
                }
            }

            //product_idが同一ではない状態を指定 その場合であればpushする
            if ($isSameProductId === false) {
                $request->session()->push('cart', $cartData);
            }
        }

        //POST送信された情報をsessionに保存 'survey_id'(key)に$request内の'survey_id'をセット
        $request->session()->put('survey_id', ($request->survey_id));

        //return redirect()->back();

        $session_cart = Session::get('cart');
        $items = '<a class="text-decoration-none text-center w-100 text-black-50 pt-2 close-cart" style="cursor:pointer;">
                    <h4>X</h4>
                </a>
                <div class="col py-3">';
        $units = 0;
        if ($session_cart) {
            foreach ($session_cart as $key => $each) {
                $units += $each['quantity'];
                $items .= '<div class="row">
                                <div class="col-2">
                                    <img src="../' . $each['img'] . '" class ="cart_img">
                                </div>
                                <div class="col-6">
                                    ' . $each['products'] . " (" . $each['price'] . "円) x " . $each['quantity'] . '
                                </div>
                                <div class="col-4">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" class="update-quantity-left-minus btn btn-outline-primary btn-number" data-type="minus" data-field="" data-key="' . $each['id'] . '">
                                                <span class="glyphicon glyphicon-minus">-</span>
                                            </button>
                                        </span>
                                        <input type="text" id="quantity_' . $each['id'] . '" name="quantity_' . $each['id'] . '" class="form-control input-number" value="' . $each['quantity'] . '" min="1" max="100" style="text-align: center">
                                        <span class="input-group-btn">
                                            <button type="button" class="update-quantity-right-plus btn btn-outline-primary btn-number" data-type="plus" data-field="" data-key="' . $each['id'] . '">
                                                <span class="glyphicon glyphicon-plus">+</span>
                                            </button>
                                        </span>
                                    </div>

                                </div>
                            </div>

                            <hr>';
            }
            if (empty(Session::get('customer_id'))) {
                $items .= '<div class="col-12 text-center">
                            <button id="login" class="btn btn-sm btn-quanto order-button" data-bs-toggle="modal" data-bs-target="#loginModal">注文手続きへ</button>
                        </div>';
            } else {
                $items .= '<div class="col-12 text-center">
                            <a href="../checkout" class="btn btn-sm btn-quanto order-button">注文手続きへ</a>
                        </div>';
            }
        } else {

            $items .= ' <div class="col-12 text-center">
                            <p class="text-center">カートは空です。</p>
                        </div>';
        }

        $items .= ' </div>';

        $cart = '<div class="col">
                    <div class="items_count text-light" id="items_count">';


        $cart .= $units . ' 点';


        $cart .= '</div>
                </div>
                <div class="col">
                    <a class="btn view-card-btn mt-1">カートを見る</a>
                </div>
                <div class="col">
                    <div class="total_result text-light" id="total_result">';
        if ($session_cart) {
            $total = 0;
            foreach ($session_cart as $key => $count) {
                $total += ($count['price'] * $count['quantity']);
            }
            $cart .= number_format($total) . ' 円';
        } else {
            $cart .= 0 . ' 円';
        }
        $cart .= ' </div>
                </div>';
        return response()->json([
            'status' => 200,
            'list' => $items,
            'cart' => $cart
        ]);
    }

    public function updateCart(Request $request)
    {

        if ((Session::get('survey_id')) !== $request->survey_id) {
            Session::pull('cart');
        }

        $cartData = [
            'id' => $request->product_id,
            'quantity' => $request->product_q,
        ];

        //sessionにcartData配列が「無い」場合、商品情報の配列をcartData(key)という名で$cartDataをSessionに追加
        if (!$request->session()->has('cart')) {
            // $request->session()->push('cart', $cartData);
        } else {
            //sessionにcartData配列が「有る」場合、情報取得
            $sessionCartData = $request->session()->get('cart');

            //isSameProductId定義 product_id同一確認フラグ false = 同一ではない状態を指定
            $isSameProductId = false;
            foreach ($sessionCartData as $index => $sessionData) {
                //product_idが同一であれば、フラグをtrueにする → 個数の合算処理、及びセッション情報更新。更新は一度のみ
                if ($sessionData['id'] === $cartData['id']) {
                    $isSameProductId = true;
                    $quantity = $cartData['quantity'];
                    //cartDataをrootとしたツリー状の多次元連想配列の特定のValueにアクセスし、指定の変数でValueの上書き処理
                    $request->session()->put('cart.' . $index . '.quantity', $quantity);
                    break;
                }
            }

            //product_idが同一ではない状態を指定 その場合であればpushする
            if ($isSameProductId === false) {
                //  $request->session()->push('cart', $cartData);
            }
        }

        //POST送信された情報をsessionに保存 'survey_id'(key)に$request内の'survey_id'をセット
        $request->session()->put('survey_id', ($request->survey_id));

        //return redirect()->back();

        $session_cart = Session::get('cart');
        $items = '<a class="text-decoration-none text-center w-100 text-black-50 pt-2 close-cart" style="cursor:pointer;">
                    <h4>X</h4>
                </a>
                <div class="col py-3">';
        $units = 0;
        if ($session_cart) {
            foreach ($session_cart as $key => $each) {
                $units = $units + $each['quantity'];
                $items .= '<div class="row">
                                <div class="col-2">
                                    <img src="../' . $each['img'] . '" class ="cart_img">
                                </div>
                                <div class="col-6">
                                    ' . $each['products'] . " (" . $each['price'] . "円) x " . $each['quantity'] . '
                                </div>
                                <div class="col-4">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" class="update-quantity-left-minus btn btn-outline-primary btn-number" data-type="minus" data-field="" data-key="' . $each['id'] . '">
                                                <span class="glyphicon glyphicon-minus">-</span>
                                            </button>
                                        </span>
                                        <input type="text" id="quantity_' . $each['id'] . '" name="quantity_' . $each['id'] . '" class="form-control input-number" value="' . $each['quantity'] . '" min="1" max="100" style="text-align: center">
                                        <span class="input-group-btn">
                                            <button type="button" class="update-quantity-right-plus btn btn-outline-primary btn-number" data-type="plus" data-field="" data-key="' . $each['id'] . '">
                                                <span class="glyphicon glyphicon-plus">+</span>
                                            </button>
                                        </span>
                                    </div>

                                </div>
                            </div>

                            <hr>';
            }
            if ($units > 0) {
                if (empty(Session::get('customer_id'))) {
                    $items .= '<div class="col-12 text-center">
                            <button id="login"  class="btn btn-sm btn-quanto order-button" data-bs-toggle="modal" data-bs-target="#loginModal">注文手続きへ</button>
                        </div>';
                } else {
                    $items .= '<div class="col-12 text-center">
                            <a href="../checkout" class="btn btn-sm btn-quanto order-button">注文手続きへ</a>
                        </div>';
                }
            }
        } else {

            $items .= ' <div class="col-12 text-center">
                            <p class="text-center">カートは空です。</p>
                        </div>';
        }

        $items .= ' </div>';

        $cart = '<div class="col">
                    <div class="items_count text-light" id="items_count">';


        $cart .= $units . ' 点';

        $cart .= '</div>
                </div>
                <div class="col">
                    <a class="btn view-card-btn mt-1">カートを見る</a>
                </div>
                <div class="col">
                    <div class="total_result text-light" id="total_result">';
        if ($session_cart) {
            $total = 0;
            foreach ($session_cart as $key => $count) {
                $total += ($count['price'] * $count['quantity']);
            }
            $cart .= number_format($total) . ' 円';
        } else {
            $cart .= 0 . ' 円';
        }
        $cart .= ' </div>
                </div>';
        return response()->json([
            'status' => 200,
            'list' => $items,
            'cart' => $cart
        ]);
    }

    public function checkout(Request $request)
    {

        $query = Survey::where('id', Session::get('survey_id'))->first();

        if (empty($query)) {
            return back();
        }
        if (empty(Session::get('cart'))) {
            return redirect('show/' . $query->token);
        }
        if (!$request->session()->has('customer_id')) {
            return redirect('show/' . $query->token);
        } else {

            if ($customer = Customer::where('id', Session::get('customer_id'))->first()) {
                if ($customer->remember_token != Session::get('customer_token')) {
                    Session::pull('customer_id');
                    Session::pull('customer_token');
                    return redirect('show/' . $query->token);
                } else {
                    $addresses = Address::where('customer_id', Session::get('customer_id'))->orderBy('status', 'desc')->get();
                    $cards = Card::where('customer_id', Session::get('customer_id'))->orderBy('status', 'desc')->get();
                }
            } else {
                return redirect('show/' . $query->token);
            }
        }


        $session_cart = Session::get('cart');

        return view('frontend.checkout', compact('query', 'session_cart', 'customer', 'addresses', 'cards'));
    }

    public function updateCheckout(Request $request)
    {

        $cartData = [
            'id' => $request->product_id,
            'quantity' => $request->product_q,
        ];

        //sessionにcartData配列が「無い」場合、商品情報の配列をcartData(key)という名で$cartDataをSessionに追加
        if (!$request->session()->has('cart')) {
            // $request->session()->push('cart', $cartData);
        } else {
            //sessionにcartData配列が「有る」場合、情報取得
            $sessionCartData = $request->session()->get('cart');

            //isSameProductId定義 product_id同一確認フラグ false = 同一ではない状態を指定
            $isSameProductId = false;
            foreach ($sessionCartData as $index => $sessionData) {
                //product_idが同一であれば、フラグをtrueにする → 個数の合算処理、及びセッション情報更新。更新は一度のみ
                if ($sessionData['id'] === $cartData['id']) {
                    $isSameProductId = true;
                    $quantity = $cartData['quantity'];
                    //cartDataをrootとしたツリー状の多次元連想配列の特定のValueにアクセスし、指定の変数でValueの上書き処理
                    $request->session()->put('cart.' . $index . '.quantity', $quantity);
                    break;
                }
            }

            //product_idが同一ではない状態を指定 その場合であればpushする
            if ($isSameProductId === false) {
                //  $request->session()->push('cart', $cartData);
            }
        }

        //POST送信された情報をsessionに保存 'survey_id'(key)に$request内の'survey_id'をセット
        $request->session()->put('survey_id', ($request->survey_id));

        //return redirect()->back();

        $session_cart = Session::get('cart');
        $items = '<div class="col">';
        $units = 0;
        if ($session_cart) {
            foreach ($session_cart as $key => $each) {
                if ($each['quantity'] > 0) {
                    $units = $units + $each['quantity'];
                    $items .= '        <div class="row">
                                    <div class="col">'
                        . $each['products'] . ' x ' . $each['quantity'] . '
                                    </div>
                                    <div class="col">


                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button type="button" class="update-quantity-left-minus btn btn-outline-primary btn-number" data-type="minus" data-field="" data-key="' . $each['id'] . '">
                                                    <span class="glyphicon glyphicon-minus"></span>
                                                </button>
                                            </span>
                                            <input type="text" id="quantity_' . $each['id'] . '" name="quantity_' . $each['id'] . '" class="form-control input-number" value="' . $each['quantity'] . '" min="1" max="100" style="text-align: center">
                                            <span class="input-group-btn">
                                                <button type="button" class="update-quantity-right-plus btn btn-outline-primary btn-number" data-type="plus" data-field="" data-key="' . $each['id'] . '">
                                                    <span class="glyphicon glyphicon-plus"></span>
                                                </button>
                                            </span>
                                        </div>

                                    </div>
                                </div>

                                <hr>';
                }
            }
        }
        $items .= '    <div class="row text-center item-line" style="max-width: 450px; margin: 0 auto; background-color: #6962FF; padding: 12px; border-radius: 13px">
                                                       <div class="col text-light d-none d-sm-block">トータル</div>
        <div class="col">

                                <div class="items_count text-light" id="items_count text-nowrap">';


        $items .= $units . ' 点';


        $items .= '              </div>
                            </div>
                            <div class="col">

                            </div>
                            <div class="col">
                                <div class="total_result text-light" id="total_result text-nowrap">';

        if ($session_cart) {
            $total = 0;
            foreach ($session_cart as $key => $count) {
                $total += ($count['price'] * $count['quantity']);
            }
            $items .= number_format($total) . ' 円';
        } else {
            $items .= 0 . ' 円';
        }

        $items .= '          </div>
                            </div>
                        </div>

                    </div>';

        if ($session_cart) {
            $total = 0;
            foreach ($session_cart as $key => $count) {
                $total += ($count['price'] * $count['quantity']);
            }
            $cart = $total;
        } else {
            $cart = 0;
        }
        return response()->json([
            'status' => 200,
            'list' => $items,
            'units' => $units,
            'cart' => $cart
        ]);
    }

    public function login(Request $request)
    {

        $status = 400;
        if ($query = Customer::where('email', $request->loginEmail)->first()) {
            $hashedPassword = $query->password;
            if (Hash::check($request->loginPassword, $hashedPassword)) {

                $unique = Str::random(60);

                $customer = Customer::find($query->id);
                $customer->remember_token = $unique;
                $customer->save();

                $request->session()->put('customer_id', $query->id);
                $request->session()->put('customer_token', $unique);
                $request->session()->put('customer_name', $query->name);
                $status = 200;
            }
        }

        return response()->json([
            'status' => $status,
        ]);
    }

    public function register(Request $request)
    {
        $status = 400;
        $emailError = '';
        $passwordError = '';
        $retypeError = '';
        $nameError = '';
        $kanaError = '';

        if (empty($request->registerEmail)) {
            $emailError = 'メールアドレスを入力してください。<br>';
            $status = 400;
        } else {
            if (filter_var($request->registerEmail, FILTER_VALIDATE_EMAIL)) {
                if (Customer::where('email', $request->registerEmail)->first()) {
                    $emailError = '既に登録されているメールアドレスです。<br>';
                    $status = 400;
                } else {
                    $status = 200;
                }
            } else {
                $emailError = 'メールアドレスが正しくありません。';
                $status = 400;
            }
        }
        if (empty($request->registerPassword)) {
            $passwordError = 'パスワードを入力してください。<br>';
            $status = 400;
        } else {
            if (empty($request->retypePassword)) {
                $retypeError = '再度パスワードを入力してください。<br>';
                $status = 400;
            } else {
                if ($request->registerPassword == $request->retypePassword) {
                } else {
                    $retypeError = '入力されたパスワードと再入力のパスワードが一致していません。<br>';
                    $status = 400;
                }
            }
        }
        if (empty($request->registerName)) {
            $nameError = 'お名前を入力してください。<br>';
            $status = 400;
        }
        if (empty($request->registerKana)) {
            $kanaError = 'フリガナを入力してください。<br>';
            $status = 400;
        }

        if ($status == 200) {

            $unique = Str::random(60);

            $customer = new Customer;
            $customer->name = Str::replace('  ', ' ', $request->registerName);
            $customer->kana = Str::replace('  ', ' ', $request->registerKana);
            $customer->email = $request->registerEmail;
            $customer->password = Hash::make($request->registerPassword);
            $customer->remember_token = $unique;
            $response = $customer->save();

            if ($response) {
                $status = 200;
                $request->session()->put('customer_id', $customer->id);
                $request->session()->put('customer_token', $unique);
                $request->session()->put('customer_name', $customer->name);
            } else {
                $status = 400;
            }
        }

        return response()->json([
            'status' => $status,
            'emailError' => $emailError,
            'passwordError' => $passwordError,
            'retypeError' => $retypeError,
            'nameError' => $nameError,
            'kanaError' => $kanaError,
        ]);
    }

    public function logout(Request $request)
    {
        $query = Survey::where('token', $request->token)->first();
        if (empty($query)) {
            return redirect(301);
        }
        Session::pull('customer_id');
        Session::pull('customer_token');
        Session::pull('customer_name');

        return redirect("show/" . $request->token);
    }

    public function forget(Request $request)
    {

        $status = 400;
        if ($customer = Customer::where('email', $request->resetEmail)->first()) {

            $unique = Str::random(60);
            $customer->remember_token = $unique;
            $customer->save();

            $status = 200;

            $url = "https://" . request()->getHost() . "/customer/reset/" . $request->survey . "?id=" . $customer->id . "&token=" . $unique;

            $email = Mail::where('name', 'パスワード再設定メール')->first();

            $content = nl2br($email->content);
            $content = Str::replace('[[name]]', $customer->name, $content);
            $content = Str::replace('[[url]]', $url, $content);

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
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            //If SMTP requires TLS encryption then set it
            $mail->SMTPSecure = 'ssl';
            //Set TCP port to connect to 
            $mail->Port = env('MAIL_PORT');

            //From email address and name
            $mail->From = env('MAIL_FROM_ADDRESS');
            $mail->FromName = env('MAIL_FROM_NAME');

            //To address and name
            $mail->addAddress($customer->email, $customer->name);
            // $mail->addAddress("recepient1@example.com"); //Recipient name is optional

            //Address to which recipient will reply
            $mail->addReplyTo('hosokawa@quanto.xbiz.jp', "Reply");

            //CC and BCC
            // $mail->addCC("cc@example.com");
            // $mail->addBCC("bcc@example.com");

            //Send HTML or Plain Text email
            $mail->isHTML(true);

            $mail->Subject = $email->subject;
            $mail->Body = $content;
            $mail->AltBody = $content;

            //Attachments
            //$mail->addAttachment("file.txt", "File.txt");
            // $mail->addAttachment("images/profile.png"); //Filename is optional


            if (!$mail->send()) {
                //  Log::info("Mailer Error: " . $mail->ErrorInfo);
                //echo "Mailer Error: " . $mail->ErrorInfo;
                //return;
            }

        }

        return response()->json([
            'status' => $status,
        ]);
    }

    public function reset(Request $request, $id)
    {
        $query = Survey::where('token', $id)->first();
        if (empty($query)) {
            return redirect(301);
        }
        $id = $request->get('id');
        $token = $request->get('token');
        $customer = Customer::where('id', $id)->
            where('remember_token', $token)->first();
        if (empty($customer)) {
            $status = 400;
        } else {
            $status = 200;
        }

        return view('frontend.reset', compact('query', 'status', 'customer'));

    }

    public function password(Request $request)
    {

        $id = $request->id;
        $token = $request->token;
        $password = Hash::make($request->password);
        $customer = Customer::where('id', $id)->where('remember_token', $token)->first();
        if (empty($customer)) {
            $status = 400;
        } else {
            $customer->password = $password;
            $customer->remember_token = Str::random(60);
            $customer->update();
            $status = 200;
        }

        return response()->json([
            'status' => $status,
        ]);
    }

    public function address()
    {
    }

    public function card()
    {
    }

    public function postcode(Request $request)
    {
        $status = 400;
        $postcodeError = '';
        $prefecture = '';
        $city = '';
        $street = '';
        $zipcode = '';
        $postalCode = $request->postcodeFirst . $request->postcodeLast;
        $url = 'http://zipcloud.ibsnet.co.jp/api/search?zipcode=' . $postalCode;

        $resJson = file_get_contents($url);
        $resJson = mb_convert_encoding($resJson, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $res = json_decode($resJson, true);

        if ($res['results']) {
            $prefecture = $res['results'][0]['address1'];
            $city = $res['results'][0]['address2'];
            $street = $res['results'][0]['address3'];
            $zipcode = $res['results'][0]['zipcode'];
        }

        if (empty($prefecture)) {
            $postcodeError = '郵便番号が正しくありません。';
        } else {
            $status = 200;
        }

        return response()->json([
            'status' => $status,
            'postcodeError' => $postcodeError,
            'prefecture' => $prefecture,
            'city' => $city,
            'street' => $street,
            'zipcode' => $zipcode,
        ]);
    }

    public function mypage(Request $request, $id)
    {
        $query = Survey::where('token', $id)->first();
        if (empty($query)) {
            return back();
        }
        if (!$request->session()->has('customer_id')) {
            return redirect('show/' . $query->token);
        }
        $customer = Customer::find($request->session()->get('customer_id'));

        $address = Address::where('customer_id', $customer->id)
            ->where('type', 1)
            ->where('status', 1)->first();

        $addresses = Address::where('customer_id', $customer->id)->where('status', 1)->get();

        $search = $request->get('search');
        $range = $request->get('range');

        if (Str::startsWith($search, 'Q')) {
            $parts = Str::of($search)->explode('-');
            if (count($parts) > 1) {
                $search = $parts[1];
            }
        }

        if (empty($search) && empty($range)) {
            $orders = Order::where('customer_id', $customer->id)->orderBy('id', 'DESC')->simplePaginate(20);
        } else {
            if (empty($range)) {
                $startDate = "2020-01-01 00:00  ";
                $endDate = date("Y-m-d H:i:s");
            } else {
                $startDate = Str::substr($range, 0, 10) . " 00:00:00";
                $endDate = Str::substr($range, 13, 23) . " 00:00:00";
            }
            $orders = Order::where([['id', 'LIKE', "%{$search}%"], ['customer_id', $customer->id], ['created_at', '>', $startDate], ['created_at', '<', $endDate]])
                ->orwhere([['name', 'LIKE', "%{$search}%"], ['customer_id', $customer->id], ['created_at', '>', $startDate], ['created_at', '<', $endDate]])
                ->orwhere([['email', 'LIKE', "%{$search}%"], ['customer_id', $customer->id], ['created_at', '>', $startDate], ['created_at', '<', $endDate]])
                ->orwhere([['survey_title', 'LIKE', "%{$search}%"], ['customer_id', $customer->id], ['created_at', '>', $startDate], ['created_at', '<', $endDate]])
                ->orderBy('id', 'DESC')->simplePaginate(20);
            $orders->appends($request->all());
        }

        return view('frontend.mypage', compact('query', 'customer', 'address', 'addresses', 'orders'));
    }

    public function get(Request $request)
    {

        $order = Order::where('id', $request->get('order_id'))
            ->where('customer_id', $request->session()->get('customer_id'))->first();
        $products = Product::where('order_id', $request->get('order_id'))->get();

        if (empty($order)) {
            $status = 400;
        } else {
            $status = 200;
        }

        $items = "";

        foreach ($products as $product) {
            $items .= "<p> $product->product_name ($product->price円) X $product->units点 = " . number_format($product->price * $product->units) . "円</p>";
        }

        return response()->json([
            'status' => $status,
            'order' => $order,
            'items' => $items,
            'total' => number_format($order->amount) . ' 円'
        ]);
    }

    public function info(Request $request)
    {
        $status = 400;
        $customer_id = 0;
        if (!empty(session()->get('customer_id'))) {
            $status = 200;
            $customer_id = session()->get('customer_id');
        }

        return response()->json([
            'status' => $status,
            'customer_id' => $customer_id,
        ]);
    }

    public function updateCustomer(Request $request)
    {
        $status = 400;
        $emailError = '';
        $passwordError = '';
        $retypeError = '';
        $nameError = '';
        $kanaError = '';

        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            if (Customer::where('id', '<>', $request->customer_id)->where('email', $request->email)->first()) {
                $emailError = '既に登録されているメールアドレスです。<br>';
                $status = 400;
            } else {
                $status = 200;
            }
        } else {
            $emailError = 'メールアドレスが正しくありません。';
            $status = 400;
        }

        if ($status == 200) {
            $customer = Customer::find($request->customer_id);
            if ($customer->address != $request->address) {
                $address = Address::where('customer_id', $request->customer_id)->where('type', 0)->first();
                if ($address) {
                    $address->type = 1;
                    $address->save();
                }
            }

            $customer->email = $request->email;
            $customer->postcode = $request->postcode;
            $customer->address = $request->address;
            $customer->phone = $request->phone;
            $response = $customer->save();
            if ($response) {
                $status = 200;
            } else {
                $status = 400;
            }
        }

        return response()->json([
            'status' => $status,
            'emailError' => $emailError,
            'passwordError' => $passwordError,
            'retypeError' => $retypeError,
            'nameError' => $nameError,
            'kanaError' => $kanaError,
        ]);
    }

    public function saveAddress(Request $request)
    {
        $status = 400;
        $emailError = '';
        $passwordError = '';
        $retypeError = '';
        $nameError = '';
        $kanaError = '';

        $customer = Customer::find($request->customer_id);
        $address = new Address;
        if ($request->address_id and $request->address_id != 0) {
            $address = Address::find($request->address_id);
        }

        $address->customer_id = $customer->id;
        $address->phone = $request->phone;
        $address->name = $request->address_name;
        $address->kana = $request->address_kana;
        $address->postcode = $request->address_postcode;
        $address->address = $request->address;
        $address->address2 = $request->address2;
        $address->address3 = $request->address3;
        $address->type = $request->type;
        $address->status = 1;
        $response = $address->save();
        if ($status = 200 and $response) {
            $status = 200;
        } else {
            $status = 400;
        }

        if ($request->type == 0) {
            $customer->postcode = $request->address_postcode;
            $customer->address = $request->address . ' ' . $request->address2 . ' ' . $request->address3;
            $customer->phone = $request->phone;
            $customer->name = $request->address_name;
            $customer->save();
        }

        return response()->json([
            'status' => $status,
            'emailError' => $emailError,
            'passwordError' => $passwordError,
            'retypeError' => $retypeError,
            'nameError' => $nameError,
            'kanaError' => $kanaError,
        ]);
    }

    public function deleteAddress(Request $request)
    {
        $status = 400;
        $emailError = '';
        $passwordError = '';
        $retypeError = '';
        $nameError = '';
        $kanaError = '';

        $response = Address::find($request->address_id)->delete();
        if ($status = 200 and $response) {
            $status = 200;
        } else {
            $status = 400;
        }

        return response()->json([
            'status' => $status,
            'emailError' => $emailError,
            'passwordError' => $passwordError,
            'retypeError' => $retypeError,
            'nameError' => $nameError,
            'kanaError' => $kanaError,
        ]);
    }

    public function saveSameAddress(Request $request)
    {
        $status = 400;
        $emailError = '';
        $passwordError = '';
        $retypeError = '';
        $nameError = '';
        $kanaError = '';

        $address = Address::find($request->address_id);
        $customer = Customer::find($request->customer_id);
        if ($address and $customer) {
            $status = 200;
            $customer->postcode = $address->postcode;
            $customer->address = $address->address . ' ' . $address->address2 . ' ' . $address->address3;
            $customer->phone = $address->phone;
            $customer->name = $address->name;
            $customer->save();

            $address->type = 0;
            $address->save();
        }

        return response()->json([
            'status' => $status,
            'emailError' => $emailError,
            'passwordError' => $passwordError,
            'retypeError' => $retypeError,
            'nameError' => $nameError,
            'kanaError' => $kanaError,
        ]);
    }
}