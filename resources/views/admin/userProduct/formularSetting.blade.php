@extends('layouts.admin', ['title' => '式の設定'])


@section('main-content')
    <?php
    echo '<script>';
    echo 'var surveys = ' . json_encode($survey) . ';';
    echo 'var questions = ' . json_encode($questions) . ';';
    echo '</script>';
    ?>
    <form method="post" id="formularSetting" action="{{ route('admin.formularSetting.save') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <input type="hidden" name="id" value="{{$survey['id']}}">
            <input type="button" onclick="submitFormular()" class="btn btn-primary" value="保存">
        </div>
        <div class="row m-2">
            <div class="col-7 p-2">
                <?php
                $formular = '';
                $surveySettings = null;
                if (isset($survey['settings'])) {
                    $surveySettings = json_decode($survey['settings']);
                    $formular = urldecode(isset($surveySettings->formular) ? $surveySettings->formular : '' );
                }

                ?>
                <textarea ondrop="dropFormular(event)" id="formularSettingDrop" rows="20" ondragover="allowDrop(event)">{{$formular}}</textarea>
                <input type="hidden" id="formularValue" name="formular" value="{{isset($surveySettings->formular) ? $surveySettings->formular : ''}}">
            </div>

            <div class="col-5 p-2">
                <div class="row p-5">
                    <div class="label">
                        質問一覧
                    </div>
                    <div class="list listQuestion">
                        @foreach( $questions as $question)
                            <?php
                            $questionSetting = json_decode($question->settings);
                            ?>

                            <div class="question" draggable="true" ondragstart="dragFormular(event, 'question', '{{$questionSetting->question_code}}')">{{$question->title}}</div>
                        @endforeach
                    </div>
                </div>
                <div class="row p-5">
                    <div class="label">
                        フォーミュラ一覧
                    </div>
                    <div class="list listMath">
                        <div class="math plus" draggable="true" ondragstart="dragFormular(event,'math' ,'plus')">+</div>
                        <div class="math minus" draggable="true" ondragstart="dragFormular(event,'math' ,'minus')">-</div>
                        <div class="math multiple" draggable="true" ondragstart="dragFormular(event,'math' ,'multiple')">*</div>
                        <div class="math divide" draggable="true" ondragstart="dragFormular(event,'math' ,'divide')">/</div>
                        <div class="math openBracket" draggable="true" ondragstart="dragFormular(event,'math' ,'open')">(</div>
                        <div class="math closeBracket" draggable="true" ondragstart="dragFormular(event,'math' ,'close')">)</div>
                    </div>
                </div>
            </div>
            <div class="col-12 p2">
                <input type="button" onclick="formClear()" class="btn btn-secondary" value="クリア">
            </div>
        </div>
    </form>

@endsection
