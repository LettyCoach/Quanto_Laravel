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
        $u_pCatetories = null;
        if (Auth::user()->isAdmin()) {
            $u_pCatetories = UserProductCategory::simplePaginate(15);
        }
        return view('admin/UserProductCategory/index', ['u_pCategories' => $u_pCatetories]);
    }

 
    public function create()
    {
        $u_pCategory = new UserProductCategory();
        return view('admin/UserProductCategory/edit', ['u_pCategory' => $u_pCategory]);
    }


    public function edit($id)
    {
        $u_pCategory = UserProductCategory::find($id);
        return view('admin/UserProductCategory/edit', ['u_pCategory' => $u_pCategory]);
    }
   
    public function save(Request $request)
    {
        $u_pCategory = new UserProductCategory();
        if ($request->get('id') != null) {
            $u_pCategory = UserProductCategory::find($request->get('id'));
        }

        $u_pCategory->name = $request->get('name');
        $u_pCategory->other = $request->get('other');
        $u_pCategory->save();

        return redirect()->route('admin.userProductCategory.edit', [
			'id' => $u_pCategory->id,
		]);
    }

    public function delete($id)
    {
        UserProductCategory::find($id)->delete();
        return redirect()->route('admin.userProductCategories');
    }
}