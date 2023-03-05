@extends('layouts.admin', ['title' => '設問'])

@section('js')
<script src="{{ asset('public/js/survey.js') }}"></script>
{{-- <script src="{{ asset('public/js/drag.js') }}"></script>--}}
<script src="{{ asset('public/js/lib/clipboard.min.js') }}"></script>
<script>
    var btns = document.querySelectorAll('.clipboard');
    var clipboard = new ClipboardJS(btns);

    clipboard.on('success', function(e) {
        $('.clip-toast').addClass('open');
        setTimeout(function() {
            $('.clip-toast').removeClass('open');
        }, 1000);
    });

    clipboard.on('error', function(e) {
        console.log(e);
    });
</script>
<script>
    $("#paymentStatus").change(function() {
        if (this.checked) {
            $("#singlePay").hide();
            $("#multiPay").show();
            $("#deliveryMethods").show();
        } else {
            $("#multiPay").hide();
            $("#deliveryMethods").hide();
            $("#singlePay").show();
        }
    });
</script>
@endsection
<?php
echo '<script>';
echo 'var GradientList = ' . json_encode(GRADIENT_COLOR) . ';';
echo 'var referral_info = ' . json_encode($referral_info) . ';';
echo '</script>';
$surveySettings = isset($survey['settings']) ? json_decode($survey['settings']) : [];
?>
@section('main-content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css" integrity="sha512-mR/b5Y7FRsKqrYZou7uysnOdCIJib/7r5QeJMFvLNHNhtye3xJp1TdJVPLtetkukFn227nKpXD9OjUc09lx97Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="row">
    <div class="col-8">
        <form class="" id="survey" method="post" action="{{ route('admin.survey.save') }}" enctype="multipart/form-data">
            @csrf
            <div class="row" style="align-items: baseline;">
                <input id="saveSurvey" type="submit" class="btn btn-primary float-left" value="保存" style="margin-right:50px">
                <div class="d-flex mb-3" style="width:350px">
                    <label class="col-form-label mr-3">支払いを有効化にする:</label>
                    <div id="cardPayment" class="switch_box box_1 d-flex align-items-center">
                        <input type="checkbox" class="switch_1" id="paymentStatus" name="payment_status" {{ (isset($survey['payment_status']) && $survey['payment_status'] == 1) ? 'checked' : '' }}>
                    </div>
                </div>
                <label class="mr-3 col-form-label" for="">利用方法: </label>
                <div>
                    <?php $how2use = isset($surveySettings->how2use) ? $surveySettings->how2use : '0'; ?>
                    <input type="radio" value="0" id="how2useEstimate" name="how2use" {{$how2use == 0 ? 'checked' : ''}} />
                    <label for="how2useEstimate">自動見積もり</label>
                    <input type="radio" value="1" id="how2useInvoice" name="how2use" {{$how2use == 1 ? 'checked' : ''}} />
                    <label for="how2useInvoice">インボイス</label>
                </div>
            </div>
            <div class="row">
                @if (isset($survey['id']))
                <span class="col-md-2 col-form-label">ID:{{$survey['id']}}</span>
                <input type="hidden" name="id" value="{{$survey['id']}}">
                @endif
                <span class="col-md-2 d-flex align-items-center">リンク先URL:</span>
                <span class="col-md-8 d-flex align-items-center">
                    <input class="form-control" type="text" placeholder="https://..." name="linkUrl" value="{{ isset($surveySettings->linkUrl) ? $surveySettings->linkUrl : '' }}">
                </span>
            </div>

            <div class="form-group row">

                <label class="col-md-1 col-form-label">色設定</label>
                <div class="col-md-8 align-items-center">
                    <div class="row">
                        <div class="col-md-12 d-flex align-items-center">
                            <label class="col-form-label mr-3">全体背景:</label>
                            <input class="" type="color" name="background_color" value="{{ isset($survey['background_color']) ? $survey['background_color'] : '#ffffff' }}">
                            <label class="col-form-label mx-3">文字:</label>
                            <input class="" type="color" name="char_color" value="{{ isset($survey['char_color']) ? $survey['char_color'] : '#000000' }}">
                            <label class="col-form-label mx-3">枠カラー:</label>
                            <input class="" type="color" name="border_color" . value="{{ isset($survey['border_color']) ? $survey['border_color'] : '#785cff' }}">
                            <label class="col-form-label mx-3">回答文言:</label>
                            <input class="" type="color" name="answer_char_color" value="{{ isset($survey['answer_char_color']) ? $survey['answer_char_color'] : '#000000' }}">
                            <label class="col-form-label mx-3">回答選択:</label>
                            <input class="" type="color" name="answer_selected_border_color" value="{{ isset($survey['answer_selected_border_color']) ? $survey['answer_selected_border_color'] : '#ffffff' }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 d-flex align-items-center">
                            <label class="col-form-label mx-3">背景:</label>
                            <input class="" type="color" name="callout_color" value="{{ isset($survey['callout_color']) ? $survey['callout_color'] : '#785cff' }}">
                            <div class="d-flex align-items-center">
                                <label class="col-form-label mx-3">グラデーション:</label>
                                <span class="gradient_background"></span>
                                <div class="col-md-5">
                                    <select name="gradient_color" class="form-control">
                                        @foreach(GRADIENT_COLOR as $key => $item)
                                        <option value="{{ $key }}" {{ (isset($survey['gradient_color']) && $survey['gradient_color'] == $key) ? 'selected' : '' }}>{{ $item[1] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-center form-inline">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="col-form-label mr-3">ステータス:</label>
                            <select class="form-control" name="status">
                                @foreach ($statuses as $status)
                                <option value="{{ $status->id }}" {{ $status->id == $survey['status'] ? 'selected' : ''}}>{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 d-flex">
                            <label class="col-form-label mr-3">進捗状況ステータスバー:</label>
                            <div class="switch_box box_1 d-flex align-items-center">
                                <input type="checkbox" class="switch_1" name="progress_status" {{ (isset($survey['progress_status']) && $survey['progress_status'] == 1) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div id="show_logo">
                <?php if (isset($survey['profile_path'])) { ?>
                    <img src="{{asset($survey['profile_path'])}}" class="fs-profile-image">
                <?php } else { ?>
                    <img src="#" class="fs-profile-image" style="display: none;">
                <?php } ?>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">ブランドロゴ</label>
                <div class="col-md-6 d-flex align-items-center">
                    <input type="file" name="profile_path" onchange="loadFile(event, '#show_logo img')" data-showimage="#show_log img" accept="image/png, image/gif, image/jpeg">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">　　ブランド名 <br>(会社またはユーザ名)
                </label>
                <div class="col-md-6 d-flex align-items-center">
                    <input type="text" name="brand_name" class="form-control" value="{{ isset($survey['brand_name']) ? $survey['brand_name'] : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">紹介文: </label>
                <div class="col-md-9 d-flex align-items-center">
                    <textarea class="form-control" placeholder="紹介文" name="brand_description">{{ isset($survey['brand_description']) ? $survey['brand_description'] : '' }}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="">タイトル: </label>
                <div class="col-md-10 d-flex align-items-center">
                    <input class="form-control" type="text" placeholder="タイトル" name="title" value="{{ isset($survey['title']) ? $survey['title'] : '' }}" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="">説明: </label>
                <div class="col-md-10">
                    <textarea class="form-control" placeholder="説明" name="description">{{ isset($survey['description']) ? $survey['description'] : '' }}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="">用途: </label>
                <div class="col-md-4">
                    <select name="purpose" class="form-control">
                        <?php $purpose = isset($surveySettings->purpose) ? $surveySettings->purpose : ''; ?>
                        <option value="" {{ ('' == $purpose) ? 'selected' : '' }}>選択なし</option>
                        @foreach($purposes as $key => $item)
                        <option value="{{ $item->name }}" {{ ($item->name == $purpose) ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="col-md-2 col-form-label" for="">担当者: </label>
                <div class="col-md-4">
                    <select name="member" class="form-control">
                        <?php $member = isset($surveySettings->member) ? $surveySettings->member : ''; ?>
                        <option value="" {{ ('' == $member) ? 'selected' : '' }}>選択なし</option>
                        @foreach($members as $key => $item)
                        <option value="{{ $item->name }}" {{ ($item->name == $member) ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="">支払方法:</label>
                <div class="col-md-4" id="singlePay" @if (isset($survey['payment_status']) && $survey['payment_status']==1) style="display:none;" @endif>
                    <select name="payment_method" class="form-control">
                        <?php $payment_method = isset($surveySettings->payment_method) ? $surveySettings->payment_method : ''; ?>
                        <option value="" {{ ('' == $payment_method) ? 'selected' : '' }}>選択なし</option>
                        @foreach($payment_methods as $key => $item)
                        <option value="{{ $item->name }}" {{ ($item->name == $payment_method) ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4" id="multiPay" @if (isset($survey['payment_status']) && $survey['payment_status']==1) style="display:block;" @else style="display:none;" @endif>
                    <?php $pay_methods = isset($surveySettings->pay_methods) ? json_decode($surveySettings->pay_methods) : array(); ?>
                    <select name="pay_methods[]" class="form-control selectpicker" multiple placeholder="選択なし">
                        <option value="お振込" @if ( in_array('お振込' ,$pay_methods)==true) selected @endif>お振込</option>
                        <option value="クレジットカード" @if ( in_array('クレジットカード' ,$pay_methods)==true) selected @endif>クレジットカード</option>
                        <option value="代引き" @if ( in_array('代引き' ,$pay_methods)==true) selected @endif>代引き</option>
                        <option value="店舗受取り" @if ( in_array('店舗受取り' ,$pay_methods)==true) selected @endif>店舗受取り</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex">
                    <label class="col-form-label mr-5">入力フォームの利用:</label>
                    <div class="switch_box box_1 d-flex align-items-center">
                        <?php $use_inputform = isset($surveySettings->use_inputform) ? $surveySettings->use_inputform : 1; ?>
                        <input type="checkbox" class="switch_1" name="use_inputform" {{ ($use_inputform == 1) ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6" id="deliveryMethods" @if (isset($survey['payment_status']) && $survey['payment_status']==1) style="display:block;" @else style="display:none;" @endif>
                    <div class="row">
                        <label class="col-md-4 col-form-label" for="">受け取り方法: </label>
                        <?php $delivery_methods = isset($surveySettings->delivery_methods) ? json_decode($surveySettings->delivery_methods) : array(); ?>
                        <div class="col-md-8">
                            <select name="delivery_methods[]" class="form-control selectpicker" multiple placeholder="選択なし">
                                <option value="通販" @if ( in_array('通販' ,$delivery_methods)==true) selected @endif>通販</option>
                                <option value="店舗受取り" @if ( in_array('店舗受取り' ,$delivery_methods)==true) selected @endif>店舗受取り</option>
                                <option value="デリバリー" @if ( in_array('デリバリー' ,$delivery_methods)==true) selected @endif>デリバリー</option>
                            </select>
                        </div>
                    </div>
                </div>
                <label class="col-md-2 col-form-label" for="">小計のプレフィックス: </label>
                <div class="col-md-4">
                    <?php $prefix = isset($surveySettings->prefix) ? $surveySettings->prefix : ''; ?>
                    <input class="form-control" type="text" placeholder="円" name="totalPrefix" value="<?php echo $prefix; ?>" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="">ユーザにメールを送ります: </label>
                <div class="col-md-4 d-flex align-items-center">
                    <?php $autoSendMail = isset($surveySettings->autoSendMail) ? $surveySettings->autoSendMail : 0; ?>
                    <input type="checkbox" class="switch_1" {{ $autoSendMail == 1 ? 'checked' : ''; }} name="autoSendMail" />
                </div>
                <label class="col-md-2 col-form-label" for="">画面上の配置: </label>
                <div class="col-md-4 d-flex align-items-center">
                    <?php $widget_align = isset($surveySettings->widgetAlign) ? $surveySettings->widgetAlign : '0'; ?>
                    <input type="radio" value="0" id="widgetAlignLeft" name="widgetAlign" {{$widget_align == 0 ? 'checked' : ''}} />
                    <label class="m-0" for="widgetAlignLeft">左寄せ</label>
                    　<input type="radio" value="1" id="widgetAlignRight" name="widgetAlign" {{$widget_align == 1 ? 'checked' : ''}} />
                    <label class="m-0" for="widgetAlignRight">右寄せ</label>
                </div>
            </div>
            <input type="hidden" value="{{ isset($surveySettings->displayQrcode) ? $surveySettings->displayQrcode : 0 }}" name="disp_qrcode">
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="">フォーミュラ: </label>
                <div class="col-md-8 d-flex align-items-center">
                    <?php $formular = isset($surveySettings->formular) ? urldecode($surveySettings->formular) : ''; ?>
                    <input class="form-control" type="text" readonly value="{{$formular}}">
                </div>
                <input type="button" class="btn btn-secondary" onclick="formularSetting()" value="式の設定">
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="">備考: </label>
                <div class="col-md-10">
                    <textarea class="form-control" placeholder="備考" name="note">{{ isset($surveySettings->note) ? $surveySettings->note : '' }}</textarea>
                </div>
            </div>

            <div id="questions-container" class="sortableArea">
                @php
                $q_index = 0;
                @endphp
                @if (isset($questions))
                @php
                $questionList = [];
                foreach ($questions as $l_item){
                    $l_settings = json_decode($l_item->settings, true);
                    $questionList[$l_settings['question_code']] = $l_item->title;
                }
                @endphp
                @foreach( $questions as $index => $question)
                @php
                $next_questions = [['id'=>0, 'title'=>'']];
                foreach ($questions as $q_item){
                if ($question->ord < $q_item->ord){
                    $next_questions[] = ['id' => $q_item->id, 'title'=>$q_item->title];
                    }
                    }
                    $q_index = $question->id;

                    $q_settings = json_decode($question->settings, true);
                    $q_required = isset($q_settings['required']) ? $q_settings['required'] : "true";
                    $q_limit = isset($q_settings['limit']) ? $q_settings['limit'] : 0;
                    $a_option = isset($q_settings['answer_option']) ? $q_settings['answer_option'] : '0';
                    $selectQuantity = isset($q_settings['selectQuantity']) ? $q_settings['selectQuantity'] : 0;
                    $parentCategory = isset($q_settings['parentCategory']) ? $q_settings['parentCategory'] : "なし";
                    $parentTitle = isset($questionList[$parentCategory]) ? ":".$questionList[$parentCategory] : "";
                @endphp
                <div class="ui-state-default">
                <div id="question_{{$question->id}}_wrapper">
                <section class="accordion">
                    <input id="block-{{$question->id}}" type="checkbox" class="ac_toggle">
                    <label id="label-{{$question->id}}"class="ac_Label" for="block-{{$question->id}}">質問{{$index + 1}}：{{$question->title}}</label>
                    <div id="content-{{$question->id}}" class="ac_content">
                        <div class="question" id="question_{{$question->id}}">
                            <input type="hidden" class="questionID" value="{{$question->id}}" name="questions[q_{{$q_index}}][id]">
                            <input type="hidden" value="{{$question->type}}" name="questions[q_{{$q_index}}][type]">
                            <input type="hidden" value="{{$q_required}}" name="questions[q_{{$q_index}}][required]">
                            <input type="hidden" value="{{$q_limit}}" name="questions[q_{{$q_index}}][limit]">
                            <div class="row form-group ">
                                <label class="col-md-2 pl-1 col-form-label d-flex align-items-center question-index-{{$index}}">質問{{$index + 1}}:</label>
                                <div class="col-md-8">
                                    <textarea placeholder="質問" class="form-control" name="questions[q_{{$q_index}}][title]" required>{{$question->title}}</textarea>
                                </div>
                                <div class="col-md-1 d-flex align-items-center">
                                    <button type="button" class="btn btn-danger buttonDelete" onclick="onDeleteQuestion('{{$q_index}}')">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary buttonEdit" style="display: block" onclick="onEdit('question_{{$q_index}}')"><i class="fa fa-pen"></i></button>
                                </div>
                            </div>
                            <div class="row form-group ">
                                <label class="ml-2 pl-1 col-form-label d-flex align-items-center">質問コード</label>
                                <div class="col-md-8">
                                    <input class="question_code" type="text" value="{{json_decode($question->settings, true)['question_code']}}" name="questions[q_{{$q_index}}][question_code]" />
                                </div>
                            </div>
                            <div class="row form-group ">
                                <label class="ml-2 pl-1 col-form-label d-flex align-items-center">親カテゴリ</label>
                                <div class="col-md-8">
                                    <select class="form-control parentCategory" disabled name="questions[q_{{$q_index}}][parent_category]">
                                        <option value="{{$parentCategory}}" {{$parentCategory != 'なし' ? 'selected' : ''}}>{{$parentCategory}}{{$parentTitle}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group ">
                                <label class="ml-2 pl-1 col-form-label d-flex align-items-center">関連情報</label>
                                <div class="col-md-8">
                                    <select class="form-control questionReferralInfo" disabled name="questions[q_{{$q_index}}][referral_info]">
                                        <option value=""></option>
                                        @foreach($referral_info as $ref)
                                        <option value="{{$ref->id}}" {{$ref->id === $question->referral_info ? 'selected' : ''}}>{{$ref->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="ml-2 pl-1 col-form-label d-flex align-items-center">回答整列</label>
                                <div class="col-md-8">
                                    <select class="form-control" disabled name="questions[q_{{$q_index}}][answer_align]" id="questionAnswerAlign_{{$q_index}}">
                                        <option value="0" {{$question->answer_align == 0 ? 'selected':''}}>左寄せ</option>
                                        <option value="1" {{$question->answer_align == 1 ? 'selected':''}}>センター</option>
                                        <option value="2" {{$question->answer_align == 2 ? 'selected':''}}>右寄せ</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="ml-2 pl-1 col-form-label d-flex align-items-center">数量の選択</label>
                                <div class="col-md-8">
                                    <input type="checkbox" class="switch_1" disabled {{ $selectQuantity == 1 ? 'checked' : ''; }} name="questions[q_{{$q_index}}][select_quantity]" id="questionSelectQuantity_{{$q_index}}">
                                </div>
                            </div>
                            <div class="row show_img{{$q_index}}">
                                <?php if ($question->file_url) { ?>
                                    <img src="{{ asset($question->file_url) }}" class="col fs-question-image mb-2">
                                <?php } else { ?>
                                    <img src="" class="col fs-question-image mb-2" style="display: none">
                                <?php } ?>
                            </div>
                            <div class="row form-group">
                                <div class="col-md">
                                    <input onchange="loadFile(event, '.modal-body .show_img{{$q_index}} img')" accept="image/png, image/gif, image/jpeg" type="file" class="form-control" name="questions[q_{{$q_index}}][file_url]">
                                </div>
                            </div>

                            <div class="d-flex mb-1">
                                <div id="answers_{{$q_index}}" class="d-flex answerDropArea flex-wrap" ondrop="dropAnswer(event)">
                                    @php
                                    $parents = [['id' => 0, 'title' => '']];
                                    foreach($answers as $item){
                                    if($item->question_id == $question->id && $item->type == 3){
                                    $parents[] = ['id' => $item->id, 'title' => $item->title];
                                    }
                                    }
                                    $checkbox_radio_item = [];
                                    foreach($answers as $item){
                                    if($item->question_id == $question->id && ($item->type == 4 || $item->type == 5 )){
                                    $checkbox_radio_item[] = $item->id;
                                    }
                                    }
                                    $hasRadio = false;
                                    @endphp
                                    @foreach($answers as $answerIndex => $answer)

                                    @if ($answer->type != 4 && $answer->type != 5 && $answer->question_id == $question->id)
                                    <?php
                                    if ($hasRadio) {
                                        $hasRadio = false;
                                    ?>
                                </div>
                            </div>
                        <?php
                                    }
                        ?>
                        <div class="answer card mr-2 mb-2" id="_answer_{{$answer->id}}">
                            <div class="card-header d-flex justify-content-between p-2" ondrop="dropAnswerOption(event)">
                                @if ($answer->type == 1)
                                <span>回答 テキスト</span>
                                @else
                                <span>回答</span>
                                @endif
                                <button class="text-danger buttonDeleteAnswer" type="button" onclick="onDelete('_answer_{{$answer->id}}')"><i class="fa fa-times"></i></button>
                            </div>
                            <div class="card-media" id="_answer_option_{{$q_index}}" ondrop="dropAnswerOption(event)">
                                オプション：
                                @if ($a_option == '0')
                                <span>無し</span>
                                @else
                                <span>
                                    @if ($a_option == '1')
                                    画像
                                    @elseif ($a_option == '2')
                                    動画
                                    @endif
                                </span>
                                <button type="button" class="text-danger buttonDeleteAnswer" onclick="onDeleteAnswerOption('_answer_option_{{$q_index}}')"><i class="fa fa-times"></i></button>
                                @endif
                                <input type="hidden" value="{{$a_option}}" name="questions[q_{{$q_index}}][answer_option]">
                            </div>
                            <div class="card-body p-2">
                                <input type="hidden" value="{{$answer->id}}" name="questions[q_{{$q_index}}][answers][a_{{$answer->id}}][id]">
                                <input type="hidden" value="{{$answer->type}}" name="questions[q_{{$q_index}}][answers][a_{{$answer->id}}][type]">

                                <textarea placeholder="回答" class="form-control" name="questions[q_{{$q_index}}][answers][a_{{$answer->id}}][title]" required>{{$answer->title}}</textarea>
                                @if (count($parents) > 1 && $answer->type != 3)
                                集団
                                <select class="form-control mt-1" name="questions[q_{{$q_index}}][answers][a_{{$answer->id}}][parent_id]">
                                    @foreach($parents as $item)
                                    <option value="{{ $item['id'] }}" {{$item['id'] == $answer->parent_id ? 'selected' : ''}}>{{ $item['title'] }}</option>
                                    @endforeach
                                </select>
                                @endif
                                @if (count($next_questions) > 1)
                                次の問題
                                <select class="form-control mt-1" name="questions[q_{{$q_index}}][answers][a_{{$answer->id}}][next_question_id]">
                                    @foreach($next_questions as $n_item)
                                    <option value="{{ $n_item['id'] }}" {{$n_item['id'] == $answer->next_question_id ? 'selected' : ''}}>{{ $n_item['title'] }}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if (($answer->type == 4 || $answer->type == 5) && $answer->question_id == $question->id)
                        @if (!$hasRadio)
                        <div class="answer card mr-2 mb-2" id="_answer_{{$answer->id}}">
                            <div class="card-header d-flex justify-content-between p-2" ondrop="dropAnswerOption(event)">
                                @if ($answer->type == 4)
                                <span>回答 チェックボックス</span>
                                @elseif ($answer->type == 5)
                                <span>回答 ラジオボタン</span>
                                @endif
                                <button class="text-danger buttonDeleteAnswer" type="button" onclick="onDelete('_answer_{{$answer->id}}', this)"><i class="fa fa-times"></i></button>
                            </div>
                            <div class="card-media" id="_answer_option_{{$q_index}}" ondrop="dropAnswerOption(event)">
                                オプション：
                                @if ($a_option == '0')
                                <span>無し</span>
                                @else
                                <span>
                                    @if ($a_option == '1')
                                    画像
                                    @elseif ($a_option == '2')
                                    動画
                                    @endif
                                </span>
                                <button type="button" class="text-danger buttonDeleteAnswer" onclick="onDeleteAnswerOption('_answer_option_{{$q_index}}')"><i class="fa fa-times"></i></button>
                                @endif
                                <input type="hidden" value="{{$a_option}}" name="questions[q_{{$q_index}}][answer_option]">
                            </div>
                            <div class="card-body p-2 row">
                                <?php
                                $aCount = 1;
                                ?>
                                @endif
                                <?php
                                if ($hasRadio) {
                                    $aCount++;
                                }
                                $hasRadio = true;
                                ?>

                                <div class="col-6 p-1" id="_answer_{{$answer->id}}_sub_{{$aCount}}">
                                    <input type="hidden" value="{{$answer->id}}" name="questions[q_{{$q_index}}][answers][a_{{$answer->id}}][id]">
                                    <input type="hidden" value="{{$answer->type}}" name="questions[q_{{$q_index}}][answers][a_{{$answer->id}}][type]">
                                    <button type="button" class="text-danger buttonDeleteAnswer" onclick="onDelete('_answer_{{$answer->id}}_sub_{{$aCount}}')"><i class="fa fa-times"></i></button>
                                    <label>回答{{$aCount}}</label>
                                    <input placeholder="回答" class="form-control" name="questions[q_{{$q_index}}][answers][a_{{$answer->id}}][title]" required value="{{$answer->title}}" />
                                    <label>税込価格</label>
                                    <input placeholder="税込価格" class="form-control" name="questions[q_{{$q_index}}][answers][a_{{$answer->id}}][value]" required value="{{$answer->value}}" />
                                    <label>税率</label>
                                    <select class="form-control tax" disabled name="questions[q_{{$q_index}}][answers][a_{{$answer->id}}][tax]">
                                        <option value=""></option>
                                        <option value="8" {{ $answer->tax == 8 ? 'selected' : ''}}>8%</option>
                                        <option value="10" {{ $answer->tax == 10 ? 'selected' : ''}}>10%</option>
                                    </select>
                                    <label>関連情報</label>
                                    <select class="form-control referralInfo" disabled name="questions[q_{{$q_index}}][answers][a_{{$answer->id}}][referral_info]">
                                        <option value=""></option>
                                        @foreach($referral_info as $ref)
                                        <option value="{{$ref->id}}" {{$ref->id == $answer->referral_info ? 'selected' : ''}}>{{$ref->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="row show_img_{{$q_index}}_{{$answer->id}}">
                                        <?php if ($answer->file_url) { ?>
                                            <img src="{{ asset($answer->file_url) }}" class="col fs-question-image mb-2">
                                        <?php } else { ?>
                                            <img src="" class="col fs-question-image mb-2" style="display: none">
                                        <?php } ?>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md">
                                            <input onchange="loadFile(event, '.modal-body .show_img_{{$q_index}}_{{$answer->id}} img')" accept="image/png, image/gif, image/jpeg" type="file" class="form-control" name="questions[q_{{$q_index}}][answers][a_{{$answer->id}}][file_url]">
                                        </div>
                                    </div>
                                    @if (count($parents) > 1)
                                    集団
                                    <select class="form-control mt-1" name="questions[q_{{$q_index}}][answers][a_{{$answer->id}}][parent_id]">
                                        @foreach($parents as $item)
                                        <option value="{{ $item['id'] }}" {{$item['id'] == $answer->parent_id ? 'selected' : ''}}>{{ $item['title'] }}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                    @if (count($next_questions) > 1)
                                    次の問題
                                    <select class="form-control mt-1 next_question_id" name="questions[q_{{$q_index}}][answers][a_{{$answer->id}}][next_question_id]">
                                        @foreach($next_questions as $n_item)
                                        <option value="{{ $n_item['id'] }}" {{$n_item['id'] == $answer->next_question_id ? 'selected' : ''}}>{{ $n_item['title'] }}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                </div>

                                {{-- @php $answer->id++;--}}
                                {{-- @endphp--}}
                                @endif
                                @endforeach
                                @if ($hasRadio)
                                @php $hasRadio = false;
                                @endphp
                            </div>
                            <button class="btn btn-primary" onclick="onNewField({{$q_index}}, {{$answer->type}}, {{$q_index}})"><i class="fa fa-plus"></i></button>
                        </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        </div>
            <div class="while-btn-add-survey-wrapper">
                <button type="button" class="btn-primary btn-add-survey while-btn-add-survey" data-toggle="modal" data-target="#modalAddQuestion" onclick="readOrderCount(this);">
                    +
                </button>
            </div>
        </div>
    @endforeach
    @endif
</div>

</form>
<div id="btn-add-survey-area">
    <button id="btn-add-survey" class="btn btn-primary btn-add-survey" data-toggle="modal" data-target="#modalAddQuestion">
        +
    </button>
</div>
</div>
<?php
$clientHost = \Illuminate\Support\Facades\Config::get('constants.clientHost');
$cartHost = \Illuminate\Support\Facades\Config::get('constants.cartHost');
$adminHost = \Illuminate\Support\Facades\Config::get('constants.adminHost');
?>
<div class="col-4">

    <div class="card">
        更新日: {{ $survey['updated_at'] }}
    </div>
    <div class="card">
        <h6 class="form-control-label m-3">
            @if (isset($survey['payment_status']) && $survey['payment_status'] == 1)
            URL: <a href="<?php echo $cartHost ?>{{ $survey['token'] }}" target="_blank"><?php echo $cartHost ?>{{ $survey['token'] }}</a>
            <br><br>
            <?php $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate($cartHost . $survey['token']) ?>
            @else
            URL: <a href="<?php echo $clientHost ?>?id={{ $survey['token'] }}" target="_blank"><?php echo $clientHost ?>?id={{ $survey['token'] }}</a>
            <br><br>
            <?php $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate($clientHost . '?id=' . $survey['token']) ?>
            @endif
            <div class="row align-items-center">
                {!! $qrCode !!}
                　QRコードを表示:
                <div class="switch_box box_1 d-flex">
                    <input type="checkbox" class="switch_1" name="disp_qrcode_dummy" {{ (isset($surveySettings->displayQrcode) && $surveySettings->displayQrcode == 1) ? 'checked' : '' }}>
                </div>
            </div>
        </h6>
        <h5 class="card-header">
            プレビュー
        </h5>
        <div class="card-body preview" id="preview" style="background: {{ isset($survey['background_color']) ? $survey['background_color'] : '#eeebff' }}">
            <div class="row">
                <div class="col-1">
                    @if (isset($survey['profile_path']))
                    <div>
                        <img src="{{asset($survey['profile_path'])}}" class="fs-profile-image small">
                    </div>
                    @endif
                </div>
                <div class="col-10">
                    <div id="preview-text" class="ml-4" style="border: 1px solid {{ isset($survey['border_color']) ? $survey['border_color'] : '#785cff' }}; background: {{ isset($survey['callout_color']) ? $survey['callout_color'] : '#785cff' }}; color: {{ isset($survey['char_color']) ? $survey['char_color'] : '#785cff' }}">
                        テキストプレビュー
                    </div>
                    <div class="card" id="preview-img" style="border: 4px solid {{ isset($survey['border_color']) ? $survey['border_color'] : '#785cff' }}">
                        <img class="card-img-top" src="{{asset('public/img/preview_img.png')}}">
                        <div class="card-body preview-gradient" style="background: {{ isset($survey['gradient_color']) ? GRADIENT_COLOR[$survey['gradient_color']][0] : 'white' }};">
                            テキストプレビュー
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <?php
        $iFrameSource = '';
        $fullSource = '';
        if (isset($survey['token'])) {
            if ($widget_align == '0') {
                $widget_align_css = 'left: 0;';
            } else if ($widget_align == '1') {
                $widget_align_css = 'right: 0;';
            }
            $iFrameSource = htmlspecialchars('<iFrame src="' . $clientHost . '?id=' . $survey['token'] . '" width="500px" height="90%" name="quanto3" frameborder="0"></iFrame><style>iFrame[name=quanto3]{position:fixed;' . $widget_align_css . 'bottom:0;margin:25px;border-radius:25px;z-index:10000;}@media(max-width:550px){iFrame[name=quanto3]{width:100%;margin:0 0 25px 0;}}</style>');
            $fullSource = htmlspecialchars('
                        <div class="quanto-embbed">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="' . $clientHost . 'assets/css/style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/5.0.0/math.js"></script>
                       <header id="header" class="header">
                            <div class="site-header">
                                <div class="site-header-inner">
                                    <div class="brand-wrapper">
                                        <div id="brand" class="brand"><img src="" alt="" /></div>
                                        <p id="brand-name" class="brand-name"></p>
                                    </div>
                                    <div id="brand-desc" class="brand-desc"></div>
                                    <div id="title-desc" class="title-desc">
                                        <h1 id="title" class="title"><span></span></h1>
                                        <p id="description" class="description"></p>
                                    </div>
                                    <div id="btn-start" class="btn-start">START</div>
                                    <div id="progress-row" class="progress-row">
                                        <div class="point"></div>
                                        <div id="progress-inner" class="progress-inner"></div>
                                        <div class="point"></div>
                                    </div>
                                </div>
                            </div>
                        </header>
                        <div id="content" class="content">
                            <form action="' . $adminHost . '/api/v1/client/save" method="POST">
                                <input type="hidden" name="survey_id" value="' . $survey['token'] . '" />
                                <div id="survey" class="survey">
                                </div>

                            </form>
                        </div>
                        <script type="text/javascript">
                            var survey_id = "' . $survey['token'] . '";
                        </script>
                        <script src="' . $clientHost . '/assets/js/script.js"></script>
                        <script type="text/javascript">
                            $(document).ready(function(){
                                $(\'[data-toggle="popover"]\').popover();
                            });
                        </script>
                        </div>

                    ');

        ?>
            <div>
                iFrameまたはすべてソースをコピーしてください
            </div>
            <div class="embbed-html">
                <div>
                    <a class="clipboard iframe-html-clipboard" style="cursor: pointer;" title="コピー" data-clipboard-text="<?php echo $iFrameSource; ?>"><i class="fa fa-clipboard mr-1"></i>コピーします</a>
                </div>
                <div class="iframe-html"><?php echo $iFrameSource; ?></div>
            </div>
            <div class="embbed-html">
                <div>
                    <a class="clipboard" style="cursor: pointer;" title="コピー" data-clipboard-text="<?php echo $fullSource; ?>"><i class="fa fa-clipboard mr-1"></i>コピーします</a>
                </div>
                <?php echo $fullSource; ?>
            </div>
        <?php
        }

        ?>


    </div>
</div>
</div>
<div class="clip-toast">
    ソースをコピーしました。
</div>
<style>
    .bootstrap-select>.dropdown-toggle {
        background: #fff;
        border: 1px solid #cbd5e0;
        height: 40px;
        padding: .575rem .87rem;
    }

    #multiPay .bootstrap-select>.dropdown-toggle.bs-placeholder {
        color: #2d3748;
    }

    #deliveryMethods .bootstrap-select>.dropdown-toggle.bs-placeholder {
        color: #2d3748;
    }

    .bootstrap-select.show-tick .dropdown-menu .selected span.check-mark {
        top: 0;
        right: 25px;
        background-color: #d2e7ff;
        padding: 5px;
        border-radius: 50%;
        width: 1.8em;
        height: 1.8em;
        color: #2e75cd;
    }

    .bootstrap-select .bs-ok-default:after {
        border-width: 0 .21em .21em 0;
        margin-left: 0.28em;
        margin-top: -0.12em;
    }
</style>
@include('admin/survey/question', ['title' => '質問追加', 'questionTypes' => $question_types, 'answerTypes' => $answer_types, 'answerOptions' => $answer_options, 'referral_info' => $referral_info])
@include('admin/survey/modal', ['title' => '回答追加', 'modal_id' => 'AddAnswer', 'items' => $answer_types])

@endsection
