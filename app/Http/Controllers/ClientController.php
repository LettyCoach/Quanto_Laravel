<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Client;
use App\Models\ClientAnswer;
use App\Models\ClientOption;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $message = $request->get('message');
        if(Auth::user()->isAdmin()) {
            $clients = Client::simplePaginate(20);
        } else {
            $clients = Client::where('email',Auth::user()->email)
                ->simplePaginate(20);
        }
        return view('admin/client/index', ['clients' => $clients, 'message' => $message]);
    }

    public function show(Request $request, $id){
        $message = $request->get('message');
        $answers = ClientAnswer::where('client_id', $id)->get();
        $client = Client::find($id);
        $survey_id = $client->survey_id;
        $resultAnswer = [];
        forEach($answers as $answer) {
            $result = [];
            $answerIds = json_decode($answer->answer_id);
            foreach($answerIds as $answerId) {
                $ans = Answer::find($answerId);
                $result['answerDetail'][] = $ans;
            }

            $question = Question::find($answer->question);
            $result['question'] = $question;
            $result['answer'] = $answer;
            $tmp_option = ClientOption::where('client_id', $id)->where('question_id', $answer->question)->first();
            $tmp_option = isset($tmp_option) ? $tmp_option->file_url : "";
            $result['option'] = json_encode($tmp_option);
            $resultAnswer[] = $result;
            Log::debug(json_encode($resultAnswer));

        }

        return view('admin/client/show', ['client' => $client, 'answers' => json_encode($resultAnswer), 'message' => $message]);
    }
    public function clientSendMail(Request $request) {
        $content = $request->get('content');
        $email = $request->get('email');
        $name = $request->get('name');
        $clientID = $request->get('id');
        $client = Client::find($clientID);

        if (! $client) {
            return redirect()->route('admin.clients', ['message' => '???????????????????????????????????????????????????????????????????????????']);
        }
        if ($email == '') {
            return redirect()->route('admin.client.show', ['id' => $clientID, 'message' => '?????????????????????????????????????????????????????????????????????']);
        }
        if ($content == '') {
            return redirect()->route('admin.client.show', ['id' => $clientID, 'message' => '???????????????????????????????????????????????????????????????']);
        }
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = "utf-8";
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host = Config::get('constants.mail.host');
            $mail->Port = Config::get('constants.mail.port');
            $mail->Username = Config::get('constants.mail.username');
            $mail->Password = Config::get('constants.mail.password');
            $mail->setFrom(Config::get('constants.mail.admin_email'), Config::get('constants.mail.admin_name'));
            $mail->Timeout = 30;
            $mail->Subject = "????????????";
            $mail->MsgHTML($content);
            $mail->addAddress($email, $name);

            $mail->send();
            $client->send_mail_status = 1;
            $client->save();
        } catch (phpmailerException $e) {
            $client->send_mail_status = 0;
            $client->save();
//            dd($e);
            return redirect()->route('admin.client.show', ['id' => $clientID, 'message' => $e]);

        } catch (Exception $e) {
            $client->send_mail_status = 0;
            $client->save();
//            dd($e);
            return redirect()->route('admin.client.show', ['id' => $clientID, 'message' => $e]);

        }
        return redirect()->route('admin.client.show', ['id' => $clientID, 'message' => '??????????????????????????????']);
    }
}
