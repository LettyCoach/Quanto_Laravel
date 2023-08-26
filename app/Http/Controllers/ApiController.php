<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Branch;
use App\Models\Client;
use App\Models\ClientAnswer;
use App\Models\ClientOption;
use App\Models\Question;
use App\Models\ReferralInfo;
use App\Models\Survey;
use App\Models\UserProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\UploadedFile;
use Exception;
use PDF;
use Storage;

class ApiController extends Controller
{
    public $suveyData = "";
    public $surveySettingData = "";
    public function getSurvey(Request $request, $id)
    {
        $query = Survey::where('token', $id)->first();
        $question = Question::where('survey_id', $query->id)->orderBy('ord')->get();
        $answer = Answer::where('survey_id', $query->id)->get();
        $referral = array();
        foreach ($question as $q) {
            $refer_id = $q->referral_info;

            if ($refer_id != null && !in_array($refer_id, $referral)) {
                $referral[] = (int) $refer_id;
            }
        }
        foreach ($answer as $a) {
            $refer_id = $a->referral_info;

            if ($refer_id != null && !in_array($refer_id, $referral)) {
                $referral[] = (int) $refer_id;
            }
        }
        $referral_info = ReferralInfo::whereIn('id', $referral)->get();
        $query['referral'] = $referral_info;
        $query['questions'] = $question;
        $query['answers'] = $answer;
        $query['brand_logo_path'] = $query['profile_path'];
        $user_profile_url = '';
        $user_profile_name = '';
        if ($query != null) {
            $user = User::find($query->user_id);
            if ($user != null) {
                $user_profile_url = $user->profile_url;
                $user_profile_name = $user->full_name;
            }
        }
        $query['user_profile_url'] = $user_profile_url;
        $query['user_profile_name'] = $user_profile_name;
        $survey_settings = json_decode($query->settings, true);
        $this->surveySettingData = $survey_settings;
        if (isset($survey_settings['displayQrcode']) && $survey_settings['displayQrcode'] == 1) {
            $clientHost = Config::get('constants.clientHost');
            // $query['qr_code'] = 'data:image/png;base64,' . base64_encode(QrCode::format('png')->size(100)->generate($clientHost . '?id=' . $id));
            $query['qr_code'] = "";
        }
        $this->suveyData = $query;
        return response()->json($query);
    }

    public function getAnswer(Request $request, $id)
    {
        $query = Answer::find($id);
        return response()->json($query);
    }

    public function getAnswers(Request $request, $qid)
    {
        $query = Answer::where('question_id', $qid)->get();
        return response()->json($query);
    }

    public function getQuestion(Request $request, $id)
    {
        $query = Question::find($id);
        $answers = Answer::where('question_id', $id)->get();
        $next_question = Question::where([
            'survey_id' => $query->survey_id,
            'ord' => $query->ord + 1
        ])->first();
        $query['answers'] = $answers;
        if ($next_question != null) {
            $query['next_question_id'] = $next_question->id;
        } else {
            $query['next_question_id'] = null;
        }
        return response()->json($query);
    }

    public function saveAnswers(Request $request)
    {
        $survey = Survey::where('token', $request->get('survey_id'))->first();

        $client = new Client();
        $client->name = $request->get('name');
        $client->email = $request->get('email');
        $client->full_name = $request->get('name');
        $client->zip_code = $request->get('zip_code');
        $client->address = $request->get('address');
        $client->phone_number = $request->get('phone_number');
        $client->survey_id = $survey->id;
        $total = is_numeric($request->get('total')) ? $request->get('total') : 0;
        $client->total = $total;
        $client->send_mail_status = 0;
        $client->save();

        $client_id = $client->id;
        $survey_settings = json_decode($survey->settings, true);

        $answers = $request->get('answers');
        $files = $request->file('options');
        $answerModels = [];
        foreach ($answers as $key => $answer) {
            $clientAnswer = new ClientAnswer();
            $clientAnswer->client_id = $client_id;
            $clientAnswer->answer_id = $answer['id'] == '[]' ? "[0]" : $answer['id']; // スキップ用の回答としてid0のレコードを作ったのでこうしている
            $clientAnswer->question = $key; // questionIDがkeyになっている
            $clientAnswer->save();
            $answerModels[] = $clientAnswer;

            // オプションメディアの保存
            $file_url = "";
            if (isset($files[$key])) {
                $file = $files[$key];
                $file->move('uploads/options', $client_id . "_" . $key . "_" . str_replace(' ', '_', $file->getClientOriginalName()));
                $file_url = 'uploads/options/' . $client_id . "_" . $key . "_" . str_replace(' ', '_', $file->getClientOriginalName());
            }
            $clientOption = new ClientOption();
            $clientOption->client_id = $client_id;
            $clientOption->question_id = $key;
            $clientOption->file_url = $file_url;
            $clientOption->save();
        }
        if ($survey_settings['autoSendMail']) {
            $this->sendMail($client, $answerModels);
        }
        return redirect(Config::get('constants.clientHost', 'http://formstylee-front.com/') . 'thankyou.php');
    }

    public function callbackStripeOauth(Request $request)
    {
        Log::info('★', $request->all());
    }

    private function sendMail($client, $answers)
    {
        $survey = $client->survey != null ? $client->survey->title : '';
        $content = "<h2>ユーザ設問回答</h2>";
        $content .= "<p><span>ユーザ名</span><span>$client->name</span></p>";
        $content .= "<p><span>メールアドレス</span><span>$client->email</span></p>";
        $content .= "<p><span>名前</span><span>$client->full_name</span></p>";
        $content .= "<p><span>郵便番号</span><span>$client->zip_code</span></p>";
        $content .= "<p><span>住所</span><span>$client->address</span></p>";
        $content .= "<p><span>電話番号</span><span>$client->phone_number</span></p>";
        $content .= "<p><span>設問</span><span>$survey</span></p>";
        $content .= "<table><thead><th>質問</th><th>回答</th></thead><tbody>";
        foreach ($answers as $answer) {
            $answerDetail = [];
            $question = Question::find($answer->question);
            $answerIds = json_decode($answer->answer_id);
            foreach ($answerIds as $id) {
                $ans = Answer::find($id);
                $answerDetail[] = $ans;
            }

            $q_item = isset($question) ? $question->title : '';
            $a_item = '';
            foreach ($answerDetail as $ans) {
                $a_item .= "<div>" . $ans['title'] . "-" . $ans['value'] . "</div>\n";
            }

            $content .= "<tr><td>$q_item</td><td>$a_item</td></tr>";
        }
        $content .= "</tbody></table>";
        // $mail = new PHPMailer(true);
        try {
            // $mail->isSMTP();
            // $mail->CharSet = "utf-8";
            // $mail->SMTPAuth = true;
            // $mail->SMTPSecure = "ssl";
            // $mail->Host = Config::get('constants.mail.host');
            // $mail->Port = Config::get('constants.mail.port');
            // $mail->Username = Config::get('constants.mail.username');
            // $mail->Password = Config::get('constants.mail.password');
            // $mail->setFrom(Config::get('constants.mail.admin_email'), Config::get('constants.mail.admin_name'));
            // $mail->Timeout = 30;
            // $mail->Subject = "設問回答";
            // $mail->MsgHTML($content);
            // $mail->addAddress($client->email, $client->full_name);
            // $users = User::all();
            // foreach ($users as $user) {
            //     $mail->addAddress($user->email, $user->full_name);
            // }
            // $mail->send();
            $data = [
                'body' => new HtmlString($content),
            ];
            $email = $client->email;
            Mail::send([], [], function (Message $message) use ($data, $email) {
                $message->setBody($data['body'], 'text/html');
                $message->subject('設問回答');
                $message->to($email);
            });

            $client->send_mail_status = 1;
            $client->save();
        }catch (Exception $e) {
            $client->send_mail_status = 0;
            $client->save();
            dd($e);
        }
    }

    public function pdf1(Request $request)
    {
        $user = User::findOrFail($request->input('user_id'));
        $survey = Survey::where('token', $request->input('id'))->first();
        $date = date('Y年n月j日', strtotime($request->input('publish')));
        $expire = date('Y年n月j日', strtotime($request->input('expire')));
        $name = $request->input('name');

        $branch = Branch::where('user_id', $request->input('user_id'))->where('survey_id', $survey->id)->first();
        if ($branch == null) {
            $branch = new Branch();
            $branch->user_id = $request->input('user_id');
            $branch->survey_id = $survey->id;
            $branch->count = 1;
        } else {
            $branch->count = $branch->count + 1;
        }
        $branch->save();

        $img_urls = $request->input('img_urls');
        $titles = $request->input('titles');
        $prices = $request->input('prices');
        $quantities = $request->input('quantities');
        $taxes = $request->input('taxes');
        $total = $request->input('total');
        $question_code = $request->input('question_code');
        $parentCategory = $request->input('parentCategory');

        $pdf = PDF::loadView(
            'pdf',
            compact(
                'user',
                'survey',
                'date',
                'expire',
                'name',
                'branch',
                'img_urls',
                'titles',
                'prices',
                'quantities',
                'taxes',
                'total',
                'question_code',
                'parentCategory'
            )
        );
        return $pdf->stream();
    }
    //new PDFEdit
    public function makeArray($forString)
    {
        $entities = [
            '<br>' => '',
            '</br>' => '',
            '<strong>' => '',
            '</strong>' => '',
            "\"" => '',
            '[' => '',
            ']' => '',
        ];
        foreach ($entities as $search => $replace) {
            $forString = str_replace($search, $replace, $forString);
        }
        $madeArray = explode(',', $forString);
        return $madeArray;
    }

    public function pdf(Request $request)
    {
        $user = User::findOrFail($request->input('user_id'));
        $survey = Survey::where('token', $request->input('id'))->first();
        $date = date('Y年n月j日', strtotime($request->input('publish')));
        $expire = date('Y年n月j日', strtotime($request->input('expire')));
        $name = $request->input('name');
        $this->getSurvey($request, $survey->token);
        $display = $this->suveyData;
        $mark = $this->surveySettingData;
        $branch = Branch::where('user_id', $request->input('user_id'))->where('survey_id', $survey->id)->first();
        if ($branch == null) {
            $branch = new Branch();
            $branch->user_id = $request->input('user_id');
            $branch->survey_id = $survey->id;
            $branch->count = 1;
        } else {
            $branch->count = $branch->count + 1;
        }
        $branch->save();

        $img_urls = $this->makeArray($request->input('img_urls'));
        $titles = $this->makeArray($request->input('titles'));
        $prices = $this->makeArray($request->input('prices'));
        $quantities = $this->makeArray($request->input('quantities'));
        $taxes = $this->makeArray($request->input('taxes'));
        $total = $request->input('total');
        $question_code = $this->makeArray($request->input('question_code'));
        $parentCategory = $this->makeArray($request->input('parentCategory'));

        $pdfArray = compact(
            'user',
            'survey',
            'date',
            'expire',
            'name',
            'branch',
            'img_urls',
            'titles',
            'prices',
            'quantities',
            'taxes',
            'total',
            'question_code',
            'parentCategory',
            'display',
            'mark'
        );
        return view('pdfEdit/index', ['pdfArray' => $pdfArray]);


    }

    public function uploadImg(Request $request)
    {
        $filename = date('ymdhis') . '.png';
        $file = $request->file;
        $file->move('public/user_product', $filename);
        //dd($filename);
        $model = new UserProduct();
        $model->brandName = "ブランド";
        $model->name = "商品名";
        $model->sku = "";
        $model->price_txt = '';
        $model->price = 0;
        $model->price2_txt = "";
        $model->price2 = 0;
        $model->flagPrice2 = "";
        $model->main_img_url = $filename;
        $model->img_urls = '[{"name":"' . $filename . '","url":"public/user_product/' . $filename . '","state":""}]';
        $model->options = '[]';
        $model->detail = '[]';
        $model->memo = "";
        $model->stock = 0;
        $model->stockLimit = "";
        $model->barcode = "";
        $model->isDisplay = "";
        $model->user_id = $request->user_id;
        $model->other = "";
        $model->save();
        return ['filename' => $filename, 'new_product_id' => $model->id];
    }
    public function uploadProfile(Request $request)
    {
        $profile_file = $request->file;
        $profile_url = '';
        $user_id = $request->user_id;
        if ($profile_file != null) {
            if (
                strtolower($profile_file->getClientOriginalExtension()) == 'png'
                || strtolower($profile_file->getClientOriginalExtension()) == 'jpg'
                || strtolower($profile_file->getClientOriginalExtension()) == 'jpeg'
                || strtolower($profile_file->getClientOriginalExtension()) == 'gif'
                || strtolower($profile_file->getClientOriginalExtension()) == 'bmp'
            ) {
                $profile_file->move('uploads/users', str_replace(' ', '_', $profile_file->getClientOriginalName()));
                $profile_url = '/uploads/users/' . str_replace(' ', '_', $profile_file->getClientOriginalName());
                User::where('id', $user_id)->update([
                    'profile_url' => $profile_url,
                ]);
            } else {
                return "image_error";
            }
        }
        return $profile_url;
    }

    public function uploadStamp(Request $request)
    {
        $stamp_file = $request->file;
        $stamp_url = '';
        $user_id = $request->user_id;
        if ($stamp_file != null) {
            if (
                strtolower($stamp_file->getClientOriginalExtension()) == 'png'
                || strtolower($stamp_file->getClientOriginalExtension()) == 'jpg'
                || strtolower($stamp_file->getClientOriginalExtension()) == 'jpeg'
                || strtolower($stamp_file->getClientOriginalExtension()) == 'gif'
                || strtolower($stamp_file->getClientOriginalExtension()) == 'bmp'
            ) {
                $stamp_file->move('uploads/users', str_replace(' ', '_', $stamp_file->getClientOriginalName()));
                $stamp_url = '/uploads/users/' . str_replace(' ', '_', $stamp_file->getClientOriginalName());
                $user_setting_tp = User::find($user_id)->settings;
                $user_setting = json_decode($user_setting_tp, true);
                $user_setting['stamp_url'] = $stamp_url;
                $user_setting_tp = json_encode($user_setting);
                User::where('id', $user_id)->update([
                    'settings' => $user_setting_tp,
                ]);
            } else {
                return "image_error";
            }
        }
        return $stamp_url;
    }

    public function uploadImgWithPath(Request $request)
    {
        $fileName = microtime(true) . ".png";
        $filePath = $request->get('filePath');
        $file = $request->file;
        $file->move($filePath, $fileName);
        return $fileName;
    }

    public function uploadImgWithPathes(Request $request)
    {
        $filePath = $request->get('filePath');
        $files = $request->file;

        $fileNames = [];
        for ($i = 0; $i < count($files); $i++) {
            $file = $files[$i];
            $fileName = microtime(true) . ".png";
            $file->move($filePath, $fileName);
            array_push($fileNames, $fileName);
        }
        return json_encode($fileNames);
    }
    public function update_inrow(Request $request)
    {

        $model = UserProduct::find($request->product_id);

        //dd(json_encode($request->ar_options));
        //dd(json_decode($model->options));
        $re_array_names = [];
        $re_array = $request->ar_options;
        //dd($re_array);
        foreach ($re_array as $re_array_element) {
            array_push($re_array_names, $re_array_element['name']);
        }

        //dd($re_array_names);

        $option_array = json_decode($model->options);
        $option_array_tp = $option_array;
        foreach ($option_array as $key => $option_element) {
            //dd($option_element->name);
            if (array_search($option_element->name, $re_array_names) === false) {
                array_push($re_array, $option_element);
            }
        }


        //dd($re_array);
        $model->name = $request->product_name;
        $model->price = $request->product_price;
        $model->options = json_encode($re_array);

        $model->save();
        return;
        //dd($model->options);

        // $category_ids = $request->get('category_ids');
        // $this->addCatetories($model->id, $category_ids);

        // return redirect()->route('admin.userProducts');
    }
}