@extends('layouts.admin', ['title' => 'ユーザー質問'])
@section('js')
    <script>
        function openSendMail() {
            $('#sendMailForm').modal('toggle');
        }
    </script>
@endsection
@section('main-content')
    <?php
    if ($message != '') {
    ?>
    <div class="alert alert-secondary alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php
    }
    ?>
    <div>
        <h2>ユーザ設問回答</h2>
    </div>

    <div class="row">
        <div class="col-md-2">ユーザ名</div>
        <div class="col-md-10">{{ $client->name }}</div>
    </div>
    <div class="row mt-1">
        <div class="col-md-2">メールアドレス</div>
        <div class="col-md-10">{{ $client->email }} </div>
    </div>
    <div class="row mt-1">
        <div class="col-md-2">メール送信状態</div>
        <div class="col-md-10">
            <?php if ($client->send_mail_status) {
                echo '送信済み';
            } else {
                echo '未送信';
            } ?>
        </div>
    </div>
    <div class="row mt-1">
        <div class="col-md-2">名前</div>
        <div class="col-md-10">{{ $client->full_name }}</div>
    </div>
    <div class="row mt-1">
        <div class="col-md-2">郵便番号</div>
        <div class="col-md-10">{{ $client->zip_code }}</div>
    </div>
    <div class="row mt-1">
        <div class="col-md-2">住所</div>
        <div class="col-md-10">{{ $client->address }}</div>
    </div>
    <div class="row mt-1">
        <div class="col-md-2">電話番号</div>
        <div class="col-md-10">{{ $client->phone_number }}</div>
    </div>
    <div class="row mt-1">
        <div class="col-md-2">設問</div>
        <div class="col-md-10">{{ $client->survey != null ? $client->survey->title : '' }}</div>
    </div>
    <div class="row mt-1">
        <button type="button" class="btn btn-primary" id="send" onclick="openSendMail()">送信</button>
    </div>
    <div class="mt-1">
        <table class="table">
            <thead>
                <tr>
                    <th>質問</th>
                    <th>回答 - 値</th>
                    <th>オプション</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach(json_decode($answers) as $answer){
                ?>
                <tr>
                    <td><?php echo $answer->question->title; ?></td>
                    <?php
                    
                    ?>
                    <td><?php foreach($answer->answerDetail as $ans) {

                        if ($ans == null) continue;

                        ?>
                        <div>
                            <?php echo $ans->title; ?>
                            -
                            <?php echo $ans->value; ?>
                        </div>
                        <?php
                        } ?>
                    </td>
                    <td>
                        @if (json_decode($answer->option) != '')
                            @if (json_decode($answer->question->settings)->answer_option == '1')
                                <img src="{{ asset(json_decode($answer->option)) }}">
                            @elseif (json_decode($answer->question->settings)->answer_option == '2')
                                <video controls width="100%">
                                    <source src="{{ asset(json_decode($answer->option)) }}" />
                                </video>
                            @endif
                        @endif
                    </td>
                </tr>
                <?php
            } ?>
            </tbody>
            <thead>
                <tr>
                    <th>小計</th>
                    <th>{{ $client->total }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <?php
    $title = '';
    
    if (json_decode($client, true)['survey'] != null) {
        $title = json_decode($client, true)['survey']['title'];
    }
    ?>
    <div id="sendMailForm" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">メール送信</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ Config::get('constants.serverHost') }}admin/client/sendMail" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $client->id }}">
                        <div class="row m-5">
                            <div class="col-md-2">ユーザ名</div>
                            <div class="col-md-10"><input type="text" name="name" value="{{ $client->name }}" />
                            </div>
                        </div>
                        <div class="row m-5">
                            <div class="col-md-2">メールアドレス</div>
                            <div class="col-md-10"><input type="text" name="email" value="{{ $client->email }}" />
                            </div>
                        </div>
                        <div class="row m-5">
                            <div class="col-md-2">メール内容</div>
                            <div class="col-md-10">
                                <?php
                                $content = '<h2>ユーザ設問回答</h2>';
                                $content .= "<p><span>ユーザ名: </span><span>$client->name</span></p>\n";
                                $content .= "<p><span>メールアドレス: </span><span>$client->email</span></p>\n";
                                $content .= "<p><span>郵便番号: </span><span>$client->zip_code</span></p>\n";
                                $content .= "<p><span>住所: </span><span>$client->address</span></p>\n";
                                $content .= "<p><span>電話番号: </span><span>$client->phone_number</span></p>\n";
                                $content .= '<p><span>設問: </span><span>' . $title . "</span></p>\n";
                                $content .= "<table><thead><th>質問</th><th>回答</th></thead>\n<tbody>";
                                foreach (json_decode($answers, true) as $answer) {
                                    $q_item = $answer['question']['title'];
                                    $a_item = '';
                                    foreach ($answer['answerDetail'] as $ans) {
                                        if ($ans == null) {
                                            continue;
                                        }
                                        $a_item .= '<div>' . $ans['title'] . '-' . $ans['value'] . "</div>\n";
                                    }
                                
                                    $content .= "<tr>\n<td>" . $q_item . "</td>\n<td>" . $a_item . "</td>\n</tr>\n";
                                }
                                $content .=
                                    "</tbody>
                                        <thead>
                                            <tr>
                                                <th>小計</th>
                                                <th>" .
                                                    $client->total .
                                                "</th>
                                            </tr>
                                        </thead>
                                        </table>\n";
                                ?>
                                <textarea type="text" rows="20" name="content">
                                     <?php echo htmlspecialchars($content); ?>
                                </textarea>
                            </div>
                        </div>
                        <div class="row modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                            <button type="submit" class="btn btn-primary" name="sendMail">送信</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
