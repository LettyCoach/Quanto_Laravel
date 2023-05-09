<?php

namespace App\Http\Controllers;


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

    public function index()
    {
        $models = null;
        $user_id = Auth::user()->id;
        if (Auth::user()->isAdmin()) {
            $models = UserProduct::simplePaginate(15);
        } else {
            $models = UserProduct::where('user_id', $user_id)->simplePaginate(15);
        }

        // $models = UserProduct::where('user_id', '555555555')->simplePaginate(15);;
        return view('admin/userProduct/index', ['models' => $models]);
    }

    public function create()
    {
        $model = new UserProduct();
        $model->options = json_encode([]);
        $model->flagPrice2 = "checked";
        $model->isDisplay = "checked";
        $max_id = DB::table('user_products')->max('id');
        $productID = sprintf("Q%05d%03d", Auth::user()->id, $max_id + 1);
        $listCategory = UserProductCategory::all();
        return view('admin/userProduct/edit', [
            'caption' => "新規登録",
            'model' => $model,
            'productID' => $productID,
            'listCategory' => $listCategory,
        ]);
    }

    public function edit($id)
    {
        $model = UserProduct::find($id);
        $listCategory = UserProductCategory::all();
        $productID = $model->getProductID();
        return view('admin/userProduct/edit', [
            'caption' => "商品情報編集",
            'model' => $model,
            'productID' => $productID,
            'listCategory' => $listCategory,
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
        $model->sku = $request->get('sku');
        $model->price = is_numeric($request->get('price')) ? $request->get('price') : 0;
        $model->price2 = is_numeric($request->get('price2')) ? $request->get('price') : 0;
        $model->flagPrice2 = $request->get('flagPrice2') ? "checked" : "";
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

        return redirect()->route('admin.userProduct.edit', [
            'id' => $model->id,
        ]);
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

    public function delete($id)
    {
        UserProduct::find($id)->delete();
        return redirect()->route('admin.userProducts');
    }
}
