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

    public function index(Request $request)
    {
        $category = $request->get("category") ? $request->get("category") : "";

        $listModel = null;
        // if (Auth::user()->isAdmin()) {
        $listModel = UserProductCategory::where("name", "like", '%' . $category . '%')->orderby('id', 'desc')->simplePaginate(10);
        // }
        return view('admin/userProductCategory/index', [
            'listModel' => $listModel,
            'category' => $category
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
        $model->sub_name = $request->get('sub_name');
        $model->other = "";
        $model->save();

        Product2Category::where('category_id', $model->id)->delete();

        $productes = json_decode($request->get('productes'));
        foreach ($productes as $product) {
            $tModel = new Product2Category();
            $tModel->product_id = $product->id;
            $tModel->category_id = $model->id;
            $tModel->save();
        }

        return redirect()->route('admin.userProductCategory.edit', [
            'id' => $model->id,
        ]);
    }
    

    public function add(Request $request)
    {
        $model = new UserProductCategory();
        
        $model->main_img_url = "";
        $model->name = $request->get('name');
        $model->sub_name = "";
        $model->other = "";
        $model->save();


        return response()->json([
            'state' => 'SUCCESS',
            'id' => $model->id,
            'name' => $model->name
        ]);
    }

    public function duplicate($id)
    {
        $model = UserProductCategory::find($id);
        $model = $model->replicate();
        $model->save();
        return redirect()->route('admin.userProductCategories');
    }

    public function delete($id)
    {
        UserProductCategory::find($id)->delete();
        return redirect()->route('admin.userProductCategories');
    }
}