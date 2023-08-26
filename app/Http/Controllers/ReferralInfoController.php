<?php

namespace App\Http\Controllers;

use App\Models\ReferralInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReferralInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        
        $referralInfo = ReferralInfo::where('user_id', Auth::user()->id)->simplePaginate(20);
        if(Auth::user()->isAdmin()) {
            $referralInfo = ReferralInfo::simplePaginate(20);
        }
        return view('admin/referralInfo/index', ['infos' => $referralInfo]);
    }

    public function save(Request $request) {

        //$referralInfoList = ReferralInfo::where('id', 0)->simplePaginate(20);;
        //if(Auth::user()->isAdmin()) {
            if($request->post('id') != null) {
                $referralInfo = ReferralInfo::find($request->post('id'));
            } else {
                $referralInfo = new ReferralInfo();
            }

            $referralInfo->name = $request->post('name');
            $referralInfo->info = htmlspecialchars($request->post('info'));
            $referralInfo->user_id = Auth::user()->id;
            $referralInfo->save();

            $referralInfoList = ReferralInfo::where('user_id', Auth::user()->id)->simplePaginate(20);
            if(Auth::user()->isAdmin()) {
                $referralInfoList = ReferralInfo::simplePaginate(20);
            }
        //}
        return view('admin/referralInfo/index', ['infos' => $referralInfoList]);
    }

    public function edit(Request $request, $id){
        $user = ReferralInfo::find($id);
        return view('admin/referralInfo/profile', ['user' => $user]);
    }

    public function delete(Request $request, $id)
    {
        $result = ReferralInfo::where('id',$id)->delete();
        $referralInfo = ReferralInfo::where('user_id', Auth::user()->id)->simplePaginate(20);
        if(Auth::user()->isAdmin()) {
            $referralInfo = ReferralInfo::simplePaginate(20);
        }
        return view('admin/referralInfo/index', ['infos' => $referralInfo]);
    }

}
