<?php

namespace App\Http\Controllers;


use App\Models\SaveItem;
use App\Models\UserProductCategory;
use App\Models\Product2Category;
use App\Models\UserProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;


class UserProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $models = null;
        $user_id = Auth::user()->id;
        $keyword = $request->keyword ?? '';
        $models = UserProduct::where("name", "like", "%$keyword%");
        if (Auth::user()->isAdmin()) {
            $models = $models->orderby('id', 'desc')->simplePaginate(15);
        } else {
            $models = $models->where('user_id', $user_id)->orderby('id', 'desc')->simplePaginate(15);
        }
        return view(
            'admin/userProduct/index',
            compact(
                'models',
                'keyword'
            )
        );
    }

    public function show($id)
    {
        $model = UserProduct::find($id);
        $user_id = Auth::user()->id;
        $user = $model->users()->find($user_id);
        $rlt = [
            'productID' => $model->getProductID(),
            'main_img' => '',
            'img_urls' => $model->getImageUrlsFullPath(),
            'name' => $model->name,
            'price' => $model->price,
            'detail' => $model->detail,
            'brandName' => $model->brandName,
            'sku' => $model->sku,
            'tag' => $user != null ? true : false,
            'options' => $model->getOptionsArray(),
        ];
        return json_encode($rlt);
    }

    public function showNew($id)
    {
        $models = null;
        $user_id = Auth::user()->id;
        if (Auth::user()->isAdmin()) {
            $models = UserProduct::orderby('id', 'desc')->simplePaginate(15);
        } else {
            $models = UserProduct::where('user_id', $user_id)->orderby('id', 'desc')->simplePaginate(15);
        }
        return view('admin/userProduct/showNew', ['models' => $models, 'id' => $id]);
    }

    public function setTag(Request $request)
    {
        $product_id = $request->get('product_id');
        $user_id = Auth::user()->id;
        $flag = $request->get('flag');

        SaveItem::where('product_id', $product_id)->where('user_id', $user_id)->delete();
        if ($flag == 1) {
            $model = new SaveItem();
            $model->product_id = $product_id;
            $model->user_id = $user_id;
            $model->save();
        }
    }

    public function create()
    {
        $model = new UserProduct();
        $model->options = json_encode([]);
        $model->flagPrice2 = "checked";
        $model->isDisplay = "checked";
        $max_id = DB::table('user_products')->max('id');
        $productID = sprintf("Q%05d%03d", Auth::user()->id, $max_id + 1);

        $categories = UserProductCategory::orderBy('name', 'asc');
        // if (!Auth::user()->isAdmin()) {
        $categories = $categories->where('user_id', Auth::user()->id);
        // }
        $categories = $categories->get();

        return view('admin/userProduct/edit', [
            'caption' => "新規登録",
            'model' => $model,
            'productID' => $productID,
            'categories' => $categories,
        ]);
    }

    public function edit($id)
    {
        $model = UserProduct::find($id);
        $model->getImageUrls();

        $categories = UserProductCategory::orderBy('name', 'asc');
        // if (!Auth::user()->isAdmin()) {
        $categories = $categories->where('user_id', Auth::user()->id);
        // }
        $categories = $categories->get();

        $productID = $model->getProductID();
        return view('admin/userProduct/edit', [
            'caption' => "商品情報編集",
            'model' => $model,
            'productID' => $productID,
            'categories' => json_encode($categories),
        ]);
    }

    public function save(Request $request)
    {
        $model = new UserProduct();
        if ($request->get('id') != null) {
            $model = UserProduct::find($request->get('id'));
        }

        $model->brandName = $request->get('brandName');
        $model->name = $request->get('name');
        $model->sku = $request->get('sku') ? $request->get('sku') : "";
        $model->price_txt = $request->get('price_txt');
        $model->price = is_numeric($request->get('price')) ? $request->get('price') : 0;
        $model->price2_txt = $request->get('price2_txt') ? $request->get('price2_txt') : "";
        $model->price2 = is_numeric($request->get('price2')) ? $request->get('price') : 0;
        $model->flagPrice2 = $request->get('flagPrice2') ? "checked" : "";
        $model->main_img_url = $request->get('main_img_url') ? $request->get('main_img_url') : "";
        $model->img_urls = $request->get('img_urls') ? $request->get('img_urls') : "_";
        $model->options = $request->get('options');
        $model->detail = $request->get('detail');
        $model->memo = $request->get('memo') ? $request->get('memo') : "";
        $model->stock = is_numeric($request->get('stock')) ? $request->get('stock') : 0;
        $model->stockLimit = $request->get('stockLimit') ? "checked" : "";
        $model->barcode = $request->get('barcode') ? $request->get('barcode') : "";
        $model->isDisplay = $request->get('isDisplay') ? "checked" : "";
        $model->user_id = Auth::user()->id;
        $model->other = "";
        $model->save();

        $category_ids = $request->get('category_ids');
        $this->addCatetories($model->id, $category_ids);

        return redirect()->route('admin.userProducts');

        // return redirect()->route('admin.userProduct.edit', [
        //     'id' => $model->id,
        // ]);
    }

    public function addCatetories($product_id, $category_ids)
    {

        $rlt = explode("_", $category_ids);
        array_shift($rlt);
        array_pop($rlt);
        Product2Category::where('product_id', $product_id)->delete();

        foreach ($rlt as $i => $category_id) {

            $tModel = new Product2Category();
            $tModel->product_id = $product_id;
            $tModel->category_id = $category_id;
            $tModel->save();
        }
    }

    public function duplicate($id)
    {
        $model = UserProduct::find($id);
        $model = $model->replicate();
        $model->save();
        return redirect()->route('admin.userProducts');
    }

    public function delete($id)
    {
        UserProduct::find($id)->delete();
        return redirect()->route('admin.userProducts');
    }

    public function uploadCSV(Request $request)
    {
        $csv = $request->csv;
        foreach ($csv['data'] as $item) {
            if (isset($item['ID']) === false || strlen($item['ID']) < 1 || $item['ID'] === "")
                continue;
            $model = new UserProduct();

            $model->brandName = "";
            $model->name = "";
            $model->sku = "";
            $model->price_txt = "";
            $model->price = 0;
            $model->price2_txt = "";
            $model->price2 = 0;
            $model->flagPrice2 = "";
            $model->main_img_url = "";
            $model->img_urls = "";
            $model->options = "[]";
            $model->detail = "";
            $model->memo = "";
            $model->stock = 0;
            $model->stockLimit = "";
            $model->barcode = "";
            $model->isDisplay = "";
            $model->user_id = Auth::user()->id;
            $model->other = "";


            $model->brandName = $item['Brand'] ?? "";
            $value = [['name' => '', 'url' => $item['Image URL'], 'state' => ""]];
            $model->img_urls = json_encode($value, JSON_UNESCAPED_UNICODE);
            $model->name = $item['Product Name'] ?? "";
            $model->sku = $item['SKU'] ?? "";
            $model->detail = $item['description'] ?? "";
            $model->price = $item['Price'] ?? 0;
            $model->price2 = $item['Sale-Price'] ?? 0;
            $value = [
                ['name' => "カラー", "description" => [$item['Color']]],
                ['name' => "サイズ", "description" => [$item['Size']]],
                ['name' => "素材", "description" => [$item['Material']]],
            ];

            $options = $item['Option'];
            $options = explode(",", $options);
            foreach ($options as $option) {
                $key = explode(":", $option)[0];
                $val = explode(":", $option)[1];
                array_push($value, ['name' => $key, "description" => [$val]]);
            }

            $model->options = json_encode($value, JSON_UNESCAPED_UNICODE);

            $model->barcode = $item['Barcode'] ?? "";
            $model->save();


            $categories = $item['Category'] ?? "";
            $categories = explode(",", $categories);
            foreach ($categories as $category) {
                $user_id = Auth::user()->id;
                $rlt = UserProductCategory::where('user_id', $user_id)->where('name', $category)->get();

                $c_id = 0;
                if (count($rlt) > 0) {
                    $c_id = $rlt[0]->id;
                } else {
                    $modelC = new UserProductCategory();
                    $modelC->main_img_url = "";
                    $modelC->name = $category;
                    $modelC->sub_name = "";
                    $modelC->user_id = $user_id;
                    $modelC->other = "";
                    $modelC->save();
                    $c_id = $modelC->id;
                }

                $modelT = new Product2Category();
                $modelT->product_id = $model->id;
                $modelT->category_id = $c_id;
                $modelT->save();
            }
        }
        // print_r($csv['data']);
    }
}