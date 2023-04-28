<?php

namespace App\Http\Controllers;

use App\Models\FollowClient;
use App\Models\SaveItem;
use App\Models\Survey;
use App\Models\UserProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PDF;

class AppApiController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $user = User::where('email', $email)->first();

        if ($user == null) {
            $rlt = $this->convertUser2AppData(null);
            $rlt['status'] = 'invalidEmail';
            return response()->json($rlt);
        }

        if (Hash::check($password, $user->password) == false) {
            $rlt = $this->convertUser2AppData(null);
            $rlt['status'] = 'invalidPassword';
            return response()->json($rlt);
        }

        $rlt = $this->convertUser2AppData($user);
        $rlt['status'] = 'successful';
        return response()->json($rlt);
    }

    public function register(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $password = Hash::make($password);
        $name = $request->get('name');
        $full_name = $request->get('full_name');
        $address = $request->get('address');
        $zip_code = $request->get('zip_code');
        $phone_number = $request->get('phone_number');
        $profile_img = $request->get('profile_url');

        $user = User::where('email', $email)->first();
        if ($user != null) {
            return response()->json([
                'status' => 'existEmail',
            ]);
        }

        $img_url = "uploads/users/" . microtime(true) . '.png';

        file_put_contents($img_url, base64_decode($profile_img));
        $profile_url = '/' . $img_url;

        $user = new User();
        $user->email = $email;
        $user->password = $password;
        $user->name = $name;
        $user->full_name = $full_name;
        $user->address = $address;
        $user->zip_code = $zip_code;
        $user->phone_number = $phone_number;
        $user->profile_url = $profile_url;

        $user->save();

        return response()->json([
            'status' => 'successful',
        ]);
    }

    public function getVerifyCode(Request $request)
    {

        $email = $request->get('email');

        $user = User::where('email', $email)->first();
        if ($user == null) {
            return response()->json([
                'status' => 'invalidEmail',
                'verifyCode' => '0000'
            ]);
        }

        $length = 4;
        $verifyCode = substr(str_shuffle('01234567890123456789012345678901234567890123456789'), 3, $length);

        $user->verifyCode = $verifyCode;
        $user->save();

        // Mail::send(
        //     [],
        //     [],
        //     function ($message) {
        //         $message->to($email)
        //             ->subject('Reset Password')
        //             ->setBody('<h1>' . $verifyCode . '</h1>', 'text/html');
        //     }
        // );

        return response()->json([
            'status' => 'successful',
            'verifyCode' => $verifyCode,
        ]);
    }

    public function verifyCode(Request $request)
    {
        $email = $request->get('email');
        $verifyCode = $request->get('verifyCode');

        $user = User::where('email', $email)->first();
        if ($user == null) {
            return response()->json([
                'status' => 'invalidEmail'
            ]);
        }

        if (strlen($user->verifyCode) < 4 || $user->verifyCode != $verifyCode) {
            return response()->json([
                'status' => 'invalidCode',
            ]);
        }

        $resetPasswordToken = Str::random(60);
        $user->verifyCode = '';
        $user->remember_token = $resetPasswordToken;

        $user->save();

        return response()->json([
            'status' => 'successful',
            'resetPasswordToken' => $resetPasswordToken
        ]);
    }


    public function resetPassword(Request $request)
    {
        $email = $request->get('email');
        $resetPasswordToken = $request->get('resetPasswordToken');
        $password = $request->get('password');

        $user = User::where('email', $email)->where('remember_token', $resetPasswordToken)->first();
        if ($user == null) {
            return response()->json([
                'status' => 'invalidEmail',
            ]);
        }

        $user->password = Hash::make($password);
        $user->save();


        return response()->json([
            'status' => 'successful',
        ]);
    }

    function convertUser2AppData($model)
    {

        if ($model == null) {

            return [
                'id' => 0,
                'name' => '',
                'full_name' => '',
                'email' => '',
                'zip_code' => '',
                'address' => '',
                'phone_number' => '',
                'profile_url' => '',
            ];
        }

        $profile_url = null;
        if ($model->profile_url != null)
            $profile_url = url('/') . $model->profile_url;

        return [
            'id' => $model->id,
            'name' => $model->name,
            'full_name' => $model->full_name,
            'email' => $model->email,
            'zip_code' => $model->zip_code,
            'address' => $model->address,
            'phone_number' => $model->phone_number,
            'profile_url' => $profile_url,
        ];
    }

    function getUserList()
    {
        $listModel = User::all();

        $rlt = [];
        foreach ($listModel as $i => $model) {
            $rlt[$i] = $this->convertUser2AppData($model);
        }

        return response()->json($rlt);
    }

    function getUserListByBuyer_id(Request $request)
    {

        $buyer_id = $request->get('buyer_id');

        $listModel = FollowClient::where('buyer_id', $buyer_id)->where('type', 0)->orderBy('agreeState', 'desc')->orderBy('updated_at', 'asc')->get();

        $checkData = [];
        foreach ($listModel as $model) {
            $supplyer_id = $model->supplyer_id;
            $checkData[$supplyer_id]['agree'] = $model->agreeState;
        }

        $listModel = User::all();

        $rlt = [];
        $i = 0;
        foreach ($listModel as $model) {
            $i = count($rlt);
            $supplyer_id = $model->id;
            if (isset($checkData[$supplyer_id]) == false)
                continue;

            $rlt[$i] = $this->convertUser2AppData($model);

            $rlt[$i]['supplyed'] = true;
            $rlt[$i]['supplyAccepted'] = false;

            if ($checkData[$supplyer_id]['agree'] != 0)
                $rlt[$i]['supplyAccepted'] = true;
        }

        foreach ($listModel as $model) {
            $i = count($rlt);
            $supplyer_id = $model->id;
            if (isset($checkData[$supplyer_id]) == true)
                continue;

            $rlt[$i] = $this->convertUser2AppData($model);

            $rlt[$i]['supplyed'] = false;
            $rlt[$i]['supplyAccepted'] = false;
        }

        return response()->json($rlt);
    }

    function setSupplyerFollow(Request $request)
    {

        $buyer_id = $request->get('buyer_id');
        $supplyer_id = $request->get('supplyer_id');

        $model = FollowClient::where('buyer_id', $buyer_id)->where('supplyer_id', $supplyer_id)->where('type', 0)->first();
        if ($model == null) {
            $model = new FollowClient();
        } else {
            return response()->json([
                'status' => 'alreadyFollow'
            ]);
        }

        $model->buyer_id = $buyer_id;
        $model->supplyer_id = $supplyer_id;
        $model->type = 0;
        $model->save();

        return response()->json([
            'status' => 'successful'
        ]);
    }

    function unsetSupplyerFollow(Request $request)
    {

        $buyer_id = $request->get('buyer_id');
        $supplyer_id = $request->get('supplyer_id');

        $listModel = FollowClient::where('buyer_id', $buyer_id)->where('supplyer_id', $supplyer_id)->where('type', 0)->get();

        foreach ($listModel as $model) {
            $model->delete();
        }

        return response()->json([
            'status' => 'successful'
        ]);
    }


    function getFollowListBySupplyer_id(Request $request)
    {

        $supplyer_id = $request->get('supplyer_id');

        $listModel = FollowClient::where('supplyer_id', $supplyer_id)->where('type', 0)->get();

        $rlt = [];
        foreach ($listModel as $i => $model) {
            $rlt[$i] = $this->convertUser2AppData($model->buyer);

            $rlt[$i]['isAccepted'] = $model->agreeState == 1 ? true : false;
        }
        return response()->json($rlt);
    }

    function setFollowAccept(Request $request)
    {


        $buyer_id = $request->get('buyer_id');
        $supplyer_id = $request->get('supplyer_id');

        $model = FollowClient::where('buyer_id', $buyer_id)->where('supplyer_id', $supplyer_id)->where('type', 0)->first();
        if ($model == null) {
            return response()->json([
                'status' => 'failed'
            ]);
        }

        $model->agreeState = 1;
        $model->save();

        return response()->json([
            'status' => 'successful'
        ]);
    }





    function convertModel2AppData($model, $user_id)
    {
        if ($model == null) {

            return [
                'id' => 0,
                'name' => '',
                'brandName' => '',
                'category' => '',
                'sku' => '',
                'price' => 0,
                'amount' => 0,
                'color' => '',
                'size' => '',
                'material' => '',
                'detail' => '',
                'img_url' => '',
                'img_urls' => [],
                'barcode' => '',
                'other' => '',
                'isSaved' => false,
                'isFavorite' => false
            ];
        }

        $isSaved = false;
        $isFavorite = false;
        if (count(SaveItem::where('user_id', $user_id)->where('product_id', $model->id)->get()) > 0) {
            $isSaved = true;
        }
        return [
            'id' => $model->id,
            'name' => $model->name,
            'brandName' => $model->brandName,
            'category' => $model->getCategoryText(),
            'sku' => $model->sku,
            'price' => $model->price,
            'amount' => $model->stock,
            'color' => $model->userProductColor->name,
            'size' => $model->userProductSize->name,
            'material' => $model->getMaterialsText(),
            'detail' => $model->detail,
            'img_url' => $model->getImageUrlFirstFullPath(),
            'img_urls' => $model->getImageUrlsFullPath(),
            'barcode' => $model->barcode,
            'other' => '',
            'isSaved' => $isSaved,
            'isFavorite' => $isFavorite
        ];
    }

    public function getProductList(Request $request)
    {
        $user_id = $request->get('user_id');
        // $listModel = UserProduct::where('user_id', $user_id)->get();
        $listModel = UserProduct::all();

        $rlt = array();
        foreach ($listModel as $i => $model) {
            $rlt[$i] = $this->convertModel2AppData($model, $user_id);
        }

        return response()->json($rlt);
    }

    public function getProductByBarcode(Request $request)
    {
        $user_id = $request->get('user_id');
        $barcode = $request->get('barcode');
        $user_type = $request->get('user_type');

        $model = UserProduct::where('barcode', $barcode);

        if ($user_type == 'seller') {
            $model = $model->where('user_id', $user_id);
        }

        $model = $model->first();

        if (strlen($barcode) < 3) {
            $model = null;
        }

        return response()->json($this->convertModel2AppData($model, $user_id));
    }

    public function getSaveItemList(Request $request)
    {
        $user_id = $request->get('user_id');
        $listModel = SaveItem::where('user_id', $user_id)->get();
        $rlt = array();
        foreach ($listModel as $i => $model) {
            $rlt[$i] = $this->convertModel2AppData($model->userProduct, $user_id);
        }

        return response()->json($rlt);
    }

    public function addSaveItem(Request $request)
    {
        $user_id = $request->get('user_id');
        $product_id = $request->get('product_id');

        $model = SaveItem::where('user_id', $user_id)->where('product_id', $product_id)->first();

        if ($model != null) {
            return response()->json([
                'status' => 'alreadyExist'
            ]);
        }
        $model = new SaveItem();
        $model->user_id = $user_id;
        $model->product_id = $product_id;
        $model->save();

        return response()->json([
            'status' => 'successful'
        ]);
    }

    public function makeInvoice(Request $request)
    {

        $user_id = 2;
        $survey_id = '9MRO7H6RZVNVaTjsqZ7b';
        $date = "2022/03/08";
        $expire = "2022/03/10";
        $invoiceUserName = "user1"; //user name

        $user = User::findOrFail($user_id);
        $survey = Survey::where('token', $survey_id)->first();
        $date = date('Y年n月j日', strtotime($date));
        $expire = date('Y年n月j日', strtotime($expire));

        $invoiceNumber = "Q1002-38-11";

        $invoiceData = $request->json()->all();

        $totalMoney = 0;
        foreach ($invoiceData as $item) {
            $totalMoney += $item['price'] * $item['amount'];
        }

        $invoicePDF = PDF::loadView(
            'invoicePDF',
            compact(
                'user',
                'invoiceUserName',
                'survey',
                'date',
                'expire',
                'invoiceNumber',
                'invoiceData',
                'totalMoney'
            )
        );

        // return $invoicePDF->stream();
        $fileName = microtime(true) . ".pdf";
        $filePath = url("/uploads/products") . '/' . $fileName;

        $invoicePDF->save(base_path("uploads/products") . '/' . $fileName);

        return response()->json([
            'name' => 'invoice',
            'pdf_url' => $filePath,
        ]);


    }
}