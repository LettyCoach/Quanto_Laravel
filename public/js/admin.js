var q_index = 100000;
var a_index = 0;
var DRAG_TYPE = {
    QUESTION: 1,
    ANSWER: 2,
    OPTION: 3,
};
var currentQuestionId = null;
var orderCount = null;
var questionData = [];
var isModified = false;
var isSaved = false;

(function ($) {
    window.addEventListener('beforeunload', e => {
        if (isModified) e.returnValue = "";
    });

    if (document.getElementById('saveSurvey')) {
        document.getElementById('saveSurvey').addEventListener('click', () => isModified = false);
    }

    function closeModalHandler() {
        if ($(`#question_${currentQuestionId}_wrapper`).length > 0) {
            //edit saved question
            $(`#content-${currentQuestionId}`).html($('.dropArea .question'));
            var str = $(`#label-${currentQuestionId}`).text();
            $(`#label-${currentQuestionId}`).text(str.slice(0, str.indexOf('：') + 1) + $(`[name="questions[q_${currentQuestionId}][title]"]`).val());
        } else {
            //add new question
            $('#questions-container').append(`
                <div class="ui-state-default">
                    <div id="question_${q_index}_wrapper">
                        <div class="while-btn-add-survey-wrapper">
                            <button type="button" class="btn-primary btn-add-survey while-btn-add-survey" data-toggle="modal" data-target="#modalAddQuestion">
                                +
                            </button>
                        </div>
                    </div>
                </div>
            `);

            $(`#question_${q_index}_wrapper`).append(`
                <section class="accordion">
                    <input id="block-${q_index}" type="checkbox" class="ac_toggle">
                    <label id="label-${q_index}" class="ac_Label" for="block-${q_index}">質問${count_questions}</label>
                    <div id="content-${q_index}" class="ac_content"></div>
                </section>
            `);

            console.log('---id', $(`[name=questions\\[q_${q_index}\\]\\[id\\]]`).val())
            console.log('---type', $(`[name=questions\\[q_${q_index}\\]\\[type\\]]`).val())
            console.log('---require', $(`[name=questions\\[q_${q_index}\\]\\[required\\]]`).val())
            console.log('---limit', $(`[name=questions\\[q_${q_index}\\]\\[limit\\]]`).val())

            let questions_type = $(`[name=questions\\[q_${q_index}\\]\\[type\\]]`).val();
            let questions_require = $(`[name=questions\\[q_${q_index}\\]\\[require\\]]`).val();
            let questions_limit = $(`[name=questions\\[q_${q_index}\\]\\[limit\\]]`).val();

            $(`#content-${q_index}`).append(`
            <div class="question" id="question_${q_index}">
                <input type="hidden" class="questionID" value="${q_index}" name="questions[q_${q_index}][id]">
                <input type="hidden" value="1" name="questions[q_${q_index}][type]">
                <input type="hidden" value="" name="questions[q_${q_index}][required]">
                <input type="hidden" value="" name="questions[q_${q_index}][limit]">
                <div class="row form-group ">
                    <label class="col-md-2 pl-1 col-form-label d-flex align-items-center question-index-${count_questions}">質問${count_questions}:</label>
                    <div class="col-md-8">
                        <textarea placeholder="質問" class="form-control" name="questions[q_${q_index}][title]" required></textarea>
                    </div>
                    <div class="col-md-1 d-flex align-items-center">
                        <button type="button" class="btn btn-danger buttonDelete" onclick="onDeleteQuestion('${q_index}')">
                            <i class="fa fa-times"></i>
                        </button>
                        <button type="button" class="btn btn-primary buttonEdit" style="display: block" onclick="onEdit('question_${q_index}')"><i class="fa fa-pen"></i></button>
                    </div>
                </div>
                <div class="row form-group ">
                    <label class="ml-2 pl-1 col-form-label d-flex align-items-center">質問コード</label>
                    <div class="col-md-8">
                        <input class="question_code" type="text" value="werwer" name="questions[q_${q_index}][question_code]" />
                    </div>
                </div>
                <div class="row form-group ">
                    <label class="ml-2 pl-1 col-form-label d-flex align-items-center">親カテゴリ</label>
                    <div class="col-md-8">
                        <select class="form-control parentCategory" disabled name="questions[q_${q_index}][parent_category]">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="row form-group ">
                    <label class="ml-2 pl-1 col-form-label d-flex align-items-center">関連情報</label>
                    <div class="col-md-8">
                        <select class="form-control questionReferralInfo" disabled name="questions[q_${q_index}][referral_info]">
                            <option value=""></option>
                            ${referral_info.reduce((acc, ref) => {
                return acc + ` <option value="${ref.id}">${ref.name}</option>`
            }, "")
                }
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="ml-2 pl-1 col-form-label d-flex align-items-center">回答整列</label>
                    <div class="col-md-8">
                        <select class="form-control" disabled name="questions[q_${q_index}][answer_align]" id="questionAnswerAlign_${q_index}">
                            <option value="0">左寄せ</option>
                            <option value="1">センター</option>
                            <option value="2">右寄せ</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="ml-2 pl-1 col-form-label d-flex align-items-center">数量の選択</label>
                    <div class="col-md-8">
                        <input type="checkbox" class="switch_1" disabled name="questions[q_${q_index}][select_quantity]" id="questionSelectQuantity_${q_index}">
                    </div>
                </div>
                <div class="row show_img${q_index}">
                    <img src="" class="col fs-question-image mb-2" style="display: none">
                </div>
                <div class="row form-group">
                    <div class="col-md">
                        <input onchange="loadFile(event, '.modal-body .show_img${q_index} img')" accept="image/png, image/gif, image/jpeg" type="file" class="form-control" name="questions[q_${q_index}][file_url]">
                    </div>
                </div>
                <div class="d-flex mb-1">
                    <div id="answers_${q_index}" class="d-flex answerDropArea flex-wrap" ondrop="dropAnswer(event)">
                        <div id="_answer_${a_index}" class="answer card mr-2 mb-2">
                            <div class="card-header d-flex justify-content-between p-2" ondrop="dropAnswerOption(event)">
                                <span>回答 ラジオボタン</span>
                                <button class="text-danger buttonDeleteAnswer" type="button" onclick="onDelete('_answer_${a_index}', this)" style="display: inline-block;"><i class="fa fa-times"></i></button>
                            </div>
                            <div class="card-media" id="_answer_option_${a_index}" ondrop="dropAnswerOption(event)">
                                オプション：
                                <span>無し</span>
                                <input type="hidden" value="0" name="questions[q_${q_index}][answer_option]">
                            </div>
                            <div class="card-body p-2 row">
                            </div>
                            <button class="btn btn-primary" onclick="onNewField(${a_index}, 1, ${q_index})"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            `);
            $(`#label-${q_index}`).append('：' + $(`[name="questions[q_${q_index}][title]"]`).val());
        }

        // $('#questions-container').append($('.dropArea .question'));
        $('#questions-container').find('button').not('.while-btn-add-survey').css("display", "none");
        $('#questions-container').find('.buttonEdit').css("display", "block");
        $('#questions-container').find('.buttonDelete').css("display", "block");
        $('#questions-container').find('input').prop('readonly', true);
        $('#questions-container').find('input[type="file"]').prop('disabled', true);
        $('#questions-container').find('input[type="checkbox"]:not(.ac_toggle)').prop('disabled', true);
        $('#questions-container').find('select').prop('disabled', true);
        $('#questions-container textarea').prop('readonly', true)
        currentQuestionId = null;
        // orderCount = null;
        // lastQuestionId = null;
    }

    $('#btnAddQuestion').click(function () {
        closeModalHandler();
        $("#modalAddQuestion .close").click();

    });

    $('input[name="background_color"]').change(function () {
        $('#preview').css('background', $(this).val());
    })

    // $('input[name="char_color"]').change(function () {
    //     $('#preview-text').css('background', $(this).val());
    // })

    $('input[name="border_color"]').change(function () {
        $('#preview-img').css('border', `4px solid ` + $(this).val());
    })

    $('#questions-container').on('click', '[data-toggle="modal"]', function (e) {
        let $target = $(e.target);
        let value = $target.data('answerid');
        $('#container-id').val(value);
        $('#sub-container-id').val(value);
    });

    $('#btnAddReferralInfo').on('click', function () {
        $('#modalReferralInfo').modal('toggle');
    });

    $(".modal").on("hidden.bs.modal", function () {
        if (isSaved) {
            closeModalHandler();
            isSaved = false;
        }

        $("#addQuestionDropArea").html("");
    });

    $("form").on("submit", function () {
        $('#questions-container').find('select').prop('disabled', false);
        $('#questions-container').find('input[type="file"]').prop('disabled', false);
        $('#questions-container').find('input[type="checkbox"]').prop('disabled', false);
    });

    $(".questionRequired").on("click", (e) => {
        $('.questionRequired').removeClass('selected');
        $(e.target).addClass('selected');
        if ($(e.target).prop('id') == 'questionRequired') {
            $(`[name=questions\\[q_${currentQuestionId}\\]\\[required\\]]`).val('true');
        } else {
            $(`[name=questions\\[q_${currentQuestionId}\\]\\[required\\]]`).val('false');
        }
    });

    $("#answerLimit").on("input change", (e) => {
        $(`[name=questions\\[q_${currentQuestionId}\\]\\[limit\\]]`).val($(e.target).val());
    });

})(jQuery);

// 質問追加のモーダル内の要素のみ対象にしている
function onDelete(id) {
    let result = window.confirm('本当に削除しますか？')
    if (result) {
        if ($('#modalAddQuestion').hasClass('show')) {
            $(`.modal-content #${id}`).remove();
        }
    }
    return result;
}

function onDeleteQuestion(id) {
    let result = window.confirm('本当に削除しますか？')
    if (result) {
        if ($('#modalAddQuestion').hasClass('show')) {
            $(`.modal-content #question_${id}`).remove();
        } else {
            $(`#question_${id}_wrapper`).parent().remove();
        }
    }
}

function onDeleteAnswerOption(id) {
    let result = onDelete(id + " button");
    if (result) {
        $(`#${id} span`).text("無し");
        $(`#${id} input`).val('0');
    }
}

function onSubDelete(id) {
    result = window.confirm('本当に削除しますか？')
    if (result) {
        $('#_sub_que_' + id).remove();
    }

    $('button[data-answerid=subQues-div' + id + ']').removeAttr('disabled');
}

$(function () {
    $("input[type=file]").on('change', function () {
        var file = $(this).prop('files')[0];
        if (file.size > 10485760) {
            alert('10MB以下のファイルを選択してください。');
            return;
        }
    });
});

function onDeleteMovie(name, id) {
    $('[name="' + name + '"]').val('');
    $('[data-index="' + name + '"]').val('-');
    $('label[for="' + name + id + '"]').text('動画を選択してください。');

}

function editReferralInfo(info) {
    $('#txtReferralEditID').val(info.id);
    $('#txtReferralEditName').val(info.name);
    $('#txtReferralEditInfo').html(info.info).text();
    $('#modalEditReferralInfo').modal('toggle');
}

function formularSetting() {
    $("#survey").append(`
        <input type="hidden" name="surveyRedirect" value="admin.formularSetting">
    `);
    var title = document.getElementsByName('title');
    if (title[0].value !== '') {
        $("#survey").submit();
    } else {
        alert('フォームを入力してください');
    }
}

// function readOrderCount(self) {
//     orderCount = $('.btn-add-survey').index(self);
// }

function addNewQuestion() {
    console.log(q_index)
    q_index++;
    console.log(q_index);
    count_questions++;

    currentQuestionId = q_index;

    if ($('#modalAddQuestion').modal) {
        $('#modalAddQuestion').modal('toggle');
    }

    $('#edit-buttons-save-off').css('display', 'block');
    $('#edit-buttons-save-on').css('display', 'none');
    $('#edit-buttons-spinner').css('display', 'none');
    $('#edit-buttons-time').html('');

    $('.dropArea').find('button').css("display", "block");
    $('.dropArea').find('.buttonDeleteAnswer').css("display", "inline-block");
    $('.dropArea').find('.buttonEdit').css("display", "none");
    $('.dropArea').find('input').prop("readonly", false);
    $('.dropArea').find('input[type="file"]').prop("disabled", false);
    $('.dropArea').find('input[type="checkbox"]').prop("disabled", false);
    $('.dropArea').find('textarea').prop("readonly", false);
    $('.dropArea').find('select').prop("disabled", false);
    $('.dropArea').find('select').prop("readonly", false);

    $('.questionRequired').removeClass("selected");
    if ($(`[name=questions\\[q_${q_index}\\]\\[required\\]]`).val() == "false") {
        $('#questionNonRequired').addClass("selected");
    } else {
        $('#questionRequired').addClass("selected");
    }
    $('#answerLimit').val($(`[name=questions\\[q_${q_index}\\]\\[limit\\]]`).val());

    $(`#modalAddQuestion .parentCategory`).empty();
    $(`#modalAddQuestion .parentCategory`).append('<option>なし</option>');
    var currentParrent = $(`#questions-container #${q_index} .parentCategory`).val();
    $('#questions-container .question_code').each((i, e) => {
        if ($(e).val() != $(`#modalAddQuestion .question_code`).val()) {
            const val = $(e).val();
            const title = $(e).parent().parent().prev().find('textarea').val();
            $(`#modalAddQuestion .parentCategory`).append(`
                <option ${currentParrent == $(e).val() ? 'selected' : ''} value="${val}">${val}:${title}</option>
            `);
        }
    });

    $('.dropArea .question').html('');
    inputListener();
}

function saveQuestion() {
    const form = document.getElementById("survey");
    let formData = new FormData(form);
    formData.append('json_res', true);
    $('#edit-buttons-spinner').css('display', 'block');

    for (var pair of formData.entries()) {
        var elem = document.querySelector(`#modalAddQuestion [name="${pair[0]}"]`);
        if (elem && elem.name.includes('questions') && elem.type != 'file') {
            formData.set(pair[0], elem.value);
            document.querySelector(`[name="${pair[0]}"]`).setAttribute('value', elem.value)
        }
    }

    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: save_url,
        data: formData,
        type: 'post',
        async: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: (data) => {
            setTimeout(function () {
                var today = new Date();
                var time = today.getHours() + "時" + today.getMinutes() + "分" + today.getSeconds() + '秒に保存しました。';
                $('#edit-buttons-save-off').css('display', 'none');
                $('#edit-buttons-save-on').css('display', 'block');
                $('#edit-buttons-spinner').css('display', 'none');
                $('#edit-buttons-time').html(time);
            }, 1000)
        }
    });
}

function inputListener() {
    var cbox = document.querySelectorAll("input")
    for (let i = 0; i < cbox.length; i++) {
        cbox[i].addEventListener("input", function (e) {
            e.target.setAttribute('value', this.value);
            e.target.value = this.value
        });
    }
}

inputListener();