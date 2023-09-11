<?php

namespace App\Http\Controllers;

use App\Models\FollowClient;
use App\Models\Paper;
use App\Models\SaveItem;
use App\Models\Survey;
use App\Models\UserProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyMail;
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

        $mail_body = $verifyCode;

        $to_name = '';
        $data = array('name' => $to_name, "body" => $mail_body);
        Mail::to($email, $to_name)->send(new VerifyMail($data));

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
            'color' => $model->getOptionsText($user_id),
            'size' => '',
            'material' => '',
            // 'color' => $model->userProductColor->name,
            // 'size' => $model->userProductSize->name,
            // 'material' => $model->getMaterialsText(),
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

        $datas = $request->json()->all();

        $user_id = $datas["userId"];
        $survey_id = '9MRO7H6RZVNVaTjsqZ7b';


        $date = date("Y年n月j日");
        $expire = date("Y年n月j日", strtotime(' +1 day'));

        $user = User::findOrFail($user_id);
        $invoiceUserName = $user->name; //user name
        $survey = Survey::where('token', $survey_id)->first();
        // $date = date('Y年n月j日', strtotime($date));
        // $expire = date('Y年n月j日', strtotime($expire));

        $invoiceNumber = "Q1002-38-11";

        $invoiceData = $datas["invoiceData"];

        $totalMoney = 0;
        $totalCount = 0;
        foreach ($invoiceData as $item) {
            $totalMoney += $item['price'] * $item['amount'];
            $totalCount += $item['amount'];
        }

        $jsonInvoiceData = [];
        $tModel = new UserProduct();
        $allCategories = $tModel->getAllOptionNames($user->id);

        $jsonInvoiceData["purpose_1"] = "ご請求書";
        $jsonInvoiceData["cDate"] = $date;
        $jsonInvoiceData["uName"] = "";
        $jsonInvoiceData["profile"] = $user->profile_url;
        $jsonInvoiceData["serial"] = "請求書No,Q" . ($user->id + 1000). "-----------";
        $jsonInvoiceData["company"] = $user->full_name;
        $jsonInvoiceData["display_total_price"] = $totalMoney;
        $jsonInvoiceData["display_reduce"] = round($totalMoney * 0.1);
        $jsonInvoiceData["zipCode"] = $user->zip_code;
        $jsonInvoiceData["adress"] = $user->address;
        $jsonInvoiceData["phone"] = $user->phone_number;
        $jsonInvoiceData["stamp"] = json_decode($user->settings)->stamp_url ?? "";
        $jsonInvoiceData["eDate"] = $expire;
        
        $adminHost = Config::get('constants.adminHost');
        $jsonInvoiceData["row_0"] = "$adminHost/img/ic_add.png";
        $jsonInvoiceData["link_0"] = "$adminHost/img/ic_link.png";
        $jsonInvoiceData["del_0"] = "$adminHost/img/ic_delete.png";
        $jsonInvoiceData["tooltip_edit_0"] = "$adminHost/img/edit_query_m.png";
        $jsonInvoiceData["tooltip_close_0"] = "$adminHost/img/ic_modal_close.png";

        foreach ($invoiceData as $key => $p) {
            $pId = $p["id"];
            $product = UserProduct::findOrFail($pId);
            $jsonInvoiceData["ID_$key"] = $product->getProductID();
            $jsonInvoiceData["productNum_$key"] = $product->id;
            $jsonInvoiceData["timg_$key"] = "$adminHost/" . $product->getImageUrlFirst();
            $jsonInvoiceData["title_$key"] = $product->name;
            $crtOptions = $product->getOptions2($user->id);
            foreach($allCategories as $k => $c) {
                $jsonInvoiceData["subt_" . $k . "_$key"] = $crtOptions[$k] ?? "";
            }


            $jsonInvoiceData["price_$key"] = $p["price"];
            $jsonInvoiceData["quantity_$key"] = $p["amount"];
            $jsonInvoiceData["current_price_$key"] = $p["price"] * $p["amount"];
            $jsonInvoiceData["reduce_pro_$key"] = 10;
            $jsonInvoiceData["reduce_plus_$key"] = round($p["price"] * $p["amount"] * 0.1);
        }

        $jsonInvoiceData["total_count"] = $totalCount;
        $jsonInvoiceData["total_price"] = $totalMoney;
        $jsonInvoiceData["reduce_price"] = round($totalMoney * 0.1);
        $jsonInvoiceData["memo_text"] = "";
        $jsonInvoiceData["totalAmount10"] = $totalMoney;
        $jsonInvoiceData["totalAmount10s"] = round($totalMoney * 0.1);
        $jsonInvoiceData["totalAmount88"] = 0;
        $jsonInvoiceData["totalAmount88s"] = 0;
        $jsonInvoiceData["totalAmount8"] = 0;
        $jsonInvoiceData["totalAmount8s"] = 0;

        $jsonInvoiceData["img_upload_url_1"] = "$adminHost/public/img/blank-plus.png";
        $jsonInvoiceData["item_productID"] = "Q00066088";
        $jsonInvoiceData["item_price"] = "81,728";
        $jsonInvoiceData["item_like"] = "NOLIKE";
        $jsonInvoiceData["item_h_name"] = "";
        $jsonInvoiceData["item_id"] = "88";
        $jsonInvoiceData["item__44Kr44Op44O8"] = "";
        $jsonInvoiceData["item__57Sg5p2Q"] = "";

        $jsonInvoiceData["img_upload_img"] = "$adminHost/public/img/blank.png";
        $jsonInvoiceData["img_upload_url"] = "blank.png";
        $jsonInvoiceData["hostUrl"] = "https://quanto3.com";
        $jsonInvoiceData["rowCount"] = count($invoiceData);
        $jsonInvoiceData["ic_add"] = "$adminHost/public/img/ic_add.png";
        $jsonInvoiceData["ic_del"] = "$adminHost/public/img/ic_delete.png";
        $jsonInvoiceData["ic_edit"] = "$adminHost/public/img/edit_query.png";
        $jsonInvoiceData["ic_blank"] = "$adminHost/public/img/blank-plus.png";
        $jsonInvoiceData["ic_link"] = "$adminHost/public/img/ic_link.png";
        $jsonInvoiceData["ic_check"] = "$adminHost/public/img/ic_check.png";
        $jsonInvoiceData["ic_newblank"] = "$adminHost/public/img/blank.png";

        $jsonInvoiceData["price"] = "0";
        $jsonInvoiceData["count_product"] = "0";
        $jsonInvoiceData["tag_1"] = "$adminHost/public/img/img_03/tag_off.png";
        $jsonInvoiceData["tag_2"] = "$adminHost/public/img/img_03/tag_on.png";
        $jsonInvoiceData["image_show"] = "checked";
        $jsonInvoiceData["uName_font_size"] = "28";
        $jsonInvoiceData["uTitle_font_size"] = "40";

        $i = 0;
        foreach($allCategories as $k => $c) {
            $i ++;
            $jsonInvoiceData["varient_check_$k"] = $i <= 3 ? "checked" : "";
        }
        $jsonInvoiceData["count_option_checked"] = "3";


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

        $pModel = new Paper();
        $pModel->user_id = $user->id;
        $pModel->subject = "ご請求書";
        $pModel->category = "invoice";
        $pModel->content = json_encode($jsonInvoiceData);
        $pModel->cDate = $date;
        $pModel->eDate = $expire;
        $pModel->total_price = $totalMoney;
        $pModel->memo_text = "";
        $pModel->send_name = "Admin";
        $pModel->save();

        // return $invoicePDF->stream();
        $fileName = microtime(true) . ".pdf";
        $filePath = url("/uploads/products") . '/' . $fileName;

        $invoicePDF->save(base_path("uploads/products") . '/' . $fileName);

        return response()->json([
            'name' => 'invoice',
            'pdf_url' => $filePath,
        ]);

        

    }


    public function productView($id)
    {
        $model = UserProduct::find($id);

        return view(
            'admin/userProduct/productView',
            compact(
                'model',
            )
        );
    }
}