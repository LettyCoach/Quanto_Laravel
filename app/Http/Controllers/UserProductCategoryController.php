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
        $user_id = Auth::user()->id;
        
        $listModel = UserProductCategory::where("name", "like", '%' . $category . '%')->orderby('updated_at', 'desc');
        if (!Auth::user()->isAdmin()) {
            $listModel = $listModel->where('user_id', $user_id);
        }
        $listModel = $listModel->simplePaginate(10);
        
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
        $model->sub_name = $request->get('sub_name') ? $request->get('sub_name') : "";
        $model->other = "";
        $model->user_id = Auth::user()->id;
        $model->save();

        Product2Category::where('category_id', $model->id)->delete();

        $productes = json_decode($request->get('productes'));
        foreach ($productes as $product) {
            $tModel = new Product2Category();
            $tModel->product_id = $product->id;
            $tModel->category_id = $model->id;
            $tModel->save();
        }

        return redirect()->route('admin.userProductCategories');

        // return redirect()->route('admin.userProductCategory.index', [
        //     'id' => $model->id,
        // ]);
    }
    

    public function add(Request $request)
    {
        $id = $request->get('id');
        $model = UserProductCategory::find($id);
        
        if ($model == null) {
            $model = new UserProductCategory();
        }
        
        $model->main_img_url = "";
        $model->name = $request->get('name');
        $model->sub_name = "";
        $model->user_id = Auth::user()->id;
        $model->other = "";
        $model->save();

        $categories = UserProductCategory::orderBy('name', 'asc');
        // if (!Auth::user()->isAdmin()) {
        $categories = $categories->where('user_id', Auth::user()->id);
        // }
        $categories = $categories->get();

        return response()->json([
            'state' => 'SUCCESS',
            'categories' => json_encode($categories),
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
    public function remove(Request $request)
    {
        $id = $request->get('id');
        UserProductCategory::find($id)->delete();

        $categories = UserProductCategory::orderBy('name', 'asc')->get();

        return response()->json([
            'state' => 'SUCCESS',
            'categories' => json_encode($categories),
        ]);
    }

}