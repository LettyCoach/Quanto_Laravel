<?php

namespace App\Http\Controllers;

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
        $listModel = UserProductCategory::simplePaginate(15);
        // }
        return view('admin/userProductCategory/index', ['listModel' => $listModel]);
    }


    public function create()
    {
        $model = new UserProductCategory();
        return view('admin/userProductCategory/edit', ['model' => $model]);
    }


    public function edit($id)
    {
        $model = UserProductCategory::find($id);
        return view('admin/userProductCategory/edit', ['model' => $model]);
    }

    public function save(Request $request)
    {
        $model = new UserProductCategory();
        if ($request->get('id') != null) {
            $model = UserProductCategory::find($request->get('id'));
        }

        $model->name = $request->get('name');
        $model->other = $request->get('other');
        $model->save();

        return redirect()->route('admin.userProductCategory.edit', [
            'id' => $model->id,
        ]);
    }

    public function delete($id)
    {
        UserProductCategory::find($id)->delete();
        return redirect()->route('admin.userProductCategories');
    }
}