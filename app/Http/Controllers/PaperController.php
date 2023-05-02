<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Status;
use App\Models\User;
use App\Models\Paper;
use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\Answer;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProduct;

class PaperController extends Controller
{
	public function index(Request $request)
	{

        return view('paper/invoice');
	}

	public function invoiceNew(Request $request)
	{

		$surveys = Survey::where('user_id', Auth::user()->id)->get();
		$answers=[];
		// $i = 0;
		// foreach($surveys as $survey){
		// 	if ($i ++ > 5) break;
        //     if($survey->id==null) continue;
		// 	$key=$survey->id;
		// 	$answers[$key]=Answer::where('survey_id', $key)->get();
		// }
		//
        $listModel = null;
        $user_id = Auth::user()->id;
        $listModel = UserProduct::all();

		$listDataTmp = array();
		foreach($listModel as $i => $model) {

			$listDataTmp[$i]['title'] = $model->name . ' (' . $model->sku . ')' . '、 ' . $model->userProductColor->name . '/ ' . $model->userProductSize->name . '/ ' . $model->getMaterialsText();
			$listDataTmp[$i]['file_url'] = $model->getImageUrlFirst();
			$listDataTmp[$i]['value'] = $model->price;
		}
		$answers[0]=$listDataTmp;


        $date = strval(date('Y-m-d'));
        $expire = strval(date('Y-m-d',strtotime('+1 day')));
        return view('paper/invoiceNew', [
			'edit_id'=>0,
			'cDate'=>$date,
			'eDate'=>$expire,
			'answers'=>$answers,
        ]);
	}

	public function invoiceSave(Request $request){
		$user_id=Auth::user()->id;
		$subject=$request->invoiceName;
		$category='invoice';
		$content=$request->file;

		if($request->paper_id==0){  
			$paper= new Paper();
			$paper->user_id = $user_id;
			$paper->subject = $subject;
			$paper->category = $category;
			$paper->content = $content;
			$paper->save();
			$edit_id=$paper->id;
			return ['edit_id'=>$edit_id,'inv_state'=>'add'];
		}
		else{
			Paper::where('id',$request->paper_id)->update(['content'=>$content,'subject'=>$subject]);
			$edit_id=$request->paper_id;
			return ['edit_id'=>$edit_id,'inv_state'=>'edit'];
		}
	}

	public function invoiceEdit(Request $request, $id){
		$editInvoice=Paper::where('id', $id)->first();
        return view('paper/invoiceEdit', [
			'editData'=>$editInvoice,
		]);
	}

	public function invoiceDelete(Request $request, $id){
		Paper::where('id', $id)->delete();
		$papers = Paper::where('user_id',Auth::user()->id)->paginate(15);
		$user=User::where('id',Auth::user()->id)->first();
		$userName=$user->name;
		return view('paper/invoice',['papers' => $papers, 'userName'=>$userName,]);
		
	}

	public function invoice(Request $request){
		$papers = Paper::where('user_id',Auth::user()->id)->paginate(15);
		$user=User::where('id',Auth::user()->id)->first();
		$userName=$user->name;
		return view('paper/invoice',['papers' => $papers, 'userName'=>$userName,]);
	}
}