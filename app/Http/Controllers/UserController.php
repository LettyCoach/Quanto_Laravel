<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PaymentMethod;
use App\Models\Purpose;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        if (Auth::user()->isAdmin()) {
            $users = User::simplePaginate(20);
        } else {
            $users = User::where('id', Auth::user()->id)->simplePaginate(20);
        }
        return view('admin/users/index', ['users' => $users]);
    }

    public function create()
    {
        $user_id = 0;
        $purposes = Purpose::where('user_id', $user_id)->get();
        $members = Member::where('user_id', $user_id)->get();
        $payment_methods = PaymentMethod::where('user_id', $user_id)->get();

        $model = new User();

        return view(
            'admin/users/create',
            compact(
                'model',
                'purposes',
                'members',
                'payment_methods',
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,',
            'current_password' => 'required|min:8',
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->full_name = $request->input('full_name') ?? "";
        $user->shop_name = $request->input('shop_name');
        $user->email = $request->input('email');
        $user->profile_url = "";
        $user->zip_code = $request->input('zip_code');
        $user->address = $request->input('address');
        $user->phone_number = $request->input('phone_number');
        $user->prefecture = $request->prefecture ?? '';
        $user->charge_member_name = $request->charge_member_name ?? '';
        $user->ec_role = $request->ec_role ?? 0;
        $user->memo = $request->memo ?? '';
        $user->password = Hash::make($request->current_password ?? '12345678');


        $user_settings['invoice'] = $request->get('invoice') ?? '';
        $user_settings['member'] = $request->get('member') ?? '';
        $user_settings['purpose'] = $request->get('purpose') ?? '';
        $user_settings['phone_number'] = $request->get('phone_number') ?? '';
        $user_settings['job_position'] = $request->get('job_position') ?? '';
        $user_settings['payment_method'] = $request->get('payment_method') ?? '';
        $user_settings['stamp_url'] = $request->input('stamp_url') ?? '';
        $user->settings = json_encode($user_settings);


        $user->save();

        return redirect()->route('admin.users');
    }

    public function edit(Request $request, $id)
    {
        $model = User::find($id);

        return view('admin/users/edit', compact('model'));
    }

    public function update(Request $request)
    {

        $id = $request->id;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|max:12|required_with:current_password',
            'password_confirmation' => 'nullable|min:8|max:12|required_with:new_password|same:new_password'
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->input('name');
        $user->full_name = $request->input('full_name') ?? "";
        $user->shop_name = $request->input('shop_name');
        $user->email = $request->input('email');
        $user->profile_url = "";
        $user->zip_code = $request->input('zip_code');
        $user->address = $request->input('address');
        $user->phone_number = $request->input('phone_number');
        $user->prefecture = $request->prefecture ?? '';
        $user->charge_member_name = $request->charge_member_name ?? '';
        $user->ec_role = $request->ec_role ?? 0;
        $user->memo = $request->memo ?? '';

        $user_settings['invoice'] = $request->get('invoice') ?? '';
        $user_settings['member'] = $request->get('member') ?? '';
        $user_settings['purpose'] = $request->get('purpose') ?? '';
        $user_settings['phone_number'] = $request->get('phone_number') ?? '';
        $user_settings['job_position'] = $request->get('job_position') ?? '';
        $user_settings['payment_method'] = $request->get('payment_method') ?? '';
        $user_settings['stamp_url'] = $request->input('stamp_url') ?? '';
        $user->settings = json_encode($user_settings);

        if (!is_null($request->input('current_password'))) {
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = $request->input('new_password');
            } else {
                return redirect()->back()->withInput();
            }
        }

        $user->save();

        return redirect()->route('admin.users');
    }

    public function delete(Request $request, $id)
    {
        $result = User::where('id', $id)->delete();
        return redirect()->route('admin.users');
    }

    public function upload(Request $request)
    {
        $profile_file = $request->file('profile_path') != null ? $request->file('profile_path') : $request->file('stamp_path');
        $profile_url = '';

        if ($profile_file != null) {
            if (
                strtolower($profile_file->getClientOriginalExtension()) == 'png'
                || strtolower($profile_file->getClientOriginalExtension()) == 'jpg'
                || strtolower($profile_file->getClientOriginalExtension()) == 'jpeg'
                || strtolower($profile_file->getClientOriginalExtension()) == 'gif'
            ) {
                $profile_file->move('uploads/users', str_replace(' ', '_', $profile_file->getClientOriginalName()));
                $profile_url = '/uploads/users/' . str_replace(' ', '_', $profile_file->getClientOriginalName());
            }
        }
        return response()->json(['profile_url' => $profile_url]);
    }
}