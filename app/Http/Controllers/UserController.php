<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){

        if(Auth::user()->isAdmin()) {
            $users = User::simplePaginate(20);
        }
        else {
            $users = User::where('id', Auth::user()->id)->simplePaginate(20);
        }
        return view('admin/users/index', ['users' => $users]);
    }

    public function edit(Request $request, $id){
        $user = User::find($id);
        return view('admin/users/profile', ['user' => $user]);
    }

    public function delete(Request $request, $id)
    {
        $result = User::where('id',$id)->delete();
        return redirect()->route('admin.users');
    }

    public function upload(Request $request){
        $profile_file = $request->file('profile_path') != null ? $request->file('profile_path') : $request->file('stamp_path');
        $profile_url = '';

        if ($profile_file != null) {
            if (strtolower($profile_file->getClientOriginalExtension()) == 'png'
                || strtolower($profile_file->getClientOriginalExtension()) == 'jpg'
                || strtolower($profile_file->getClientOriginalExtension()) == 'jpeg'
                || strtolower($profile_file->getClientOriginalExtension()) == 'gif'
            ) {
                $profile_file->move('uploads/users', str_replace(' ','_', $profile_file->getClientOriginalName()));
                $profile_url = '/uploads/users/' . str_replace(' ','_', $profile_file->getClientOriginalName());
            }
        }
        return response()->json(['profile_url'=>$profile_url]);
    }
}
