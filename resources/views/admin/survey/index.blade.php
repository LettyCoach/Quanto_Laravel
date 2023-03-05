@extends('layouts.admin', ['title' => '設問'])
@section('js')
    <script src="{{ asset('public/js/lib/clipboard.min.js') }}"></script>
    <script>
        var btns = document.querySelectorAll('.clipboard');
        var clipboard = new ClipboardJS(btns);

        clipboard.on('success', function (e) {
            $('.clip-toast').addClass('open');
            setTimeout(function() {
                $('.clip-toast').removeClass('open');
            }, 1000);
        });

        clipboard.on('error', function (e) {
            console.log(e);
        });
    </script>
@endsection
@section('main-content')
    <div>
        <div class="action-container">
            <a class="btn btn-primary" href="{{ route('admin.survey.add') }}">新規追加</a>
        </div>
    </div>
    <div class="mt-2">
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>用途</th>
                <th>タイトル</th>
                <th>ステータス</th>
                <th>更新日</th>
                <th>担当者</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($surveys as $survey)
                <?php
                    $clientHost = \Illuminate\Support\Facades\Config::get('constants.clientHost');
                    $surveySettings = isset($survey['settings']) ? json_decode($survey['settings']) : [];
                    $userSettings = isset(Auth::user()->settings) ? json_decode(Auth::user()->settings) : [];

                    $purpose_survey = isset($surveySettings->purpose) ? $surveySettings->purpose : '';
                    $purpose_user = isset($userSettings->purpose) ? $userSettings->purpose : '';
                    $purpose =  $purpose_survey != '' ? $purpose_survey : $purpose_user;

                    $member_survey = isset($surveySettings->member) ? $surveySettings->member : '';
                    $member_user = isset($userSettings->member) ? $userSettings->member : '';
                    $member =  $member_survey != '' ? $member_survey : $member_user;
                ?>
                <tr>
                    <td>{{ $survey->id }}</td>
                    <td>{{ $purpose }}</td>
                    <td>{{ $survey->title }}</td>
                    <td>{{ $survey->statuses->name }}</td>
                    <td>{{ $survey->updated_at }}</td>
                    <td>{{ $member }}</td>
                    <td>
                        <a class="btn btn-info clipboard" data-clipboard-text="<?php echo $clientHost; ?>?id={{ $survey->token }}"
                        title="<?php echo $clientHost; ?>?id={{ $survey->token }}">共有</a>
                        <a class="btn btn-primary" href="{{ route('admin.survey.edit',['id'=>$survey->id]) }}">編集</a>
                        <a class="btn btn-danger" href="{{ route('admin.survey.delete',['id'=>$survey->id]) }}">削除</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $surveys->links() }}
    </div>
    <div class="clip-toast">
        URLをコピーしました。
    </div>
    <style>
        .clip-toast {
            position: fixed;
            font-size: 12px;
            background: #00000088;
            width: fit-content;
            padding: 8px 16px;
            border-radius: 4px;
            right: 50px;
            bottom: 50px;
            opacity: 0;
            visibility: hidden;
            transition: all 300ms cubic-bezier(0.335, 0.01, 0.03, 1.36);
            color: white;
        }
        .open {
            opacity: 1;
            visibility: visible;
        }
    </style>
@endsection
