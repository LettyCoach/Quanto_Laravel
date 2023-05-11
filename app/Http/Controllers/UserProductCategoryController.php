<?php

namespace App\Http\Controllers;

use App\Models\Product2Category;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProductCategory;
use Illuminate\Http\Request;

class UserProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $listModel = null;
        // if (Auth::user()->isAdmin()) {
        $listModel = UserProductCategory::simplePaginate(10);
        // }
        return view('admin/userProductCategory/index', [
            'listModel' => $listModel
        ]);
    }


    public function create()
    {
        $model = new UserProductCategory();
        return view('admin/userProductCategory/edit', [
            'caption' => "カテゴリー新規登録",
            'model' => $model
        ]);
    }

    public function edit($id)
    {
        $model = UserProductCategory::find($id);
        return view('admin/userProductCategory/edit', [
            'caption' => "カテゴリー情報編集",
            'model' => $model
        ]);
    }

    public function save(Request $request)
    {
        $model = new UserProductCategory();
        if ($request->get('id') != null) {
            $model = UserProductCategory::find($request->get('id'));
        }

        $model->main_img_url = $request->get('main_img_url') ? $request->get('main_img_url') : "";
        $model->name = $request->get('name');
        $model->other = "";
        $model->save();

        Product2Category::where('category_id', $model->id)->delete();

        $productes = json_decode($request->get('productes'));
        foreach($productes as $product) {
            $tModel = new Product2Category();
            $tModel->product_id = $product->id;
            $tModel->category_id = $model->id;
            $tModel->save();
        }

        return redirect()->route('admin.userProductCategory.edit', [
            'id' => $model->id,
        ]);
    }

    public function duplicate($id)
    {
        $model = UserProductCategory::find($id);
        $model =$model->replicate();
        $model->save();
        return redirect()->route('admin.userProductCategories');
    }

    public function delete($id)
    {
        UserProductCategory::find($id)->delete();
        return redirect()->route('admin.userProductCategories');
    }
}
