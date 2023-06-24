<?php

namespace App\Http\Controllers;


use App\Models\Member;
use App\Models\PaymentMethod;
use App\Models\Purpose;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $purposes = Purpose::where('user_id', Auth::user()->id)->get();
        $members = Member::where('user_id', Auth::user()->id)->get();
        $payment_methods = PaymentMethod::where('user_id', Auth::user()->id)->get();

        return view('admin.users.profile', [
            'purposes' => $purposes,
            'members' => $members,
            'payment_methods' => $payment_methods,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::user()->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|max:12|required_with:current_password',
            'password_confirmation' => 'nullable|min:8|max:12|required_with:new_password|same:new_password'
        ]);

        $user = User::findOrFail(Auth::user()->id);
        $user->name = $request->input('name');
        $user->full_name = $request->input('full_name');
        $user->shop_name = $request->input('shop_name');
        $user->email = $request->input('email');
        $user->profile_url = $request->input('profile_url');
        $user->zip_code = $request->input('zip_code');
        $user->address = $request->input('address');
        $user->phone_number = $request->input('phone_number');

        $user_settings['invoice'] = $request->get('invoice') ? $request->get('invoice') : '';
        $user_settings['member'] = $request->get('member') ? $request->get('member') : '';
        $user_settings['purpose'] = $request->get('purpose') ? $request->get('purpose') : '';
        $user_settings['payment_method'] = $request->get('payment_method') ? $request->get('payment_method') : '';
        $user_settings['stamp_url'] = $request->input('stamp_url');
        $user->settings = json_encode($user_settings);

        if (!is_null($request->input('current_password'))) {
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = $request->input('new_password');
            } else {
                return redirect()->back()->withInput();
            }
        }

        $user->save();

        return redirect()->route('profile')->withSuccess('Profile updated successfully.');
    }

    public function purpose(Request $request)
    {
        $purpose = new Purpose();
        $purpose->user_id = Auth::user()->id;
        $purpose->name = $request->input('data');

        $purpose->save();

        return response()->json(['message' => 'success']);
    }

    public function payment_method(Request $request)
    {
        $payment_method = new PaymentMethod();
        $payment_method->user_id = Auth::user()->id;
        $payment_method->name = $request->input('data');

        $payment_method->save();

        return response()->json(['message' => 'success']);
    }

    public function member(Request $request)
    {
        $member = new Member();
        $member->user_id = Auth::user()->id;
        $member->name = $request->input('data');

        $member->save();

        return response()->json(['message' => 'success']);
    }
}
