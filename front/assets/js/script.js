/* javascriptのコードを記載 */

function newline(str) {
    str = str.replace(/(?:\r\n|\r|\n)/g, '<br>');
    return str;
}

var serverHost = 'http://192.168.146.10:9016';
var currentTab = 0;
var formular = '';
var prefix = '';
var scope = {};
var scope_count = {}; // 選択した回答の数を保管する。フッターに表示する
var func_sum = function(obj) {
    var sum = 0;
    Object.values(obj).forEach(o => sum += o);
    return sum;
};
var MODAL_O = {};
var calculate = null;
var total = 0;
var qid_history = []; // クエスチョンIDを保管していく。nextPrev()で使う

var current = 1;
var delay_time = 1500;
var total_questions = -1;
var question_order = -1;
var profile_img_url = '';
var avart_name = '';
var progress_status = -1;
var initial_data = null;
var gradient_attrs = [
    'linear-gradient(0deg, rgba(255,255,255,1) 0%, rgba(255,255,255,1) 100%)',
    'linear-gradient(160deg, rgba(63,227,220,1) 0%, rgba(51,129,251,1) 50%, rgba(112,109,246,1) 100%)',
    'linear-gradient(90deg, rgba(250,116,149,1) 0%, rgba(253,223,65,1) 100%)',
    'linear-gradient(130deg, rgba(240,147,251,1) 0%, rgba(244,87,108,1) 100%)',
    'linear-gradient(160deg, rgba(32,211,252,1) 0%, rgba(182,32,254,1) 100%)',
    'linear-gradient(180deg, rgba(243,244,135,1) 0%, rgba(151,250,194,1) 100%)'
];
const TYPE = {
    "TEXT": 1,
    "IMAGE": 2,
    "MOVIE": 3,
    "CHECK": 4,
    "RADIO": 5
};

function addQ() {
    $('#content .row-a').each(function() {
        $(this).css('min-height', '0');
    });

    var bouncing_html = '<div class="loadingContainer"><div class="ball1"></div><div class="ball2"></div><div class="ball3"></div></div>';
    var q_html = '<div id="q-' + current + '" class="row row-q" style="display: none;">';
    // q_html += '<div class="avatar"><img src="./assets/img/avatar-default.jpg" alt="" /></div>';
    // q_html += '<div class="avatar-wrapper"><div class="avatar"><img src="' + profile_img_url + '" alt="" /></div><p class="avatar-name">' + avart_name + '</p></div>';
    q_html += '<div class="avatar-wrapper"><div class="avatar" style="background: url(' + profile_img_url+ ');"></div><p class="avatar-name">' + avart_name + '</p></div>';

    var min_height = 'style="min-height: calc(100vh - ' + $('.site-header').outerHeight() + 'px);"';
    q_html += '<div class="q-area"' + min_height + '>';
    q_html += '<div id="q-txt-row-main" class="q-txt-row"><div id="q-txt-main" class="q-txt">' + bouncing_html + '</div></div>';
    q_html += '<div id="q-txt-row-sub" class="q-txt-row"><div id="q-txt-sub" class="q-txt">' + bouncing_html + '</div></div>';
    q_html += '<div class="q-a-area" style="display: none;"></div></div></div>';
    q_html += '</div>';
    $('#chatview').append(q_html);
}

function addA(a_height = 0) {
    var bouncing_html = '<div class="loadingContainer"><div class="ball1"></div><div class="ball2"></div><div class="ball3"></div></div>';
    var q_html = '<div id="a-' + current + '" class="row row-a" style="min-height: ' + a_height + 'px;">';
    q_html += '<div class="a-area"><div class="a-txt">' + bouncing_html + '</div></div>';
    q_html += '<input id="a-input-' + current + '" type="hidden" name="answers[]" />';
    q_html += '</div>';
    $('#chatview').append(q_html);
}

$(document).ready(function() {
    document.addEventListener("updateOrderList", drawOrderList);
    document.addEventListener("updateOrderList", setPDFdata);

    var date = new Date();
    var yyyy = date.getFullYear();
    var mm = ("0"+(date.getMonth()+1)).slice(-2);
    var dd = ("0"+date.getDate()).slice(-2);
    $('#previewModal .calendar.publish').val(yyyy+'-'+mm+'-'+dd);

    date.setDate(date.getDate() + 1);
    yyyy = date.getFullYear();
    mm = ("0"+(date.getMonth()+1)).slice(-2);
    dd = ("0"+date.getDate()).slice(-2);
    $('#previewModal .calendar.expire').val(yyyy+'-'+mm+'-'+dd);

    $('#progress-row').hide();
    $('.logo-img').attr('src', 'assets/img/footer_logo.png');
    if (survey_id == -1) return;

    url = serverHost + '/api/v1/survey/get/' + survey_id;
    var request = $.get(url, function(data) {
        /* settingsを事前にJSON.parseしておく */

        var settings = data.settings;

        if (settings != null && settings != '') {
            var settingArr = JSON.parse(settings);
            data.settings = settingArr;
            console.log(data.settings);
            formular = settingArr['formular'] ? decodeURIComponent(settingArr['formular']) : null;
            prefix = settingArr['prefix'];
            if (settingArr.linkUrl) {
                $('.logo-img').css('cursor', 'pointer');
                $('.logo-img').on('click', () => window.open(settingArr.linkUrl, '_blank', 'noreferrer'));
            }
            if (settingArr.how2use == '0') { // 自動見積もりの場合はプレビューボタンを表示しない
                $('.footer-order-list button').css('display', 'none');
            }
        }
        data.questions.forEach((q, i, A) => {
            var q_settings = q.settings;
            if (q_settings && q_settings != '') {
                A[i].settings = JSON.parse(q_settings);
            } else {
                A[i].settings = [];
            }
        });

        $('#brand').css('background-image', 'url("' + serverHost + '/' + data.brand_logo_path + '")');
        $('#title span').text(data.title);
        $('#brand-name').text(data.brand_name);
        if (data.qr_code) $('#qrcode-area-img').attr('src', data.qr_code);
        else $('#qrcode-area').css('display', 'none');

        if (data.brand_description != null && data.brand_description != "") {
            $('#brand-desc').show();
            $('#brand-desc').html(newline(data.brand_description));
        }

        if (data.description != null && data.description != "") {
            $('#description').show();
            $('#description').html(newline(data.description));
        }

        if (data.callout_color == null || data.callout_color == "") {
            data.callout_color = "#ffffff";
        }

        profile_img_url = serverHost + '/' + data.user_profile_url;
        avart_name = data.user_profile_name;
        progress_status = data.progress_status;

        // add_first_question(data);
        initial_data = data;

        total_questions = data.question_count;
        gradient_idx = data.gradient_color;
        var prog_html = '';
        for (i = 0; i < total_questions; i++) {
            prog_html += '<div class="prog-bar-wrap"><div id="prog-bar-' + (i + 1) + '" class="prog-bar"><span></span></div></div>';
        }
        $('#progress-inner').append(prog_html);

        var prog_bar_w = (100 / total_questions) + "%";

        style_string = '<style>';
        style_string += 'body { background-color: ' + data.background_color + ';}';
        // style_string += '.row.row-q .avatar-wrapper .avatar-name {color: ' + data.char_color + ';}';
        style_string += '.row.row-q .q-area .q-txt-row .q-txt {color: ' + data.char_color + '; background: ' + data.callout_color + ';}';
        style_string += '.row.row-q .q-area .q-txt-row .q-txt:before { border-color: transparent ' + data.callout_color + ' transparent transparent;} ';
        // style_string += '.row.row-q .q-area .q-txt-row .q-txt:after { border-color: transparent ' + data.callout_color + ' transparent transparent;} ';
        // style_string += '.row.row-q .q-area .q-a-area .q-a-list { background: rgba(' + hexToRgb(data.border_color).r + ',' + hexToRgb(data.border_color).g + ',' + hexToRgb(data.border_color).b + ', 0.3);}';
        style_string += '.row.row-q .q-area .q-a-area .q-a-list { background: ' + gradient_attrs[gradient_idx] + ';}';
        style_string += '.row.row-q .q-area .q-a-area .q-a-list .q-a-item { color: ' + data.char_color + '; background: ' + data.callout_color + ';}';
        style_string += '.row.row-q .q-area .q-a-area .q-a-list .q-a-item.selected { border-color: ' + data.border_color + ';}';
        // style_string += '.row.row-q .q-area .q-a-area .q-a-form-fields { background: rgba(' + hexToRgb(data.border_color).r + ',' + hexToRgb(data.border_color).g + ',' + hexToRgb(data.border_color).b + ', 0.3);}';
        style_string += '.row.row-q .q-area .q-a-area .q-a-form-fields { background: ' + gradient_attrs[gradient_idx] + ';}';
        style_string += '.row.row-q .q-area .q-a-area .q-a-form-fields p {color: ' + data.char_color + ';}';
        style_string += '.row.row-q .q-area .q-a-area input[type=submit] { border-color: ' + data.border_color + ';} ';
        style_string += '.row.row-a .a-area .a-txt {color: ' + data.char_color + '; background: ' + data.callout_color + ';}';
        style_string += '.row.row-a .a-area .a-txt:before { border-color: transparent transparent transparent ' + data.callout_color + ';} ';
        // style_string += '.row.row-a .a-area .a-txt:after { border-color: transparent transparent transparent ' + data.callout_color + ';} ';

        style_string += 'header .site-header { background-color: ' + data.background_color + ';} ';
        style_string += 'header .btn-start { color: ' + data.char_color + '; background: ' + data.callout_color + ';} ';
        style_string += 'header .title-desc .title span { color: ' + data.char_color + '; background: ' + data.callout_color + ';} ';
        style_string += 'header .brand-name { color: ' + data.char_color + ';} ';
        style_string += 'header .brand-desc { color: ' + data.char_color +  '; background: ' + data.callout_color + ';} ';
        style_string += 'header .logo-img-wrapper { background-color: ' + data.background_color + ';} ';

        style_string += 'header .progress-row .point { background-color: ' + data.border_color + ';} ';
        style_string += 'header .progress-row .progress-inner .prog-bar-wrap { width: ' + prog_bar_w + ';} ';
        /* style_string += 'header .progress-row .progress-inner .prog-bar-wrap { border-color: ' + data.border_color + ';} ';
        style_string += 'header .progress-row .progress-inner .prog-bar-wrap:nth-child(1) { border-color: ' + data.border_color + ';} '; */
        style_string += 'header .progress-row .progress-inner .prog-bar-wrap .prog-bar { background: linear-gradient(to left, #d9d9d9 50%, ' + data.border_color + ' 50%) right;} ';
        style_string += 'header .progress-row .progress-inner .prog-bar-wrap .prog-bar.confirmed span { background: ' + data.border_color + ';}';

        style_string += '.answer-select {border: 4px solid '+ data.border_color +';}';
        style_string += '.answer-select.selected {background-color: '+ data.callout_color +';}';

        if (data.answer_char_color != null) {
            style_string += '.answer-title { color: '+ data.answer_char_color +';}';
        }
        if (data.answer_selected_border_color != null) {
            var answer_selected_border_padding = 10;
            style_string += '.answer-select.selected:before { content: ""; position: absolute; width: calc(100% + ' + answer_selected_border_padding * 2 + 'px); height: calc(100% + ' + answer_selected_border_padding * 2 + 'px); left: -' + answer_selected_border_padding + 'px; top: -' + answer_selected_border_padding + 'px; border: 3px solid '+ data.answer_selected_border_color +';}';
        }
        style_string += '</style>';
        $('head').append(style_string);

        // フッタ部分の処理を定義　ここから
        $('.footer-order-skip').click((e) => {
            if (!$(e.target).hasClass("disabled")) {
                nextPrev(1);
            }
        });
        $('.footer-order-show').click(() => {
            $('.footer-order-bottom').removeClass('active');
            $('.footer-order-bottom').addClass('inactive');
            $('.footer-order-list').addClass('active');
            $('.footer-order-list').removeClass('inactive');
            setTimeout(() => $('.footer-order').addClass('list'), 250);
        });
        $('.footer-order-hide').click(() => {
            $('.footer-order').removeClass('list');
            $('.footer-order-bottom').addClass('active');
            $('.footer-order-bottom').removeClass('inactive');
            $('.footer-order-list').removeClass('active');
            $('.footer-order-list').addClass('inactive');
        });
        // フッタ部分の処理を定義　ここまで

        // PDF作成用の情報をセット
        $('#previewModal [name=user_id]').val(data.user_id);

        setTimeout(function() {
            $('#loading-area').hide();
        }, 100);
    })
    .done(function(data) {
    })
    .fail(function() {
    })
    .always(function() {

    });

});

function showTab(n) {
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";
    var selected = x[n].getElementsByClassName('selected');
    if (selected.length > 0){
        document.getElementById("nextBtn").disabled = false;
    } else {
        document.getElementById("nextBtn").disabled = true;
    }

    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
        $('#prevBtn').attr('disabled', false);
    }
    if (n == (x.length - 1)) {
        document.getElementById("nextBtn").style.display = "none";
        document.getElementById("submitBtn").style.display = "inline";
    } else {
        document.getElementById("nextBtn").innerHTML = "次へ";
        document.getElementById("nextBtn").style.display = "inline";
        document.getElementById("submitBtn").style.display = "none";
    }

    var req_next = x[n].getElementsByClassName('q_required')[0];
    if (req_next) {
        if (req_next.value == "true") {
            $('.footer-order-skip').addClass("disabled");
            $('.answer-skip').addClass("disabled");
        } else {
            $('.footer-order-skip').removeClass("disabled");
            $('.answer-skip').removeClass("disabled");
        }
    } else { // 送信ページのみここに入る想定。本当は良くないが、動くだろう。
        $('.footer-order-skip').addClass("disabled");
        $('.answer-skip').addClass("disabled");
    }

    window.scroll({top: 0, behavior: 'smooth'});
}

function nextPrev(n) {
    $('[data-toggle="popover"]').popover("hide");
    var x = document.getElementsByClassName("tab");
    // Hide the current tab:
    x[currentTab].style.display = "none";

    if (n == 1) {
        var nextQuestion = $('#nextBtn').data('nextQuestion');
        if (nextQuestion == null || nextQuestion == 0) {
            // Increase the current tab by 1:
            currentTab = currentTab + n;
            var qid_str = $(document.getElementsByClassName("tab")[currentTab]).attr('id');
            if (qid_str) qid_history.push(Number(qid_str.slice(2)));
            else qid_history.push(-1);
        } else {
            qid_history.push(nextQuestion);
            var y = [].slice.call(x);
            currentTab = y.indexOf(x[`q_${nextQuestion}`]);
        }
    } else {
        var qid_next = qid_history.pop();
        if (qid_next != -1) {
            $('#nextBtn').data('nextQuestion', qid_next);

            // 遷移前ページの選択の除去
            $(`#answer_q_${qid_next}`).val('[]');
            $(`#answer_q_${qid_next}_count`).val('[]');
            document.dispatchEvent(new CustomEvent("updateOrderList"));
            $(x[currentTab]).find('.answer-select.selected').removeClass('selected');
            var currentQuestion = initial_data.questions.find((q) => q.id === qid_next);
            if (currentQuestion) {
                var q_settings_array = currentQuestion.settings;
                var q_code = q_settings_array['question_code'] ?  q_settings_array['question_code']  : 'q_' + qid_next;
                scope[q_code] = 0; scope_count[q_code] = 0;
                $('.footer-order-bottom .footer-order-count').html(func_sum(scope_count));
                $('.footer-order-list .footer-order-count').html(func_sum(scope_count) + "点");
                if (calculate) {
                    total = calculate.eval(scope);
                    $('.footer-order-amount').html(total.toLocaleString() + "円");
                    $('#total_result_hidden').val(total);
                }
            }
        } else $('#nextBtn').data('nextQuestion', 0);
        var y = [].slice.call(x);
        currentTab = y.indexOf(x[`q_${qid_history.slice(-1)[0]}`]);
    }
    showTab(currentTab);
}


$('#btn-start').click(function() {
    $('#btn-start').hide();
    $('#header').slideUp(100);
    $('footer').show();

    setTimeout(function() {
        displaySurvey(initial_data);
        showTab(currentTab);
    }, 100);

});


function displaySurvey(data) {
    var questionData = data.questions;
    var q_html = '';
    if (questionData.length > 0) {
        qid_history.push(questionData[0].id);
        questionData.forEach(q => {
            var q_settings = q.settings;
            var q_required = q_settings['required'] ? q_settings['required'] : "true";
            var a_option = q_settings['answer_option'] ? q_settings['answer_option'] : 0;
            var q_code = q_settings['question_code'] ?  q_settings['question_code']  : 'q_'+q.id ;
            var select_quantity = q_settings['selectQuantity'] == 1;
            scope = {...scope, [q_code]: 0}; scope_count = {...scope_count, [q_code]: 0};
            $('.footer-order-bottom .footer-order-count').html(func_sum(scope_count));
            $('.footer-order-list .footer-order-count').html(func_sum(scope_count) + "点");

            var q_referral = data.referral.filter(re => re.id == q.referral_info);
            q_html += `
            <div class="tab" id="q_${q.id}">
              <div class="question">
                <div class="title">${q.title}
                ${q.file_url ? (
                  `
                    <div class="question-image">
                      <img src="${serverHost}/${q.file_url}">
                    </div>
                  `
                )  : ''}
                </div>
                <input type="hidden" id="answer_q_${q.id}" name="answers[${q.id}][id]" value="[]">
                <input type="hidden" id="answer_q_${q.id}_count" name="answers[${q.id}][count]" value="[]">
                <input type="hidden" id="q_${q.id}_required" value="${q_required}" class="q_required">
                ${q_referral.length > 0 ? (
                    `
                    <span class="referralInfo" data-toggle="popover" data-html="true" data-title="${q_referral[0].name}" data-content="${q_referral[0].info}">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    `
                ) : ''}
              </div>
              <div class="answerList">
                ${renderAnswer(data.answers, q.id,
                    {
                        "answer_align": q.answer_align,
                        "required": q_required,
                        "a_option": a_option,
                        "select_quantity": select_quantity
                    }
                )}
              </div>
            </div>
            `;
        });
        if (formular) {
            calculate = math.compile(formular);
            total = calculate.eval(scope);
        }
        var q_required_first = questionData[0].settings['required'] ? questionData[0].settings['required'] : "true";
        if (q_required_first == "true") {
            $('.footer-order-skip').removeClass("disabled");
            $('.answer-skip').removeClass("disabled");
        } else {
            $('.footer-order-skip').addClass("disabled");
            $('.answer-skip').addClass("disabled");
        }

        var isUseForm = data.settings.use_inputform;
        q_html += `
            <div class="tab">
                <div class="q-a-form-fields-wrapper">`;
        if (isUseForm == 1) {
            q_html += `
                    <div class="q-a-form-fields">`;
        } else {
            q_html += `
                    <div class="q-a-form-fields" style="display: none;">`;
        }
        q_html += `
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">メールアドレス</span>
                          </div>`;
        if (isUseForm == 1) {
            q_html += `
                          <input type="email" required placeholder="test@mail.com" name="email" class="form-control" aria-label="メールアドレス" aria-describedby="inputGroup-sizing-default">`;
        } else {
            q_html += `
                          <input type="email" value="input@none.com" placeholder="test@mail.com" name="email" class="form-control" aria-label="メールアドレス" aria-describedby="inputGroup-sizing-default">`;
        }
        q_html += `
                        </div>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">お名前</span>
                          </div>`;
        if (isUseForm == 1) {
            q_html += `
                          <input type="text" required placeholder="山田 太郎" name="name" class="form-control" aria-label="メールアドレス" aria-describedby="inputGroup-sizing-default">`;
        } else {
            q_html += `
                          <input type="text" value="匿名" placeholder="山田 太郎" name="name" class="form-control" aria-label="メールアドレス" aria-describedby="inputGroup-sizing-default">`;
        }
        q_html += `
                        </div>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">郵便番号</span>
                          </div>
                          <input type="text" placeholder="111-1111" name="zip_code" class="form-control" aria-label="メールアドレス" aria-describedby="inputGroup-sizing-default" onKeyUp="AjaxZip3.zip2addr(this,\'\',\'address\',\'address\');">
                        </div>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">住所</span>
                          </div>
                          <input type="text" placeholder="" name="address" class="form-control" aria-label="メールアドレス" aria-describedby="inputGroup-sizing-default">
                        </div>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">電話番号</span>
                          </div>
                          <input type="tel" placeholder="03-1234-5678" name="phone_number" class="form-control" aria-label="メールアドレス" aria-describedby="inputGroup-sizing-default">
                        </div>
                    </div>
                </div>
            </div>
        `
        q_html += `<div class="controlButton" style="overflow:auto;"></div>`;
        if (formular!= undefined) {
            q_html += `
                <div class="total">
                    <input type="hidden" id="total_result_hidden" name="total" value="${total}">
                </div>
            `
        }

    }
    $('#survey').html(q_html);
    $('[data-toggle="popover"]').popover();


}

function renderAnswer(answerList, questionID, obj) {
    if (answerList.length > 0){
        var answers = answerList.filter(answer => answer.question_id === questionID);
        var resultHtml = '';
        var hasImage = answers.filter(answer => answer.file_url).length != 0 ? true : false;
        var answerAlign = 'left';
        if(obj.answer_align == 1)  answerAlign = 'center';
        if(obj.answer_align == 2)  answerAlign = 'right';
        answers.forEach((ans) => {
            var ans_referral = initial_data.referral.filter(re => re.id == ans.referral_info);
            var price = ans.value == "" ? "" : Number(ans.value).toLocaleString() + "円　";
            if (ans.type == TYPE.TEXT) {
                resultHtml += `
                    <div class="answer-input">
                        <div class="title answer-title">
                            <div style="text-align: ${answerAlign}">${ans.title}</div>
                            <div class="answer-price">${price}</div>
                        </div>
                        ${ans.file_url ? (
                            `
                            <div class="answer-image" onclick="handleSelectAnswer(this, ${questionID}, ${ans.id}, 1)">
                              <img src="${serverHost}/${ans.file_url}">
                            </div>
                          `
                        )  : ''}
                        <input type="text" class="answer_${ans.id} answerText" oninput="handleChangeText(this, ${questionID}, ${ans.id})" />
                    </div>
                        ${ans_referral.length > 0 ? (
                            `
                            <span class="referralInfo"
                                data-toggle="popover"
                                data-html="true"
                                data-title="${ans_referral[0].name}"
                                data-content="${unescape(ans_referral[0].info)}">
                                <i class="fas fa-info-circle"></i>
                            </span>
                            `
                        ) : ''}
                `;
            }else {
                var __func = `handleSelectAnswer(this, ${questionID}, ${ans.id}, -1)`;
                var __modal = "";
                if (obj.select_quantity) {
                    __func = `setModalObject(this, ${questionID}, ${ans.id}, '${ans.file_url}', '${ans.title}', '${price}')`;
                    __modal = 'data-toggle="modal" data-target="#orderModal"';
                }
                resultHtml += `
                    <div class="answer-select">
                        <div class="title answer-title" onclick="${__func}" ${__modal}>
                            <div style="text-align: ${answerAlign}">${ans.title}</div>
                            <div class="answer-price">${price}</div>
                        </div>
                        ${ans.file_url ? (
                            `
                            <div class="answer-image" onclick="${__func}" ${__modal}>
                            <img src="${serverHost}/${ans.file_url}">
                            </div>
                        `
                        )  : ''}
                        ${ans_referral.length > 0 ? (
                            `
                            <span class="referralInfo"
                                data-toggle="popover"
                                data-html="true"
                                data-title="${ans_referral[0].name}"
                                data-content="${unescape(ans_referral[0].info)}">
                                <i class="fas fa-info-circle"></i>
                            </span>
                            `
                        ) : ''}

                    </div>
                `;
            }
        });

        // オプションをアップロードする部分の追加
        if (obj.a_option != 0) {
            let __text, __type, __accept, __tag;
            if (obj.a_option == "1") {
                __text = "画像";
                __type = 1;
                __accept = "image/*";
                __tag = "<img>";
            } else {  // obj.a_option == "2"
                __text = "動画";
                __type = 2;
                __accept = "video/*";
                __tag = '<video controls loop autoplay width="100%"></video>';
            }
            resultHtml += `
                <div class="answer-select answer-option">
                    <div class="title answer-title pc">
                        <label class="btn btn-secondary" for="question_${questionID}_file_url" style="text-align: ${answerAlign}; margin-bottom: unset;">${__text}のアップロード</label>
                        <button class="btn btn-danger" type="button" onclick="onDelete(this, 'question_${questionID}_file_url', ${__type})" style="display: none;">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    <div class="title answer-title text-center sp">
                        <label class="btn btn-sm btn-secondary" for="question_${questionID}_file_url" style="text-align: ${answerAlign}; margin-bottom: unset; font-size:small;">${__text}のアップロード</label>
                        <button class="btn btn-sm btn-danger" type="button" onclick="onDelete(this, 'question_${questionID}_file_url', ${__type})" style="display: none; font-size:small;">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    <div class="answer-image uploading d-flex align-items-center">
                        <input accept="${__accept}" type="file" value="" onChange="loadFile(event, ${__type})"
                        style="display: none;" id="question_${questionID}_file_url" name="options[${questionID}]" />
                        ${__tag}
                    </div>
                </div>
            `;
        }

        // スキップ選択の追加
        if (obj.required == "false") {
            resultHtml += `
            <div class="answer-select answer-skip">
                <div class="title answer-title" onclick="nextPrev(1)">
                    <div style="text-align: ${answerAlign}">スキップ</div>
                    <div class="answer-price"></div>
                </div>`;
            if (hasImage) {
                resultHtml += `
                    <div class="answer-image" onclick="nextPrev(1)">
                        <img src="assets/img/skip_sign.png">
                    </div>`;
                }
            resultHtml += `</div>`;
        }

        // 選択肢に画像がない場合はテキスト用のスタイルを適用する
        if (!hasImage) {
            style_string = '<style>';
            style_string += `#q_${questionID} .answerList .answer { flex-direction: column; align-content: center; }`;
            style_string += `#q_${questionID} .answer-select { padding-bottom: 0px; width: 20rem; }`;
            style_string += `#q_${questionID} .answer-option.uploaded { padding-bottom: 300px; }`;
            style_string += `#q_${questionID} .answer-option .answer-image.uploading { height: 0px; }`;
            style_string += '</style>';
            $('head').append(style_string);
        }

        return `
            <div class="answer">
            ${resultHtml}
            </div>
        `;
    }

}
function handleChangeText(element, questionID, answerID) {
    var answerList = initial_data.answers;
    var currentAnswer = answerList.find((a) => a.id === answerID);
    if (currentAnswer) {
        $(`#answer_q_${questionID}`).val(element.value);
        $('#nextBtn').data('nextQuestion', currentAnswer.next_question_id);

        $('#nextBtn').attr('disabled', true);
        if (element.value != '') {
            $('#nextBtn').attr('disabled', false);
            $('#prevBtn').attr('disabled', false);
        }
        document.dispatchEvent(new CustomEvent("updateOrderList"));
    }
}

function setModalObject(element, questionID, answerID, imgsrc, title, price) {
    MODAL_O.elm = element;
    MODAL_O.qID = questionID;
    MODAL_O.aID = answerID;
    if (imgsrc && imgsrc != 'null') {
        $('#orderModal .modal-body img').prop('src', serverHost + "/" + imgsrc);
    } else {
        $('#orderModal .modal-body img').prop('src', '');
    }
    $('#orderModal .product-title').text(title);
    $('#orderModal .product-price').text(price);
    
    var listAnsArr = JSON.parse($(`#answer_q_${questionID}`).val());
    var index = listAnsArr.indexOf(answerID);
    if (listAnsArr.length > 0 && index > -1) {
        var listAnsCountArr = JSON.parse($(`#answer_q_${questionID}_count`).val());
        $('#quantity').val(listAnsCountArr[index]);
        MODAL_O.quantity = listAnsCountArr[index];
    } else {
        $('#quantity').val('1');
        MODAL_O.quantity = 0;
    }
}

function handleSelectAnswer(element, questionID, answerID, __quantity) {
    var answerList = initial_data.answers;
    var questionList = initial_data.questions;
    var currentAnswer = answerList.find((a) => a.id === answerID);
    var currentQuestion = questionList.find((q) => q.id === questionID);
    var quantity = __quantity ? __quantity : Number($('#quantity').val());
    if (currentAnswer) {
        var q_settings = currentQuestion.settings;
        var q_code = '';
        var a_limit = 1;
        var q_req = "true";
        if (q_settings && q_settings != '') {
            q_code = q_settings['question_code'] ?  q_settings['question_code']  : 'q_' + currentQuestion.id;
            q_req = q_settings['required'] ?  q_settings['required']  : "true";

            if (currentAnswer.type === TYPE.CHECK) {
                a_limit = q_settings['limit'] ? q_settings['limit'] : 0;
            } else {
                a_limit = 1;
            }
        }

        // #region 次へ・スキップの表示制御とチェックボックスの選択数カウント
        if (element) {
            if (quantity == -1) {
                // モーダルを表示せず、クリックでON/OFFする場合の対応
                if (element.parentElement.classList.contains('selected')) {
                    quantity = 0;
                } else {
                    quantity = 1;
                }
            }

            var parents = element.parentElement.parentElement.children;
            if (quantity === 0) {
                element.parentElement.classList.remove('selected');
            }
            else if (currentAnswer.type === TYPE.CHECK) {
                element.parentElement.classList.add('selected');
            } else {
                for (var i = 0; i < parents.length; i++) {
                    parents[i].classList.remove('selected');
                }
                element.parentElement.classList.add('selected');
            }

            var selectCount = 0;
            for (var i = 0; i < parents.length; i++) {
                if (parents[i].classList.contains('selected')) {
                    selectCount++;
                }
            }

            $('#nextBtn').attr('disabled', true);
            $('.footer-order-skip').addClass("disabled");
            $('.answer-skip').addClass("disabled");
            if (a_limit == 0) { // チェックボックスの時だけ入る
                if (q_req == "true" && selectCount != 0) $('#nextBtn').attr('disabled', false);
                else if (q_req == "false") {
                    if (selectCount == 0) {
                        $('.footer-order-skip').removeClass("disabled");
                        $('.answer-skip').removeClass("disabled");
                    } else {
                        $('#nextBtn').attr('disabled', false);
                    }
                }
            } else if (selectCount == 0) {
                if (q_req == "false") {
                    $('.footer-order-skip').removeClass("disabled");
                    $('.answer-skip').removeClass("disabled");
                }
            } else if (selectCount <= a_limit) {
                $('#nextBtn').attr('disabled', false);
            } else {  // a_limit < selectCount
                element.parentElement.classList.toggle('selected');
                $('#nextBtn').attr('disabled', false);
                window.alert("回答数の上限を超えております。");
                return;
            }
        }
        //#endregion 次へ・スキップの表示制御とチェックボックスの選択数カウント

        var newValue = 0;
        if (currentAnswer.type === TYPE.CHECK) {
            var listAnsArr = JSON.parse($(`#answer_q_${questionID}`).val());
            var listAnsCountArr = JSON.parse($(`#answer_q_${questionID}_count`).val());
            var index = listAnsArr.indexOf(answerID);
            if (listAnsArr.length > 0 && index > -1) { // 選択してあるものを対象としたとき
                if (quantity == 0) { // 選択削除
                    listAnsArr.splice(index, 1);
                    var tmp = listAnsCountArr.splice(index, 1)[0];
                    if (scope[q_code] > Number(currentAnswer.value)) {
                        newValue = Number(scope[q_code]) - Number(currentAnswer.value) * tmp;
                    } else {
                        newValue = 0;
                    }
                    if (Number(currentAnswer.value) != 0) scope_count[q_code] -= tmp;
                } else { // 個数変更
                    var tmp = listAnsCountArr[index]; // 元の個数
                    var diff = quantity - tmp; // 元の個数と今の個数の差分
                    newValue = Number(scope[q_code]) + Number(currentAnswer.value) * diff;
                    listAnsCountArr[index] = quantity;
                    if (Number(currentAnswer.value) != 0) scope_count[q_code] += diff;
                }
            } else { // 選択していないものを対象としたとき
                if (quantity != 0) {
                    listAnsArr.push(answerID);
                    listAnsCountArr.push(quantity);
                    newValue = Number(scope[q_code]) + Number(currentAnswer.value) * quantity;
                    if (Number(currentAnswer.value) != 0) scope_count[q_code] += quantity;
                } else { /* quantity == 0 nothing to do */ }
            }
            $(`#answer_q_${questionID}`).val(JSON.stringify(listAnsArr));
            $(`#answer_q_${questionID}_count`).val(JSON.stringify(listAnsCountArr));
            // 自動で次のページへ
            if (a_limit > 0 && selectCount == a_limit) {
                $('body').css('pointer-events', 'none');
                setTimeout(() => {
                    nextPrev(1);
                    $('body').css('pointer-events', '');
                }, 1000);
            }

        } else { // 回答種別がチェックボックス以外の時
            $(`#answer_q_${questionID}`).val(JSON.stringify([answerID]));
            $(`#answer_q_${questionID}_count`).val(JSON.stringify([quantity]));
            newValue = Number(currentAnswer.value) * quantity;
            if (Number(currentAnswer.value) != 0) scope_count[q_code] = quantity;
            else scope_count[q_code] = 0;
            // 自動で次のページへ
            if (quantity != 0) {
                $('body').css('pointer-events', 'none');
                setTimeout(() => {
                    nextPrev(1);
                    $('body').css('pointer-events', '');
                }, 1000);
            }
        }
        $('#nextBtn').data('nextQuestion', currentAnswer.next_question_id);
        scope = {...scope, [q_code]: newValue };
        if (calculate) {
            total = calculate.eval(scope);
            $('.footer-order-amount').html(total.toLocaleString() + "円");
            $('#total_result_hidden').val(total);
        }
        $('.footer-order-bottom .footer-order-count').html(func_sum(scope_count));
        $('.footer-order-list .footer-order-count').html(func_sum(scope_count) + "点");
        document.dispatchEvent(new CustomEvent("updateOrderList"));
    }
}


function add_first_question(data) {
    addQ();

    $('#q-' + current).show();
    EPPZScrollTo.scrollVerticalToElementById('end-anchor', 20);

    $(document).trigger('get-question', [data.first_question.id]);
}

$(document).bind('get-question', function(event, id) {
    url = serverHost + '/api/v1/questions/get/' + id;
    var request = $.get(url, function(data) {
        question_order = data.ord;
        setTimeout(function() {
            $('#q-' + current + ' .q-txt-row#q-txt-row-main .q-txt').html(newline(data.title));

            if (data.sub_title == null || data.sub_title == '') {
                $(document).trigger('show-answer-list', [data]);
            } else {
                $(document).trigger('show-add-question', [data]);
            }
        }, delay_time);
    })
    .done(function(data) {
    })
    .fail(function() {
    })
    .always(function() {
    });
});

$(document).bind('show-add-question', function(event, data) {
    $('#q-' + current + ' .q-txt-row#q-txt-row-sub').show();
    setTimeout(function() {
        $('#q-' + current + ' .q-txt-row#q-txt-row-sub .q-txt').html(newline(data.sub_title));
        $(document).trigger('show-answer-list', [data]);
    }, delay_time);
});

function getYoutubeID(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);

    return (match && match[2].length === 11)
      ? match[2]
      : null;
}

$(document).bind('show-answer-list', function(event, data) {
    type = data.type;
    answers = data.answers;
    next_id = data.next_question_id;
    a_area_html = '<div class="q-a-area-wrapper">';
    if (type == TYPE.IMAGE) {
        a_area_html += '<div class="q-a-img"><div class="q-a-img-wrapper"><img src="'+ serverHost +'/' + data.file_url + '"></div></div>';
    } else if (type == TYPE.MOVIE) {
        a_area_html += '<div class="q-a-video"><div class="q-a-video-wrapper">';
        if (data.movie_file != null && data.movie_file != '') {
            a_area_html += '<video controls autoplay loop muted>';
            a_area_html += '<source src="' + serverHost + data.movie_file + '" type="video/mp4">';
            a_area_html += '</video>';
        } else if (data.movie_source != null && data.movie_source != '') {
            a_area_html += newline(data.movie_source);
        } else if (data.movie_url != null && data.movie_url != '') {
            var youtube_id = getYoutubeID(data.movie_url);
            if (youtube_id != null) {
                var youtube_iframe = '<iframe width="100%" height="315" src="//www.youtube.com/embed/' + youtube_id + '?rel=0&autoplay=1&mute=1&loop=1" frameborder="0" allowfullscreen></iframe>';
                a_area_html += youtube_iframe;
            } else {
                a_area_html += '<video controls autoplay loop muted>';
                a_area_html += '<source src="' + data.movie_url + '" type="video/mp4">';
                a_area_html += '</video>';
            }
        }

        a_area_html += '</div></div>';
    }

    a_area_html += '<div class="q-a-list">';
    for (i = 0; i < answers.length; i++) {
        if (answers[i].type != TYPE.IMAGE) {
            a_area_html += '<div class="q-a-item">' + newline(answers[i].title) + '</div>';
        } else {
            // a_area_html += '<div class="q-a-item"><div class="q-a-item-img"><img src="http://formstylee.com/public/' + answers[i].file_url + '"></div>' + newline(answers[i].title) + '</div>';
            a_area_html += '<div class="q-a-item"><div class="q-a-item-img" style="background: url('+ serverHost + '/' + answers[i].file_url + '"></div>' + newline(answers[i].title) + '</div>';
        }
    }
    a_area_html += '</div></div>';

    setTimeout(function() {
        $('#q-' + current + ' .q-a-area').show();
        $('#q-' + current + ' .q-a-area').html(a_area_html);

        $('#q-' + current + ' .q-a-area .q-a-img .q-a-img-wrapper img').one("load", function() {
            // EPPZScrollTo.scrollVerticalToElementById('end-anchor', 20);
            img_loaded = true;
        });
        $('#q-' + current + ' .q-a-area .q-a-list .q-a-item .q-a-item-img img').one("load", function() {
            // EPPZScrollTo.scrollVerticalToElementById('end-anchor', 20);
            img_loaded = true;
        });
        EPPZScrollTo.scrollVerticalToElementById('end-anchor', 20);

        $('#q-' + current + ' .q-a-list .q-a-item').click(function() {
            if ($(this).parent().hasClass('processed')) {
                return;
            }

            $(this).addClass('selected');

            sel_a_idx = $('#q-' + current + ' .q-a-list .q-a-item').index(this);
            $(this).parent().addClass('processed');

            $(this).parent().parent().parent().parent().css('min-height', 'auto');
            // EPPZScrollTo.scrollVerticalToElementById('end-anchor', 20);

            var a_height = $(window).height() - ($(this).parent().parent().parent().parent().outerHeight() + $('.site-header').outerHeight() + 16);

            addA(a_height);

            setTimeout(function() {
                $('#a-' + current + ' .a-txt').html('「' + answers[sel_a_idx].title + '」です。');
                $('#a-input-' + current).val(answers[sel_a_idx].id);

                for (i = 0; i < (question_order + 1); i++) {
                    var prog_bar_id = "#prog-bar-" + (i + 1);
                    if (!$(prog_bar_id).hasClass('confirmed')) {
                        $(prog_bar_id).addClass('confirmed');
                    }
                }

                if (answers[sel_a_idx].next_question_id != 0) {
                    next_id = answers[sel_a_idx].next_question_id;
                }

                current++;
                addQ();
                $('#q-' + current).show();
                EPPZScrollTo.scrollVerticalToElementById('end-anchor', 20);
                if (next_id != null && next_id != 0) {
                    $(document).trigger('get-question', [next_id]);
                } else {
                    $(document).trigger('show-last-message', [data]);
                }
            }, delay_time);
        });
    }, delay_time);
});

$(document).bind('show-last-message', function(event, data) {
    setTimeout(function() {
        $('#q-' + current + ' .q-txt').html('アンケート内容を送信してください。');
        var form_html = '<div class="q-a-form-fields-wrapper"><div class="q-a-form-fields">';
        form_html += '<div class="q-a-form-field-row"><p>メールアドレス</p><input type="email" placeholder="test@mail.com" name="email" /></div>';
        form_html += '<div class="q-a-form-field-row"><p>お名前</p><input type="text" placeholder="山田 太郎" name="full_name" /></div>';
        form_html += '<div class="q-a-form-field-row"><p>郵便番号11</p><input type="text" placeholder="111-1111" name="zip_code" onKeyUp="AjaxZip3.zip2addr(this,\'\',\'address\',\'address\');"/></div>';
        form_html += '<div class="q-a-form-field-row"><p>住所</p><input type="text" placeholder="" name="address"/></div>';
        form_html += '<div class="q-a-form-field-row"><p>電話番号</p><input type="tel" placeholder="03-1234-5678" name="phone_number" /></div>';
        form_html += '</div>';
        form_html += '<input type="submit" value="送信する"></div>';
        $('#q-' + current + ' .q-a-area').append(form_html);
        $('#q-' + current + ' .q-a-area').show();
        EPPZScrollTo.scrollVerticalToElementById('end-anchor', 20);
    }, delay_time);
});

loadFile = function (e, t) {
    var p = t == 1 ? $(e.target).parent().find('img') : $(e.target).parent().find('video');
    $(p).attr('src', window.URL.createObjectURL(e.target.files[0]));
    $(p).parent().removeClass('uploading');
    $(p).parent().parent().addClass('uploaded');
    if ($(p).parent().parent().parent().find('.answerText').length != 0) {
        // テキスト + 画像アップロードオプションのときに進めるようにする暫定対応
        $(p).parent().parent().parent().find('.answerText').val("アップロード完了").trigger('input');
    }
    $(p).show();
    $(p).parent().parent().find('.btn-danger').css('display', 'inline-block');
}

qRightPlus = function (e, type) {
    e.preventDefault();
    var targetInput = $(e.target).parent().parent().find('input');
    var quantity = parseInt(targetInput.val());
    $(targetInput).val(quantity + 1);
    if (type == 'footer') {
        handleSelectAnswer( null, $(targetInput).data('qid'), $(targetInput).data('aid'), quantity + 1 );
    }
}

qLeftMinus = function (e, type) {
    e.preventDefault();
    var targetInput = $(e.target).parent().parent().find('input');
    var quantity = parseInt(targetInput.val());
    var limit = type == 'modal' ? 0 : 1;
    if (quantity > limit) {
        $(targetInput).val(quantity - 1);
    } else {
        return;
    }
    if (type == 'footer') {
        handleSelectAnswer( null, $(targetInput).data('qid'), $(targetInput).data('aid'), quantity - 1 );
    } else { // type == 'modal'
        if (quantity - 1 <= MODAL_O.quantity) {
            $('#orderModal .modal-footer button').text('更新');
        } else {
            $('#orderModal .modal-footer button').text('追加');
        }
    }
}

drawOrderList = function() {
    var questions = initial_data.questions;
    var padding = 20; //子要素のずらす幅
    var child_ranges = {}; //ずらす幅を保存

    if (questions.length > 0) {
        $('.footer-order-list .pc .item').empty();
        $('.footer-order-list .sp .item').empty();
        questions.forEach(q => {
            var listAns = $(`#answer_q_${q.id}`).val();
            var listAnsArr;
            var listAnsCountArr = JSON.parse($(`#answer_q_${q.id}_count`).val());
            try {
                listAnsArr = JSON.parse(listAns);
            } catch (e) {
                // テキストの場合、来てしまう。いずれ対処。
                listAnsArr = [];
            }

            listAnsArr.forEach((e, i) => {
                var ans = initial_data.answers.find(a => a.id == e);
                var image_url = ans.file_url ? `<img src="${serverHost}/${ans.file_url}">` : ``;
                var quantity = listAnsCountArr[i];
                var price = ans.value == "" ? "" : Number(ans.value).toLocaleString() + "円";
                var display = price == "" ? "display: none;" : "";
                var question_code = q.settings.question_code;
                var parent_code = q.settings.parentCategory;
                var select_quantity = q.settings.selectQuantity;

                //子要素ずらす幅設定
                if (!([question_code] in child_ranges)){
                    if (parent_code == "なし"){
                        child_ranges[question_code] = 0;
                    } else if ([parent_code] in child_ranges){
                        child_ranges[question_code] = child_ranges[parent_code] + padding;
                    } else {
                        child_ranges[question_code] = 0;
                    }
                }

                var child_range = child_ranges[question_code];

                // #region pc用のリストの生成
                var resultHtml = `
                    <div class="row m-0">
                        <div style="width: 3.5rem; margin-left:${child_range}px;">${image_url}</div>
                        <div class="col m-auto pc" style="display: flex;">
                            <span class="fw-b">${ans.title}</span>
                        </div>
                        <span class="m-auto pc" style="width: 100px;">${price}</span>
                        <div class="col m-auto fd-c sp" style="display: flex;">
                            <span class="fw-b">${ans.title}</span>
                            <span>${price}</span>
                        </div>
                        <div class="m-auto" style="width: 113px;">
                            <div class="counter" style="${display}">`;
                if (select_quantity == 0){
                    resultHtml = resultHtml + `
                                <div class="input-group">
                                    <input type="text" class="form-control input-number" data-qid="${q.id}" data-aid="${ans.id}"
                                        value="${quantity}" style="text-align: center; pointer-events: none;">
                                </div>`;
                } else {
                    resultHtml = resultHtml + `
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button type="button" class="quantity-left-minus btn btn-outline-primary btn-number"
                                        onClick="qLeftMinus(event, 'footer')" data-type="minus" data-field="">
                                            <span class="glyphicon glyphicon-minus" style="pointer-events: none;">-</span>
                                        </button>
                                    </span>
                                    <input type="text" class="form-control input-number" data-qid="${q.id}" data-aid="${ans.id}"
                                        value="${quantity}" style="text-align: center; pointer-events: none;">
                                    <span class="input-group-btn">
                                        <button type="button" class="quantity-right-plus btn btn-outline-primary btn-number"
                                        onClick="qRightPlus(event, 'footer')" data-type="plus" data-field="">
                                            <span class="glyphicon glyphicon-plus" style="pointer-events: none;">+</span>
                                        </button>
                                    </span>
                                </div>`;
                }
                resultHtml = resultHtml + `
                            </div>
                        </div>
                    </div>
                    <hr class="my-2">`;
                // #endregion pc用のリストの生成
                $('.footer-order-list .pc .item').append(resultHtml);
                // #region スマホ用のリストの生成　使わなくなったけどおいておく
                var resultHtml = `
                    <div class="row m-0">
                        <div class="col-5 d-flex align-items-center">
                            <img src="${serverHost}/${ans.file_url}">
                        </div>
                        <div class="col-7 p-0">
                            <div class="m-auto fw-b text-center">
                                ${ans.title}
                            </div>
                            <div class="m-auto fw-b text-center">
                                ${price}
                            </div>
                            <div class="m-aauto counter py-2" style="width: 113px;">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button type="button" class="quantity-left-minus btn btn-outline-primary btn-number"
                                        onClick="qLeftMinus(event, 'footer')" data-type="minus" data-field="">
                                            <span class="glyphicon glyphicon-minus" style="pointer-events: none;">-</span>
                                        </button>
                                    </span>
                                    <input type="text" class="form-control input-number" data-qid="${q.id}" data-aid="${ans.id}"
                                        value="${quantity}" style="text-align: center; pointer-events: none;">
                                    <span class="input-group-btn">
                                        <button type="button" class="quantity-right-plus btn btn-outline-primary btn-number"
                                        onClick="qRightPlus(event, 'footer')" data-type="plus" data-field="">
                                            <span class="glyphicon glyphicon-plus" style="pointer-events: none;">+</span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                `;
                // #endregion スマホ用のリストの生成
                // $('.footer-order-list .sp .item').append(resultHtml);
            });
        });
    }
}

setPDFdata = function() {
    var questions = initial_data.questions;
    if (questions.length > 0) {
        var img_urls = [];
        var titles = [];
        var prices = [];
        var quantities = [];
        var taxes = [];
        var question_code = [];
        var parentCategory = [];

        questions.forEach(q => {
            var listAns = $(`#answer_q_${q.id}`).val();
            var listAnsArr;
            var listAnsCountArr = JSON.parse($(`#answer_q_${q.id}_count`).val());

            try {
                listAnsArr = JSON.parse(listAns);
            } catch (e) {
                // テキストの場合、来てしまう。いずれ対処。
                listAnsArr = [];
            }

            listAnsArr.forEach((e, i) => {
                var ans = initial_data.answers.find(a => a.id == e);
                if (ans.file_url) {
                    img_urls.push(`${serverHost}/${ans.file_url}`);
                } else {
                    img_urls.push(``);
                }
                titles.push(ans.title);
                prices.push(ans.value);
                quantities.push(listAnsCountArr[i]);
                taxes.push(ans.tax);
                question_code.push(q.settings.question_code);
                parentCategory.push(q.settings.parentCategory);
            });
        });

        $(`#previewModal [name=img_urls]`).val(JSON.stringify(img_urls));
        $(`#previewModal [name=titles]`).val(JSON.stringify(titles));
        $(`#previewModal [name=prices]`).val(JSON.stringify(prices));
        $(`#previewModal [name=quantities]`).val(JSON.stringify(quantities));
        $(`#previewModal [name=taxes]`).val(JSON.stringify(taxes));
        $(`#previewModal [name=total]`).val(total);
        $(`#previewModal [name=question_code]`).val(JSON.stringify(question_code));
        $(`#previewModal [name=parentCategory]`).val(JSON.stringify(parentCategory));
    }
}

onDelete = function(e, id, t) {
    var p = t == 1 ? $(e).parent().parent().find('img') : $(e).parent().parent().find('video');
    $(p).attr('src', '');
    $(e).css('display', 'none');
    $(`#${id}`)[0].value = '';
}