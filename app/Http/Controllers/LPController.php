<?php

namespace App\Http\Controllers;

use App\Http\Requests\LPSavePost;
use App\Models\Content;
use App\Models\LP;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LPController extends Controller
{
	public function index(Request $request)
	{
		$lps = LP::join('users', 'lps.user_id', 'users.id')
			->where('users.id', Auth::user()->id)
			->select('lps.*')
			->simplePaginate(20);

		return view('admin.lp', compact('lps'));
	}

	public function add(Request $request)
	{
		$lp = new LP();
		$statuses = Status::all();

		$lp->user_id = Auth::user()->id;
		$lp->token = Str::random(20);
		$lp->status = 1; // 下書き
		$lp->save();

		$contents = $lp->getDefaultContentSets();

		$user = User::find($lp->user_id);
		$isEdit = true;
		return view('frontend.lp', compact(
			'lp',
			'statuses',
			'user',
			'contents',
			'isEdit'
		)
		);
	}

	public function edit(Request $request, $id)
	{
		$lp = LP::find($id);
		$user = User::find($lp->user_id);
		$statuses = Status::all();
		$contents = Content::where('lp_id', $id)->get()->sortBy("ord");
		$isEdit = true;

		return view('frontend.lp', compact(
			'lp',
			'statuses',
			'user',
			'contents',
			'isEdit'
		)
		);
	}

	public function delete(Request $request, $id)
	{
		$result = LP::where('id', $id)->delete();
		return redirect()->route('admin.lps');
	}

	public function save(LPSavePost $request)
	{
		$lp = LP::find($request->get('id'));

		$lp->title = $request->get('title') ? $request->get('title') : '';
		$lp->title2 = $request->get('title2') ? $request->get('title2') : '';
		$lp->title3 = $request->get('title3') ? $request->get('title3') : '';
		$lp->tel = $request->get('tel') ? $request->get('tel') : '';
		$lp->email = $request->get('email') ? $request->get('email') : '';
		$lp->status = $request->get('status');
		$lp->description = $request->get('description');

		$lp_settings = isset($lp->settings) ? json_decode($lp->settings, true) : [];
		$lp_settings['map_html'] = $request->get('map_html');
		$lp_settings['profile_url'] = $request->get('profile_url');
		$lp_settings['twitter_url'] = $request->get('twitter_url') ? $request->get('twitter_url') : '';
		$lp_settings['facebook_url'] = $request->get('facebook_url') ? $request->get('facebook_url') : '';
		$lp_settings['instagram_url'] = $request->get('instagram_url') ? $request->get('instagram_url') : '';
		$lp_settings['shop_info'] = $request->get('shop_info');
		$lp_settings['shop_intro'] = $request->get('shop_intro');
		$lp_settings['mainimgs'] = json_encode($request->get('mainimgs'));

		$lp_settings['icon_size'] = json_encode($request->get('icon_size'));
		$lp_settings['icon_position'] = json_encode($request->get('icon_position'));

		$lp->settings = json_encode($lp_settings);

		$lp->save();


		$contents = $request->get('contents');

		if ($contents != null) {
			$c_ids = array_keys($contents);
			$ord = 1;
			foreach ($c_ids as $c_key) {
				$item = $contents[$c_key];
				$content = Content::find($c_key);

				if (!empty($content) && is_object($content)) {

					$content->file_url = isset($item['url']) ? $item['url'] : '';
					$content->description = isset($item['description']) ? $item['description'] : '';
					$content->ord = $ord;

					$content->save();
					$ord++;

				}

			}
		}

		return redirect()->route('admin.lp.edit', [
			'id' => $lp->id,
		]);
	}

	public function show(Request $request, $id)
	{
		$lp = LP::where('id', $id)->first();
		$user = User::find($lp->user_id);
		$contents = Content::where('lp_id', $lp->id)->get()->sortBy("ord");
		$isEdit = false;

		return view('frontend.lp', compact('lp', 'user', 'contents', 'isEdit'));
	}

	public function upload(Request $request)
	{
		$profile_file = $request->file('file');
		$profile_url = '';

		if ($profile_file != null) {
			if (
				strtolower($profile_file->getClientOriginalExtension()) == 'png'
				|| strtolower($profile_file->getClientOriginalExtension()) == 'jpg'
				|| strtolower($profile_file->getClientOriginalExtension()) == 'jpeg'
				|| strtolower($profile_file->getClientOriginalExtension()) == 'gif'
			) {
				$profile_file->move('uploads/lps/' . Auth::user()->id, str_replace(' ', '_', $profile_file->getClientOriginalName()));
				$profile_url = '/uploads/lps/' . Auth::user()->id . '/' . str_replace(' ', '_', $profile_file->getClientOriginalName());
			}
		}
		return response()->json(['url' => $profile_url]);
	}

	public function content(Request $request)
	{
		$content = new Content();
		$content->lp_id = $request->get('lp_id');
		$content->type = $request->get('type');
		$content->save();
		$content->ord = $content->id;
		$content->save();

		return response()->json(['id' => $content->id]);
	}

	public function contentDelete(Request $request)
	{
		Content::find($request->get('content_id'))->delete();
		return response()->json(['id' => $request->get('content_id')]);
	}
}