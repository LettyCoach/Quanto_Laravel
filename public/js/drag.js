function allowDrop(ev) {
    ev.preventDefault();
}

function dragElement(ev, type, id) {
    var dragData = JSON.stringify({type, id});
    switch (type) {
        case DRAG_TYPE.QUESTION:
            ev.dataTransfer.setData("question", dragData);
            break;
        case DRAG_TYPE.ANSWER:
            ev.dataTransfer.setData("answer", dragData);
            break;
        case DRAG_TYPE.OPTION:
            ev.dataTransfer.setData("option", dragData);
            break;
    }
}

function dropQuestion(ev) {
    ev.preventDefault();
    if (ev.dataTransfer.getData("question")) {
        var data = JSON.parse(ev.dataTransfer.getData("question"));
        var id = data.id;
        var type = data.type;
        if (type === DRAG_TYPE.QUESTION) {
            $('#addQuestionDropArea').html(renderQuestion(id));
        }
    }
}

function dropAnswer(ev) {
    if (ev.dataTransfer.getData("answer")) {
        var data = JSON.parse(ev.dataTransfer.getData("answer"));
        var answer = data.id;
        var type = data.type;
        if (type === DRAG_TYPE.ANSWER) {
            $(ev.target).html(renderAnswer(answer));
        }
    }
}

function dropAnswerOption(ev) {
    if (ev.dataTransfer.getData("option")) {
        var data = JSON.parse(ev.dataTransfer.getData("option"));
        var option = data.id;
        var type = data.type;
        if (type === DRAG_TYPE.OPTION) {
            var q_index = $(".modal-content .questionID").val();
            $(`.modal-content #_answer_option_${q_index}`).html(`
                オプション：<span>${option.name}</span>
                <input type="hidden" value="${option.id}" name="questions[q_${q_index}][answer_option]">
                <button type="button" class="text-danger buttonDeleteAnswer" onclick="onDeleteAnswerOption('_answer_option_${q_index}')"><i class="fa fa-times"></i></button>
            `);
        }
    }
}

function onNewField( a_index, id, currentQ) {

    var answerArea = $(`#modalAddQuestion #question_${currentQ} #answers_${currentQ}`);
    var referralOption = '<option value=""></option>';
    referral_info.forEach((info) => {

        referralOption += `<option value="${info.id}">${info.name}</option>`

    })
    var index = answerArea.find('.card-body > div').length+1;
    answerArea.find('.card-body').append(`
        <div class="col-6 p-1" id="_answer_${a_index}_sub_${index}">
            <button type="button" class="text-danger buttonDeleteAnswer" onclick="onDelete('_answer_${a_index}_sub_${index}')"><i class="fa fa-times"></i></button>
            <input type="hidden" value="${id}" name="questions[q_${currentQ}][answers][a_${index}][type]">
            <label>回答${index}</label>
            <input placeholder="回答" class="form-control" name="questions[q_${currentQ}][answers][a_${index}][title]" id="answer_${currentQ}_${index}_title" oninput="handleInputText('answer_${currentQ}_${index}_title',this.value)" required />
            <label>税込価格</label>
            <input class="form-control" name="questions[q_${currentQ}][answers][a_${index}][value]" placeholder="税込価格" id="answer_${currentQ}_${index}_value" oninput="handleInputText('answer_${currentQ}_${index}_value',this.value)" />
            <label>税率</label>
            <select class="form-control" name="questions[q_${currentQuestionId}][answers][a_${a_index}][tax]" id="answer_${currentQuestionId}_${a_index}_tax" oninput="handleInputText('answer_${currentQuestionId}_${a_index}_tax',this.value)">
            <option value=""></option>
            <option value="8">8%</option>
            <option value="10">10%</option>
            </select>
            <label>関連情報</label>
            <select class="form-control referralInfo" name="questions[q_${currentQ}][answers][a_${index}][referral_info]" id="answer_${currentQ}_${index}_referral_info" oninput="handleInputText('answer_${currentQ}_${index}_referral_info',this.value)" >
                ${referralOption}
            </select>
            <label>回答写真</label>
            <div class="show_img_${currentQ}_${index}" style="padding-left: 5px;">
                <img src="" style="display: none;width: 90%">
            </div>
            <input onchange="loadFile(event, '.modal-body .show_img_${currentQ}_${index} img')" accept="image/png, image/gif, image/jpeg" type="file" class="form-control mb-2" name="questions[q_${currentQ}][answers][a_${index}][file_url]">
        </div>
    `);
}

function onEdit(id) {
    isModified = true;
    currentQuestionId = $(`#questions-container #${id}`).find('.questionID').val();

    // $('#edit-buttons-save-off').css('display', 'block');
    // $('#edit-buttons-save-on').css('display', 'none');
    // $('#edit-buttons-spinner').css('display', 'none');
    $('#edit-buttons-time').html('');
    
    $('#modalAddQuestion').modal('toggle');
    $('.dropArea').html($(`#${id}`).parent().html());
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
    if ($(`[name=questions\\[q_${currentQuestionId}\\]\\[required\\]]`).val() == "false") {
        $('#questionNonRequired').addClass("selected");
    } else {
        $('#questionRequired').addClass("selected");
    }
    $('#answerLimit').val($(`[name=questions\\[q_${currentQuestionId}\\]\\[limit\\]]`).val());

    $(`#modalAddQuestion .parentCategory`).empty();
    $(`#modalAddQuestion .parentCategory`).append('<option>なし</option>');
    var currentParrent = $(`#questions-container #${id} .parentCategory`).val();
    $('#questions-container .question_code').each((i, e) => {
        if ($(e).val() != $(`#modalAddQuestion .question_code`).val()) {
            const val = $(e).val();
            const title = $(e).parent().parent().prev().find('textarea').val();
            $(`#modalAddQuestion .parentCategory`).append(`
                <option ${currentParrent == $(e).val() ? 'selected' : ''} value="${val}">${val}:${title}</option>
            `);
        }
    });

    for (var k in questionData) {
        $(`#modalAddQuestion #${k}`).val(questionData[k]);
    }

}

function renderAnswer(answer) {
    var id = answer.id;
    var a_index = $(`#answers_${currentQuestionId} .answerDropArea > div`).length + 1;
    var referralOption = '<option value=""></option>';
    referral_info.forEach((info) => {

        referralOption += `<option value="${info.id}">${info.name}</option>`

    })
    if (id === 4 || id === 5) {
        //checkbox answer
        //radio answer
        return `
        <div class="answer card mr-2 mb-2" id="_answer_${a_index}">
            <div class="card-header d-flex justify-content-between p-2" ondrop="dropAnswerOption(event)">
                <span>回答 ${answer.name}</span>
                <button type="button" class="text-danger buttonDeleteAnswer" onclick="onDelete('_answer_${a_index}')"><i class="fa fa-times"></i></button>
            </div>
            <div class="card_media" id="_answer_option_${q_index}" ondrop="dropAnswerOption(event)">
                オプション：<span>無し</span>
                <input type="hidden" value="0" name="questions[q_${q_index}][answer_option]">
            </div>
            <div class="card-body p-2 row">
                <input type="hidden" value="${id}" name="questions[q_${currentQuestionId}][answers][a_${a_index}][type]">
                <div class="col p-1" id="_answer_${a_index}_sub_${a_index}">
                    <button type="button" class="text-danger buttonDeleteAnswer" onclick="onDelete('_answer_${a_index}_sub_${a_index}')"><i class="fa fa-times"></i></button>
                    <label>回答${a_index}</label>
                    <input placeholder="回答" class="form-control" name="questions[q_${currentQuestionId}][answers][a_${a_index}][title]" id="answer_${currentQuestionId}_${a_index}_title" oninput="handleInputText('answer_${currentQuestionId}_${a_index}_title',this.value)" required />
                    <label>税込価格</label>
                    <input class="form-control" name="questions[q_${currentQuestionId}][answers][a_${a_index}][value]" placeholder="税込価格" id="answer_${currentQuestionId}_${a_index}_value" oninput="handleInputText('answer_${currentQuestionId}_${a_index}_value',this.value)" />
                    <label>税率</label>
                    <select class="form-control" name="questions[q_${currentQuestionId}][answers][a_${a_index}][tax]" id="answer_${currentQuestionId}_${a_index}_tax" oninput="handleInputText('answer_${currentQuestionId}_${a_index}_tax',this.value)">
                    <option value=""></option>
                    <option value="8">8%</option>
                    <option value="10">10%</option>
                    </select>
                    <label>関連情報</label>
                    <select class="form-control referralInfo" name="questions[q_${currentQuestionId}][answers][a_${a_index}][referral_info]" id="answer_${currentQuestionId}_${a_index}_referral_info" oninput="handleInputText('answer_${currentQuestionId}_${a_index}_referral_info',this.value)">
                    ${referralOption}
                    </select>
                    <label>回答写真</label>
                    <div class="show_img_${currentQuestionId}_${a_index}" style="padding-left: 5px;">
                        <img src="" style="display: none;width: 90%">
                    </div>
                    <input onchange="loadFile(event, '.modal-body .show_img_${currentQuestionId}_${a_index} img')" accept="image/png, image/gif, image/jpeg" type="file" class="form-control mb-2" name="questions[q_${currentQuestionId}][answers][a_${a_index}][file_url]" id="answer_${currentQuestionId}_${a_index}_file_url" oninput="handleInputText('answer_${currentQuestionId}_${a_index}_file_url',this.value)">

                </div>


            </div>
            <button class="btn btn-primary" onclick="onNewField(${a_index}, ${id}, ${currentQuestionId})"><i class="fa fa-plus"></i></button>
        </div>
        `;
    }

    //text input answer
    return `
        <div class="answer card mr-2 mb-2" id="_answer_${a_index}">
            <div class="card-header d-flex justify-content-between p-2" ondrop="dropAnswerOption(event)">
                <span>回答 ${answer.name}</span>
                <button type="button" class="text-danger buttonDelete" onclick="onDelete('_answer_${a_index}')"><i class="fa fa-times"></i></button>
            </div>
            <div class="card_media" id="_answer_option_${q_index}" ondrop="dropAnswerOption(event)">
                オプション：<span>無し</span>
                <input type="hidden" value="0" name="questions[q_${q_index}][answer_option]">
            </div>
            <div class="card-body p-2 row">
                <div class="col">
                    <input type="hidden" value="${id}" name="questions[q_${currentQuestionId}][answers][a_${a_index}][type]">
                    <textarea placeholder="回答" class="form-control" name="questions[q_${currentQuestionId}][answers][a_${a_index}][title]" id="answer_${currentQuestionId}_${a_index}_title" oninput="handleInputText('answer_${currentQuestionId}_${a_index}_title',this.value)" required></textarea>
                    <div>
                        <select class="form-control referralInfo" name="questions[q_${currentQuestionId}][answers][a_${a_index}][referral_info]" id="answer_${currentQuestionId}_${a_index}_referral_info" oninput="handleInputText('answer_${currentQuestionId}_${a_index}_referral_info',this.value)">
                        ${referralOption}
                        </select>
                    </div>
                </div>
            </div>
        </div>
        `;

}

function renderQuestion(id) {
    q_index = $('#questions-container').children().length + 1;
    // while ($(`#question_${q_index}`).length > 0) {
    //     q_index++;
    // }
    currentQuestionId = q_index;
    var referralOption = '<option value=""></option>';
    referral_info.forEach((info) => { referralOption += `<option value="${info.id}">${info.name}</option>` });

    var q_req = $('.questionRequired.selected').prop("id") == 'questionRequired' ? 'true' : 'false';
    var a_limit = $('#answerLimit').val() ? $('#answerLimit').val() : 0;

    var category_list = "<option>なし</option>";
    $('#questions-container .question_code').each((i, e) => {
    const val = $(e).val();
    const title = $(e).parent().parent().prev().find('textarea').val();
    category_list += `<option value="${val}">${val}:${title}</option>`;
});

    return `
            <div class="question" id="question_${q_index}">
                <input type="hidden" value="${id}" class="questionType" name="questions[q_${q_index}][type]">
                <input type="hidden" value="${q_index}" class="questionID">
                <input type="hidden" value="${q_req}" name="questions[q_${q_index}][required]">
                <input type="hidden" value="${a_limit}" name="questions[q_${q_index}][limit]">

                <div class="row form-group ">
                    <label class="ml-2 pl-1 col-form-label d-flex align-items-center">質問${q_index}</label>
                    <div class="col-md-8">

                        <input type="text" placeholder="質問" value="" id="question_${q_index}_title" class="form-control" name="questions[q_${q_index}][title]" required oninput="handleInputText('question_${q_index}_title',this.value)">
                    </div>
                    <div class="col-md-1 d-flex align-items-center">
                        <button type="button" class="btn btn-danger buttonDelete" onclick="onDelete('question_${q_index}')"><i class="fa fa-times"></i></button>
                        <button type="button" class="btn btn-primary buttonEdit" style="display: none" onclick="onEdit('question_${q_index}')"><i class="fa fa-pen"></i></button>
                    </div>
                </div>
                <div class="row form-group ">
                    <label  class="ml-2 pl-1 col-form-label d-flex align-items-center">質問コード</label>
                    <div class="col-md-8">
                        <input type="text" class="question_code" value="q_${q_index}" name="questions[q_${q_index}][question_code]" />
                    </div>
                </div>
                <div class="row form-group ">
                    <label class="ml-2 pl-1 col-form-label d-flex align-items-center">親カテゴリ</label>
                    <div class="col-md-8">
                        <select class="form-control parentCategory" name="questions[q_${q_index}][parent_category]">
                            ${category_list}
                        </select>
                    </div>
                </div>
                <div class="row form-group ">
                    <label  class="ml-2 pl-1 col-form-label d-flex align-items-center">関連情報</label>
                    <div class="col-md-8">

                        <select class="form-control" name="questions[q_${q_index}][referral_info]" class="questionReferralInfo" id="questionReferralInfo_${q_index}" onchange="handleInputText('questionReferralInfo_${q_index}', this.value)">
                        ${referralOption}
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="ml-2 pl-1 col-form-label d-flex align-items-center">回答整列</label>
                    <div class="col-md-8">
                        <select class="form-control" name="questions[q_${q_index}][answer_align]" id="questionAnswerAlign_${q_index}" onchange="handleInputText('questionAnswerAlign_${q_index}', this.value)">
                            <option value="0" selected>左寄せ</option>
                            <option value="1">センター</option>
                            <option value="2">右寄せ</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="ml-2 pl-1 col-form-label d-flex align-items-center">数量の選択</label>
                    <div class="col-md-8">
                        <input type="checkbox" class="switch_1" name="questions[q_${q_index}][select_quantity]" id="questionSelectQuantity_${q_index}" onchange="handleInputText('questionSelectQuantity_${q_index}', this.value)">
                    </div>
                </div>
                <div class="row show_img${q_index}" style="padding-left: 5px;">
                    <img src="" style="display: none;width: 90%">
                </div>
                <div class="row form-group">
                    <div class="col-md">
                        <input onchange="loadFile(event, '.modal-body .show_img${q_index} img')" accept="image/png, image/gif, image/jpeg" type="file" class="form-control" value="" id="question_${q_index}_file_url" name="questions[q_${q_index}][file_url]" />
                    </div>
                </div>
                <div class="d-flex mb-2">
                    <div id="answers_${q_index}" class="d-flex answerDropArea flex-wrap" ondrop="dropAnswer(event)">

                    </div>
                </div>
            </div>
        `;
}





function dropFormular(event) {

    if (event.dataTransfer.getData("formular")) {
        var data = JSON.parse(event.dataTransfer.getData("formular"));

        var value = data.value;
        var type = data.type;
        console.log(type);
        console.log(value);
        var textarea = $('#formularSetting textarea');
        textarea.val(textarea.val() + renderFormular(type, value));
    }
}

function dragFormular(ev, type, value) {
    var dragData = JSON.stringify({type, value});
    ev.dataTransfer.setData("formular", dragData);

}

function formClear(){
    $('#formularSetting textarea').val('');
}

function renderFormular(type, value) {
    switch (type) {
        case 'question':
            return `${value}`;
        case 'math':
            if (value === 'plus')
                return ' + ';
            if (value === 'minus')
                return ' - ';
            if (value === 'multiple')
                return ' * ';
            if (value === 'divide')
                return ' / ';
            if (value === 'open')
                return ' ( ';
            if (value === 'close')
                return ' ) ';
        default:
            return '';
    }
}

function submitFormular(){
    var formular = $('#formularSetting textarea').val();
    $('#formularSetting #formularValue').val(encodeURIComponent(formular));
    $('#formularSetting').submit();
}

function handleInputText(key, value) {
    if (value) {
        questionData[key] = value;
    }
}

$(".sortableArea").sortable();
