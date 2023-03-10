<?php

namespace App\Http\Controllers;

use App\Models\UserProductColor;
use App\Models\UserProductSize;
use App\Models\UserProductCategory;
use App\Models\UserProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $listModel = null;
        $user_id = Auth::user()->id;
        if (Auth::user()->isAdmin()) {
            $listModel = UserProduct::simplePaginate(15);
        } else {
            $listModel = UserProduct::where('user_id', $user_id)->simplePaginate(15);
        }
        return view('admin/userProduct/index', ['listModel' => $listModel]);
    }

    public function create()
    {
        $model = new UserProduct();
        $model->isDisplay = "checked";
        $listUPColor = UserProductColor::all();
        $listUPSize = UserProductSize::all();
        $listCategory = UserProductCategory::all();
        return view('admin/userProduct/edit', [
            'model' => $model,
            'listUPColor' => $listUPColor,
            'listUPSize' => $listUPSize,
            'listCategory' => $listCategory,
        ]);
    }

    public function edit($id)
    {
        $model = UserProduct::find($id);
        $listUPColor = UserProductColor::all();
        $listUPSize = UserProductSize::all();
        $listCategory = UserProductCategory::all();
        return view('admin/userProduct/edit', [
            'model' => $model,
            'listUPColor' => $listUPColor,
            'listUPSize' => $listUPSize,
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
        $model->img_urls = $request->get('img_urls') ? $request->get('img_urls') : "_";
        $model->detail = $request->get('detail');
        $model->category_ids = $request->get('category_ids') ? $request->get('category_ids') : "_";
        $model->color_id = $request->get('color_id');
        $model->size_id = $request->get('size_id');
        $model->materials = $request->get('materials') ? $request->get('materials') : "_m_";
        $model->memo = $request->get('memo') ? $request->get('memo') : "";
        $model->stock = is_numeric($request->get('stock')) ? $request->get('stock') : 0;
        $model->stockLimit =  $request->get('stockLimit') ? "checked" : "";
        $model->barcode = $request->get('barcode') ? $request->get('barcode') : "";
        $model->isDisplay = $request->get('isDisplay') ? "checked" : "";
        $model->user_id = Auth::user()->id;
        $model->other = "";
        $model->save();

        return redirect()->route('admin.userProduct.edit', [
            'id' => $model->id,
        ]);
    }

    public function delete($id)
    {
        UserProduct::find($id)->delete();
        return redirect()->route('admin.userProducts');
    }
}