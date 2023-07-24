//global values
var like_flag = false;
var count_option_checked = 3;
var page_direction = true;
var varient_array = [];
var temp_text_element = document.createElement('div');
var update_product_flag = true;
var selected_item_id = 0;
var current_img_index = '';

//Set main css properties
var uMethodDivCss = 300;
var uMethodCss = 200;
var uNameCss = 175;
var uNameDivCss = 220;
var uTitleCss = 280;
var iHtml = '';
var inv_state='';

//get ic Url
var delUrl = $("#ic_del").attr('src');
var addUrl = $("#ic_add").attr('src');
var editUrl = $("#ic_edit").attr('src');
var linkUrl = $("#ic_link").attr('src');
var blankUrl = $("#ic_blank").attr('src');
var newblankUrl = $("#ic_newblank").attr('src');
var checkUrl = $("#ic_check").attr('src');
var stamp = $("#stamp").attr('src');
var profile = $("#profile").attr('src');
var logoSrc = $("#logo").attr('src');
var t_editUrl=$("#tooltip_edit_0").attr('src');
var t_closeUrl=$("#tooltip_close_0").attr('src');

function json_data_init(){
    dis_data = dis_data.replaceAll('&quot;', '\"');
    dis_data = dis_data.replaceAll(':\"{', ':{');
    dis_data = dis_data.replaceAll('}\",', '},');
    dis_data = dis_data.replaceAll('\n', '');
    dis_data = dis_data.replaceAll('　', '');
    dis_data = dis_data.replaceAll('	', '');
    dis_data = dis_data.replaceAll(' ', '');
    dis_data = dis_data.replaceAll('\n', '');
    dis_data = dis_data.replaceAll('\r', '').replaceAll(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/<[^>]*>/g, '');    
}

function global_value_init(){
}

function get_option_array()
{
    $('[id^="varient_check_"]').each(function(){
        let key = $(this).parent().parent().find("p").text();
        let ar_vlaue = $(this).attr('id').replaceAll(/varient_check_/g, '');
        varient_array.push({'name' : key , 'value' : ar_vlaue});
    });
}

function make_production_select_modal(production_data){
    var iHtml = '';
    for (const [key, value] of Object.entries(production_data)) {
        for (const [kkey, answer] of Object.entries(value)) {
            var hostUrl = $("#hostUrl").val();
            var select_img_url = answer.file_url;
            if (answer.file_url == null) select_img_url = blankUrl;
            var item_productName = answer.product;
            var item_brandName = answer.brand;
            var item_productID = answer.productID;
            var item_id = answer.id;
            var item_price = answer.value;
            var item_like = answer.productLike;
            var out_product_name = item_productName
            // if (item_productName.length > 10) { item_productName = item_productName.slice(0, 7); item_productName += "..." }

            var item_sub = answer.value;
            iHtml += '<div class="img-view-item"><div class="img-item-img">';
            iHtml += '<input type="hidden" id="img_upload_url_1" value="' + select_img_url + '">';
            iHtml += '<input type="hidden" id="item_productID" value="' + item_productID + '">';
            iHtml += '<input type="hidden" id="item_price" value="' + item_price + '">';
            iHtml += '<input type="hidden" id="item_like" value="' + item_like + '">';
            iHtml += '<input type="hidden" id="item_h_name" value="' + item_productName + '">';
            iHtml += '<input type="hidden" id="item_id" value="' + item_id + '">';
            for (const [kkkey, option] of Object.entries(answer.options)) {
                iHtml += '<input type="hidden" id="item_'+ kkkey +'" value="' + option + '">';
                out_product_name += option;
            }
            // if (out_product_name.length > 10) { item_productName = item_productName.slice(0, 7); item_productName += "..." }

            iHtml += '<img alt="' + select_img_url + '" src="' + select_img_url + '" ></div>';
            iHtml += '<div class="img-item-down"><div class="img-item-brandName">' + item_brandName + '</div><div class="img-item-productName">' + out_product_name + '</div><div class="img-item-price-sub">'+ item_price +'円</div><input type="hidden" class="img-item-sub" value="' + item_sub + '"></div><div class="img-upload-link-btn-1"><img src="' + checkUrl + '" style="height: 30px;"></div></div>';
        }
    }
    $("#img_view").html('');
    $("#img_view").append(iHtml);
    $("#img_view").append(makeNew_item_view());
}

function makeNew_item_view(){
    var new_item_html = '<div class="img-view-item" id="new_item_blank"><div class="img-item-img"><input type="hidden" id="img_upload_url_1" value="' + blankUrl + '"><img alt="' + blankUrl + '" src="' + newblankUrl + '" ></div>';
    new_item_html += '<div class="img-item-down"><div class="img-item-brandName">' + " " + '</div><div class="img-item-productName">' + " " + '</div><input type="hidden" class="img-item-sub" value="' + "タイトル" + '"></div></div>';
    return new_item_html;
}

function getNumber(_str) {
    if (_str == null) return '0';
    var arr = _str.split('');
    var out = new Array();
    for (var cnt = 0; cnt < arr.length; cnt++) {
        if (isNaN(arr[cnt]) == false) {
            out.push(arr[cnt]);
        }
    }
    return Number(out.join(''));
}
//num
function updateTextView(_obj) {
    var num = getNumber(_obj.val());
    if (num == 0) {
        _obj.val('0');
    } else {
        _obj.val(num.toLocaleString());
    }
}

function css_re() {
    var rows = parseInt($("#select_resize").val());
    if(!page_direction) {rows = 15;}
    var img_height = Math.ceil(38 + (15 - rows) * (60 - 38) / (15 - 8)).toString() + "px";
    var td_height = Math.ceil(45 + (15 - rows) * (70 - 45) / (15 - 8)).toString() + "px";
    $("td").css('height', td_height);
    $(".td-a1-d2-img").css('height', img_height);
    $(".td-a1-d2-img").css('width', img_height);
}

function uDiv() {
    if ($("#uMethod").val() != '') {
        $("#uMethod").css('width', uMethodCss);
        $("#uMethodDiv").css('width', uMethodDivCss);
    }
    if ($("#uName").val() != '') {
        $("#uName").css('min-width', uNameCss);
        $("#uNameDiv").css('min-width', uNameDivCss);
    }
}
//make table row
function make_tr(i, k, cId) {
    //set variables
    var rHtml = "";
    var product_tr_ID = $('#ID_' + (i - k)).val();
    var imgUrl = $('#timg_' + (i - k)).attr('src');
    var title = $('#title_' + (i - k)).text();
    var price = $('#price_' + (i - k)).val();
    var quantity = $('#quantity_' + (i - k)).val();
    var current_price = $('#current_price_' + (i - k)).val();
    var current_reduce = $('#reduce_plus_' + (i - k)).val();
    var reduce_pro_option = $('#reduce_pro_' + (i - k)).val();
    var productNum = $('#productNum_' + (i - k)).val();
    var selk = ($('#reduce_pro_' + (i - k)).prop('selectedIndex') != null) ? $('#reduce_pro_' + (i - k)).prop('selectedIndex') : 0;
    if (k < 0) { }
    else if (cId < 0) { }
    else {
        if (i == cId + 1) {product_tr_ID='Q0000'; imgUrl = blankUrl; title = "タイトル"; price = 0; quantity = 1; current_price = 0; current_reduce = 0; productNum = -1;} 
        else if ($(".blank_new_row").css('display') === 'block') {product_tr_ID='Q0000'; imgUrl = blankUrl; title = "タイトル"; price = 0; quantity = 1; current_price = 0; current_reduce = 0; productNum = -1;} 
    }
    //making html
    rHtml += '<tr>';
    rHtml += '<td class="td-ID">';
    rHtml += '<div class="td-a1-d1 tooltipimg">';
    rHtml += '<img src="' + editUrl + '" class="img1"/>';
    rHtml += '<div class="tooltiptext">';
    rHtml += '<div><img src="' + addUrl + '" class="img2 tooltip-row" id="row_' + i + '"/></div><p>画像追加</p><div><img src="' + linkUrl + '" id="link_' + i + '" class="img2 tooltip-link"/></div><p>URLリンク</p><div><img src="' + delUrl + '" id="del_' + i + '" class="img2 tooltip-del"/></div></div>';
    rHtml += '<div class="tooltip-edit-div"><img src="'+t_editUrl+'" id="tooltip_edit_'+i+'" class="tooltip-edit"/><p>編集</p></div>';
    rHtml += '<img src="'+t_closeUrl+'" id="tooltip_close_'+i+'" class="tooltip-close"/>';
    rHtml += '<input class="td-ID-input" id="ID_'+ i +'" value="'+ product_tr_ID +'"><input type="hidden" id="productNum_'+ i +'" value="'+ productNum +'"></div>';
    rHtml += '</td>';

    rHtml += '<td class="td-a1">';
    rHtml += '<div class="flex-center"><img alt="product" id="timg_' + i + '"src="' + imgUrl + '" class="td-a1-d2-img" /></div><div class="td-a1-input open-modal" id="title_' + i + '">' + title + '</div></td>';

    rHtml += '';
    varient_array.forEach(varient_element=>{
        let ik = i-k;
        let varient_element_value = $("#subt_"+ varient_element.value + '_' + ik).text();
        if (varient_element_value == "") varient_element_value = "　";
        console.log(typeof(varient_element_value));
        rHtml += '<td class="td-plus td-plus-'+ varient_element.value +'">';
        rHtml += '<div class="td-subt-input td-input-'+ varient_element.value +' open-modal" id="subt_'+ varient_element.value +'_'+ i +'">'+ varient_element_value +'</div></td>';
        $('#subt_'+varient_element.value+ '_' + current_img_index).text($(this).parent().find('#item_'+varient_element).val());
    });

    rHtml += '<td class="td-a2"><input class="td-a2-input" id="price_' + i + '" value="' + price + '"><span>円</span></td><td class="td-a3"><input class="td-a3-input"   id="quantity_' + i + '" value="' + quantity + '"></td><td class="td-a4"> <input class="td-a4-input"  id="current_price_' + i + '" value="' + current_price + '">円</td>';
    rHtml += '<td class="td-a5 reduce-pro-td"><select name="pets" class="reduce-pro" id="reduce_pro_' + i + '">';
    if (selk == 0) rHtml += '<option value="10" selected>10%</option><option value="8">8%(軽減税率)</option><option value="8">8%</option><option value="0">0%</option></select>';
    else if (selk == 1) rHtml += '<option value="10">10%</option><option value="8" selected>8%(軽減税率)</option><option value="8" >8%</option><option value="0">0%</option></select>';
    else if (selk == 2) rHtml += '<option value="10">10%</option><option value="8" >8%(軽減税率)</option><option value="8" selected>8%</option><option value="0">0%</option></select>';
    else rHtml += '<option value="10">10%</option><option value="8" >8%(軽減税率)</option><option value="8" >8%</option><option value="0" selected>0%</option></select>';
    rHtml += '<input  class="td-a5-input" id="reduce_plus_' + i + '" value="' + current_reduce + '">円</td></tr>';
    return rHtml;
}

function detail_make() {
    var subHtml = '';
    var memo_text_val = $("#memo_text").val();
    subHtml += '<div style="position: relative;"><textarea style="width:700px; height: 100px; border: 1px solid grey; padding: 5px; box-sizing: border-box; margin-top:20px; font-size: 20px;" placeholder="(備考)" id="memo_text">'+ memo_text_val +'</textarea><div class="detail_price">';
    subHtml += '<div><p>10%対象&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="sinput"id="totalAmount10" value="0">円</p><p>&nbsp;&nbsp;消費税(内税)<input class="sinput"id="totalAmount10s" value="0">円</p></div>';
    subHtml += '<div><p>8%(軽減税率)<input class="sinput"id="totalAmount88" value="0">円</p><p>&nbsp;&nbsp;消費税(内税)<input class="sinput"id="totalAmount88s" value="0">円</p></div>';
    subHtml += '<div><p>8%対象 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="sinput"id="totalAmount8" value="0">円</p><p>&nbsp;&nbsp;消費税(内税)<input class="sinput" id="totalAmount8s" value="0">円</p></div></div></div>';
    return subHtml;
}

function make_before(ci) {
    var pdfHtml = "";
    var cDate = $("#cDate").val();
    var uName = $("#uName").val();
    var company = $("#company").val();
    var invoice = $("#invoice_num").text();
    var display_total = parseInt($("#display_total_price").val().replaceAll(',', ''));
    var zipCode = $("#zipCode").val();
    var adress = $("#adress").val();
    var phone = $("#phone").val();
    var eDate = $("#eDate").val();
    var payMethod = $("#uMethod").val();
    var purpose = $("#purpose_1").val();
    var serial = $("#serial").val();
    var display_reduce = $("#display_reduce").val();
    pdfHtml += '<div id="page1" class="page1"><div class="flex-between mb1"><div class="pro33"></div><div class="pro33 flex-center text-center p2 text-center t6 b8"><input id="purpose_1" class="input10" style="height: 60px;" value="' + purpose + '"></div><div class="pro33 text-right t1 b2">発行日:<input id="cDate" class="input2 w125 text-right" value="' + cDate + '"> </div></div>';
    pdfHtml += '<div class="flex mb1"><div class="pro60"><div id="uNameDiv" class="t4 uline-grey pb1"><input id="uName" class="input9 text-center" value="' + uName + '"> 様</div><div id="uMethodDiv" class="uline-grey pb5 text-left t2 ufit"> 支払方法：<input id="uMethod" class="input1 w200" value="' + payMethod + '"></div></div>';
    pdfHtml += '<div class="flex-between p2"><div class="p2"><img id="profile" alt="profile" src="' + profile + '" style="border-style:solid; border-width:1px; height:50px; width:50px" /></div>';
    pdfHtml += '<div class="t1-col"><input id="serial" class="input2 w200" value="' + serial + '"><input id="company" class="input2 w200" value="' + company + '"><div id="invoice_num">' + invoice + '</div></div></div></div><div class="flex pro100"><div class="flex-between pro60"><div class="flex-end pb3"><div class="uline-grey pb1"><span class="t1">ご請求金額&nbsp;</span><input class="input4 w250 " id="display_total_price" value="' + display_total + '"><span class="t5 b4" style="vertical-align: 3px;">円&nbsp;</span><span>(税込)</span><input type="text" class="display-reduce " value="' + display_reduce + '" id="display_reduce">円</div></div></div>';
    pdfHtml += '<div class="profile-block t1 pro40"><div class="flex-center p2 text-center w150"  style="width: 80px;"><p>住所</p></div><div class="profile-sub-block flex p2"><div><input id="zipCode" class="input2 w200" value="' + zipCode + '"><input id="adress" class="input2 w200" value="' + adress + '"><input id="phone" class="input2 w200" value="' + phone + '"></div></div><div class="flex-center p3"><img alt="stamp" id="stamp" src="' + stamp + '" style="height:70px; width:70px" /></div></div></div>';
    pdfHtml += '<div><div class="t1"> 有効期間 <input id="eDate" class="input2 w200" value="' + eDate + '"></div>';
    pdfHtml += make_line_des(ci)
    pdfHtml += '<div  id="main_table">';
    return pdfHtml;
}

function make_page2_top() {
    var cDate = $("#cDate").val();
    var purpose = $("#purpose_1").val();
    var pdfHtml = "";
    pdfHtml += '<div id="page2" class="page2"><div class="flex-between mb1"><div class="pro33"></div><div class="pro33 flex-center text-center p2 text-center t6 b8"><input id="purpose_2" class="input10" style="height: 60px;" value="' + purpose + '" readonly></div><div class="pro33 text-right t1 b2">発行日:<input id="cDate" class="input2 w125 text-right" value="' + cDate + '"> </div></div>';
    return pdfHtml;
}

$(document).on('click', ".img-close-btn", function (e) {
    $(".q-modal").css("display", "none");
});

function make_page_break() {
    var pdfHtml = "";
    pdfHtml += '<div class="pagebreak" style="page-break-after: always; position relative;"><span style="display:none">&nbsp;</span></div>';
    return pdfHtml;
}

function make_line_des(ci) {
    var pdfHtml = "";
    if (ci == "2/2") { pdfHtml += '<hr style="border-top:2px solid blue"><p class="text-center t2">内容明細' + ci + '</p>'; }
    else { pdfHtml += '<hr style="border-top:2px solid blue"><p class="text-center t2">内容明細' + ci + '</p></div>'; }
    return pdfHtml;
}

function pdfRender(type, cId, tId, rows) {
    //get totals
    var total_count = $("#total_count").attr('value');
    var total_price = $("#total_price").attr('value');
    var total_price_sub = total_price;
    var total_count_sub = total_count;
    var reduce_price_sub = 0
    var reduce_price = $("#reduce_price").val();
    var reduce_amount = $("#reduce_amount").val();
    //make pdfHtml
    var pdfHtml = '';
    var k = 0;
    if (type == 'add') {
        k = 1;
    }
    else if (type == 'del') {
        k = -1;
    }
    else {
        k = 0;
    }
    if (tId + k <= rows) {
        pdfHtml += make_before(' ');
        //make table HTML
        //        var rHtml = '<table cellpadding="1" cellspacing="0" class="main-table"><thead>';
        var rHtml = '<div class ="blank_new_row"><img src="'+ $(".blank_new_row_img").attr('src') +'" class="blank_new_row_img" alt=""></div><table cellpadding="1" cellspacing="0" class="main-table"><thead>';
        rHtml+= $(".main-table").find("thead").html();//  '<tr><th class="th1">内容</div></th><th class="th2">単価</div></th><th class="th3">数量</th><th class="th4">金額(円)</th><th class="th5">消費税</th></tr>';
        rHtml+='</thead><tbody>';
        //make main rows
        for (var i = 0; i < tId + k; i++) {
            if (i > cId + k) { rHtml += make_tr(i, k, cId); }
            else { rHtml += make_tr(i, 0, cId); }
        }
        //make last row
        let x_ct_option = count_option_checked+2;
        rHtml += '<tr><td class="td-r1" colspan="'+ x_ct_option +'">合計</td><td ></td><td class="td-r3"><input  class="td-r3-input" id="total_count" value="' + total_count + '"></td><td class="td-r4"><input class="td-r4-input" id="total_price" value="' + total_price + '">円</td><td class="td-r5"> 消費税<input  class="td-r5-input" id="reduce_price" value="' + reduce_price + '">円</td></tr>';
        rHtml += '</tbody></table>';
        pdfHtml += rHtml;
        pdfHtml += '</div>';
        pdfHtml += detail_make();
        pdfHtml += '<style id="page_style">';
        pdfHtml += $("#page_style").text();
        pdfHtml += '</style>';
        pdfHtml += '</div>';
    }
    else {
        pdfHtml += make_before('1/2');

        //make table HTML
        var rHtml1 = '';
        var rHtml2 = '';
        var rHtml = '<div class = "blank_new_row"><img src="'+ $(".blank_new_row_img").attr('src') +'" class="blank_new_row_img" alt=""></div><table cellpadding="1" cellspacing="0" class="main-table"><thead>';
        rHtml+= $(".main-table").find("thead").html();
        
        //'<tr><th class="th1">内容</div></th><th class="th2">単価</div></th><th class="th3">数量</th><th class="th4">金額(円)</th><th class="th5">消費税</th></tr>
        rHtml+='</thead><tbody>';
        rHtml1 = rHtml2 = rHtml;
        //make main rows
        for (var i = 0; i < tId + k; i++) {
            if (i < rows) {
                if (i > cId + k) { rHtml1 += make_tr(i, k, cId); }
                else { rHtml1 += make_tr(i, 0, cId); }
            }
            else {
                if (i > cId + k) { rHtml2 += make_tr(i, k, cId); }
                else { rHtml2 += make_tr(i, 0, cId); }
            }
        }
        //make last row    
        let x_ct_option = count_option_checked+2;        
        rHtml1 += '<tr><td class="td-r1" colspan="'+ x_ct_option +'">小計</td><td ></td><td class="td-r3"><input  class="td-r3-input" id="total_count" value="' + total_count_sub + '"></td><td class="td-r4"><input class="td-r4-input" id="total_price_sub" value="' + total_price_sub + '">円</td><td class="td-r5"> 消費税<input  class="td-r5-input" id="reduce_price_sub" value="' + reduce_price_sub + '">円</td></tr>';
        rHtml1 += '</tbody></table>';
        rHtml2 += '<tr><td class="td-r1" colspan="'+ x_ct_option +'">合計</td><td ></td><td class="td-r3"><input  class="td-r3-input" id="total_count" value="' + total_count + '"></td><td class="td-r4"><input class="td-r4-input" id="total_price" value="' + total_price + '">円</td><td class="td-r5"> 消費税<input  class="td-r5-input" id="reduce_price" value="' + reduce_price + '">円</td></tr></tbody></table>';
        //render
        pdfHtml += rHtml1;
        pdfHtml += '</div></div>';
        pdfHtml += make_page_break();
        pdfHtml += '<div class="break"></div>';
        pdfHtml += make_page2_top();
        pdfHtml += make_line_des('2/2');
        pdfHtml += '<div  id="main_table2">';
        pdfHtml += rHtml2;
        pdfHtml += '</div>';
        pdfHtml += detail_make();
        pdfHtml += '<style id="page_style">';
        pdfHtml += $("#page_style").text();
        pdfHtml += '</style>';
        pdfHtml += '</div>';
    }
    if (type == 'add') {
        $("#rowCount").val(tId + 1);
    }
    else if (type == 'del') {
        $("#rowCount").val(tId - 1);
    }
    else { }
    //replaceAll html
    update_product_flag = false;
    $("#invoice").html(pdfHtml);
    $("#current_price_0").change();
    $("#quantity_0").change();
    $("#reduce_plus_0").change();
    $("#rowCount").change();
    css_re();
    uDiv();
    resize_main_title($('#purpose_1'));
    resize_main_title($("#purpose_2"));
    $('[id*="price"]').each(function () {
        updateTextView($(this));
    });
    $('[id^="reduce_plus_"]').each(function () {
        updateTextView($(this));
    });
    updateTextView($('#reduce_price'));
    updateTextView($('#display_reduce'));
    $("#uName").keyup();
    $("#image_show").prop('checked', true);
    $("#uName_font_size").change();
    $('[id^="title_"]').each(function () {
        $(this).change();
    });
    $(".image-show-small").each(function(){
        var category_text = $(this).parent().find('[id^="varient_check_"]').attr('id').replaceAll(/varient_check_/g, '');
        //$(this).parent().find('[id^="varient_check_"]').prop('checked', !$(this).parent().find('[id^="varient_check_"]').prop('checked'));
        if($(this).parent().find('[id^="varient_check_"]').prop('checked') == true){
            //$(this).css("background-image","url('../../public/img/image_on.png')");
            $(".td-plus-" + category_text).each(function (){
                $(this).css('display', 'table-cell');
            });
            $(".th-plus-" + category_text).each(function (){
                $(this).css('display', 'table-cell');
            });
        } 
        else {
            //$(this).css("background-image","url('../../public/img/image_off.png')");
            $(".td-plus-" + category_text).each(function (){
                $(this).css('display', 'none');
            });
            $(".th-plus-" + category_text).each(function (){
                $(this).css('display', 'none');
            });
        }
    });
    if(parseInt($("#rowCount").val()) < 1){
        $(".blank_new_row").css("display", 'block');
    }
    else{
        $(".blank_new_row").css("display", 'none');
    }
    update_product_flag = true;
}

function save_inrow_data(id_tpnum, cur_row_num){
    if($("#q_modal").css('display') === 'block') return;
    if(!update_product_flag) return;
    if(id_tpnum < 0) return;
    var ar_options = [];

    varient_array.forEach(element => {
        var ar_option = {};
        ar_option['name'] = element.name;
        ar_option['description'] = $("#subt_"+ element.value +'_'+cur_row_num).text().split(",");
        ar_options.push(ar_option);      
    });

    var product_price = $("#price_"+cur_row_num).val();
    product_price = product_price.replaceAll(',', '');

    var product_name = $("#title_"+cur_row_num).text();

    var hostUrl = $("#hostUrl").val();
    var postUrl = hostUrl + '/api/v1/client/update_inrow';
    $.ajax({
        url: postUrl,
        type: 'POST',
        data: jQuery.param({
            'product_id' : id_tpnum,
            'ar_options' : ar_options,
            'product_price' : product_price,
            'product_name' : product_name
        }) ,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        success: function (response) {
            //alert(response.status);
        },
        error: function () {
            //alert("error");
        }
    });
}

function render_page_change(m_dir, m_ct){
    $(".td-r1").attr('colspan', m_ct+2);
    if(m_ct>3) m_dir = false;
    else m_dir = true;
    page_direction = m_dir;
    render_direction_changed(page_direction);
}

function render_direction_changed(x_dir){
    if(!x_dir){
        let title_width = 300-(count_option_checked*30);
        $('#page_style').text('@page { margin: 0; size: A4 landscape; }');
        $(".form-body").css('width', '1400px');
        $("#select_resize").val('15');
        $("#select_resize").change();
        $("#select_resize").val('8');
        $("#select_resize").attr('disabled', true);
    }
    else{
        let title_width = 130-(count_option_checked*20);
        $(".form-body").css('width', '1000px');
        $('#page_style').text('@page { margin: 0; size: A4; }');
        $("#select_resize").attr('disabled', false);
    }
}

function change_toEdit(toEdit_url){
    if(inv_state=="edit") return;
    to_router=toEdit_url.substr(0, toEdit_url.length-1);
    to_router+=paper_id;
    window.location.href = to_router;
}

function resize_main_title(_obj) {
    $("#uTitle_font_size").change();
}

function myFunction() {
    if($("#varient_resize").css('display')=='none') $("#varient_resize").css('display', "block");
    else $("#varient_resize").css('display', "none");
}

function mail_send_one(){
    if(mails_text = $("#mails_text").val() == null || $("#mails_text").val() == '' || $("#mails_text").val() == '#') return;
    if(mails_text = $("#mail_textarea").val() == null || $("#mail_textarea").val() == '') return;
    var hostUrl = $("#hostUrl").val();
    var postUrl = $("#mail_send_form").attr('action');
    var mails_text = $("#mails_text").val();
    var mail_textarea = $("#mail_textarea").val();
    $.ajax({
        type: 'POST',
        url: postUrl,
        data: {'mails_text': mails_text, 'mail_textarea' : mail_textarea},
        success: function (data, status) {
            if(status == 'sucess') alert('メールを送信しました。');
        }
    }); // close ajax
    $("#mailModal").css('display', 'none');
}

function update_total_price() {
    updateTextView($("#total_price_sub"));
    updateTextView($("#total_price"));
    updateTextView($("#reduce_plus"));
}

  // Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.fix-dropbtn')) {
        var dropdowns = document.getElementsByClassName("fix-dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
    if (event.target.tagName != 'TEXTAREA' && !event.target.classList.contains('open-modal')) {
        $(".input-modal").hide();
    }
}


json_data_init();
dis_data = JSON.parse(dis_data);
make_production_select_modal(dis_data);
resize_main_title($('[id^="purpose_"]'));
get_option_array();
$(document).ready(function () {
    $(document).on('keyup', '[id^="title_"]', function () {
        var txtrows = $(this).text().split("\n").length;
        var rows = parseInt($("#select_resize").val());
        var thisHeight = 40 + (15 - rows) * (65 - 40) / (15 - 8);
        var thisFont = 12 + (15 - rows) * (20 - 12) / (15 - 8);
        $(this).css('font-size', thisFont + "px");
        $(this).css('height', thisHeight + "px");
    });

    //delete row and render table
    $(document).on('click', '.tooltip-del', function () {

        //get currnt count
        var cId = parseInt($(this).attr('id').replaceAll(/del_/g, ''));
        var tId = parseInt($("#rowCount").val());
        var rows = parseInt($("#select_resize").val());
        if(tId<1) return;
        //re-render pdf
        pdfRender('del', cId, tId, rows);
    });

    //add row and render table
    $(document).on('click', '.tooltip-row', function () {
        //get currnt count
        var cId = parseInt($(this).attr('id').replaceAll(/row_/g, ''));
        var tId = parseInt($("#rowCount").val());
        var rows = parseInt($("#select_resize").val());
        //re-render pdf
        pdfRender('add', cId, tId, rows);
    });
    //selector change
    $(document).on('change', '#select_resize', function () {
        // alert('start');
        var rows = parseInt($("#select_resize").val());
        var tId = parseInt($("#rowCount").val());
        var cId = -2;
        pdfRender('re', cId, tId, rows);
    });

    //display_totoal_price auto update
    $(document).on('change', '#total_price', function () {
        $("#display_total_price").val(parseInt($(this).val().replaceAll(',', '')));
        updateTextView($(this));
        $("#reduce_pro").change();
    });
    $(document).on('change', '#display_total_price', function () {
        updateTextView($(this));
    });

    ////////////////////////////////////////////////////////////
    $(document).on('change', '#uName_font_size', function () {
        var set_font_size = parseInt($(this).val());
        var ct_num = $("#uName").val().length;
        dis_width = ct_num * set_font_size + 10;
        uNameCss = dis_width;
        uNameDivCss = dis_width + 50;
        $("#uName").css('font-size', set_font_size + 'px');
        $("#uName").css('width', uNameCss);
        $("#uNameDiv").css('width', uNameDivCss);
    });
    $(document).on('keyup', '#uName', function () {
        var str_uName = $(this).val();
        var hasFullWidth = 0;
        var hasHalfWidth = 0;

        for (var i = 0; i < str_uName.length; i++) {
            var charCode = str_uName.charCodeAt(i);
            if (
              (charCode >= 0x0020 && charCode <= 0x007E) || // Half-width characters
              (charCode >= 0xFF61 && charCode <= 0xFF9F) || // Half-width Katakana
              (charCode >= 0xFFA0 && charCode <= 0xFFDC) || // Full-width Roman characters and half-width voiced sound marks
              (charCode >= 0xFFE8 && charCode <= 0xFFEE)    // Half-width punctuation marks and symbols
            ) {
              hasHalfWidth++;
            } else {
              hasFullWidth++;
            }
        }
        var set_font_size = parseInt($(this).css('font-size').replace("px", ''));
        var ct_num = $(this).val().length;
        
        dis_width = hasFullWidth * set_font_size + hasHalfWidth * set_font_size /2 + 10;
        uNameCss = dis_width;
        uNameDivCss = dis_width + 50;
        $("#uName").css('width', uNameCss);
        $("#uNameDiv").css('width', uNameDivCss);
    });
    $(document).on('change', '#uTitle_font_size', function () {
        var set_font_size = parseInt($(this).val());
        var ct_num = $("#purpose_1").val().length;
        dis_width = ct_num * set_font_size + 100;
        uTitleCss = dis_width;
        $("#purpose_1").css('font-size', set_font_size + 'px');
        $("#purpose_2").css('font-size', set_font_size + 'px');
        $("#purpose_1").css('width', uTitleCss);
        $("#purpose_2").css('width', uTitleCss);
    });
    $(document).on('keyup', '#purpose_1', function () {
        var set_font_size = parseInt($(this).css('font-size').replace("px", ''));
        var ct_num = $(this).val().length;
        dis_width = ct_num * set_font_size + 100;
        uTitleCss = dis_width;
        $("#purpose_1").css('font-size', set_font_size + 'px');
        $("#purpose_2").css('font-size', set_font_size + 'px');
        $("#purpose_1").css('width', uTitleCss);
        $("#purpose_2").css('width', uTitleCss);
    });
    ///////////////////////////////////////////////////////////////////
    $(document).on('keyup', '#uMethod', function () {   
        var dis_width = $(this).val().length * 20 + 20;
        uMethodCss = dis_width;
        uMethodDivCss = dis_width + 120
        $("#uMethod").css('width', uMethodCss);
        $("#uMethodDiv").css('width', uMethodDivCss);
    });
    $(document).on('keyup', '#purpose_1', function () {
        resize_main_title($(this));
        $("#purpose_2").val($(this).val());
        resize_main_title($("#purpose_2"));
    });

    //検証 number
    $(document).on('keyup', '[id*="price"]', function () {
        var $th = $(this);
        $th.val($th.val().replaceAll(/[^0-9,]/g, function (str) { alert('「 "' + str + ' " と入力しました。\n\n数字のみを使用してください。」'); return ''; }));
        updateTextView($(this));
    });
    $(document).on('keyup', '[id*="quantity"]', function () {
        var $th = $(this);
        $th.val($th.val().replaceAll(/[^0-9]/g, function (str) { alert('「 "' + str + ' " と入力しました。\n\n数字のみを使用してください。」'); return ''; }));
    });

    //捕獲 price
    $(document).on('change', '[id^="price_"]', function () {
        //alert('dododo');
        var mPrice = $(this).val();
        var mId = $(this).attr('id').replaceAll(/price_/g, '');
        var current_price = parseInt($(this).val().replaceAll(',', '')) * $('#quantity_' + mId).val();
        $('#current_price_' + mId).val(current_price);
        $('#reduce_plus_' + mId).val(Math.round(current_price * parseInt($('#reduce_pro_' + mId).val()) * 0.01));
        $('#current_price_' + mId).change(); updateTextView($('#current_price_' + mId));
        $('#reduce_plus_' + mId).change(); updateTextView($('#reduce_plus_' + mId));
        update_total_price();
    });

    //reduce_pro_
    $(document).on('change', '[id^="reduce_pro_"]', function () {
        var mId = $(this).attr('id').replaceAll(/reduce_pro_/g, '');
        var pro = parseInt($(this).val());
        $('#reduce_plus_' + mId).val(Math.round(parseInt($('#current_price_' + mId).val().replaceAll(',', '')) * parseInt($('#reduce_pro_' + mId).val()) * 0.01));
        $('#reduce_plus_' + mId).change(); updateTextView($('#reduce_plus_' + mId));


    });

    //捕獲 count
    $(document).on('change', '[id^="quantity_"]', function () {
        var rows = parseInt($("#select_resize").val());
        var tId = parseInt($("#rowCount").val());
        var mId = $(this).attr('id').replaceAll(/quantity_/g, '');
        var current_price = $(this).val() * parseInt($('#price_' + mId).val().replaceAll(',', ''));
        $('#current_price_' + mId).val(current_price);
        $('#reduce_plus_' + mId).val(Math.round(current_price * parseInt($('#reduce_pro_' + mId).val()) * 0.01));
        $('#current_price_' + mId).change(); updateTextView($('#current_price_' + mId));
        $('#reduce_plus_' + mId).change(); updateTextView($('#reduce_plus_' + mId));
        update_total_price();
        var totalCount = 0;
        $("[id^='quantity_']").each(function () {
            totalCount += parseInt($(this).val());
        });
        $("#total_count").val(totalCount);
        if (tId >= rows) {
            var sub_quantity = 0;
            $('#main_table').find('[id^="quantity_"]').each(function () {
                sub_quantity += parseInt($(this).val());
            });
            $("#total_count_sub").val(sub_quantity);
        }
        $("#total_price").change();
        $("#display_total_price").change(); update_total_price();
    });

    //捕獲　amount
    $(document).on('change', '[id^="current_price_"]', function () {
        var totalAmount = 0;
        var rows = parseInt($("#select_resize").val());
        var tId = parseInt($("#rowCount").val());
        $("[id^='current_price_']").each(function () {
            totalAmount += parseInt($(this).val().replaceAll(',', ''));
        });
        $("#total_price").val(totalAmount);
        if (tId >= rows) {
            var sub_total = 0;
            $('#main_table').find('[id^="current_price_"]').each(function () {
                sub_total += parseInt($(this).val().replaceAll(',', ''));
            });
            $("#total_price_sub").val(sub_total);
        }
        $("#total_price").change();
        $("#display_total_price").change();
    });
    //////////////////////////////////////////////////////////////////
    //捕獲　reduce 
    $(document).on('change', '[id^="reduce_plus_"]', function () {
        var totalAmount_sub = 0;
        var totalAmount10 = 0;
        var totalAmount8 = 0;
        var totalAmount88 = 0;
        var totalAmount0 = 0;
        var totalAmount10s = 0;
        var totalAmount8s = 0;
        var totalAmount88s = 0;
        var tId = parseInt($("#rowCount").val());
        var rows = parseInt($("#select_resize").val());
        $("[id^='current_price_']").each(function () {
            var thisval = parseInt($(this).val().replaceAll(',', ''));
            var pindex = $(this).attr('id').replaceAll(/current_price_/g, '');
            var pcat = $('#reduce_pro_' + pindex).prop('selectedIndex');

            if (pcat == 0) { totalAmount10 += thisval; totalAmount10s += Math.round(thisval * 0.1); totalAmount_sub += Math.round(thisval * 0.1);}
            else if (pcat == 1) { totalAmount88 += thisval; totalAmount88s += Math.round(thisval * 0.08); totalAmount_sub += Math.round(thisval * 0.08);}
            else if (pcat == 2) { totalAmount8 += thisval; totalAmount8s += Math.round(thisval * 0.08); totalAmount_sub += Math.round(thisval * 0.08);}
            else { totalAmount0 += 0;}
   
        });

        $("#reduce_price").val(totalAmount_sub);
        $("#totalAmount10").val(totalAmount10); updateTextView($("#totalAmount10"));
        $("#totalAmount10s").val(totalAmount10s); updateTextView($("#totalAmount10s"));
        $("#totalAmount88").val(totalAmount88); updateTextView($("#totalAmount88"));
        $("#totalAmount88s").val(totalAmount88s); updateTextView($("#totalAmount88s"));
        $("#totalAmount8").val(totalAmount8); updateTextView($("#totalAmount8"));
        $("#totalAmount8s").val(totalAmount8s); updateTextView($("#totalAmount8s"));

        if (tId >= rows) {
            var reduce_price_sub = 0
            $('#main_table').find('[id^="reduce_plus_"]').each(function () {
                reduce_price_sub += parseInt($(this).val().replaceAll(',', ''));
            });
            $("#reduce_price_sub").val(reduce_price_sub);
        }
        $("#reduce_price").change(); updateTextView($("#reduce_price"));

    });

    /////////////////////////////////////////////////////////////////

    //画像ホバート
    $(document).on('click', '.tooltipimg', function (e) {
        if(e.target.id.search("tooltip_close_")>=0) return;
        if(e.target.id.search("tooltip_edit_")>=0) return;
        else{
            $(".tooltipimg").each(function(){
                $(this).find(".tooltiptext").css("visibility", "hidden");
            });
            $(this).find(".tooltip-edit").css("visibility", "visible");
        }
    });
    $(document).on('click', '.tooltip-edit', function (e) {
        if(e.target.id.search("tooltip_close_")>=0) return;
        else{
            $(".tooltipimg").each(function(){
                $(this).find(".tooltiptext").css("visibility", "hidden");
            });
            $(this).parent().parent().find(".tooltiptext").css("visibility", "visible");
            $(this).parent().parent().find(".tooltip-close").css("visibility", "visible");
        }
    });
    $(document).on('click', '.tooltip-close', function (e) {
            $(".tooltiptext").css("visibility", "hidden");
            $(".tooltip-close").css("visibility", "hidden");
    });
    $(document).on('click', 'input, img,textarea, select', function (e) {
        if(e.target.id.search("tooltip_close_")>=0) return;
        if(e.target.id.search("tooltip_edit_")>=0) return;
        $(".tooltipimg").each(function(){
            $(this).find(".tooltiptext").css("visibility", "hidden");
            $(this).find(".tooltip-close").css("visibility", "hidden");
        });
    });



    $(document).on('mouseenter', '.img2', function (e) {
        $(this).css("opacity", "0.7");
    }).on('mouseleave', '.img2', function (e) {
        $(this).animate({ opacity: '1' }, 300);
    });

    $(document).on('change', "#image_show", function (e) {
        if (this.checked) {
            $('[id^="timg_"]').each(function () {
                $(this).css('display', 'block');
            });
        } else {
            $('[id^="timg_"]').each(function () {
                $(this).css('display', 'none');
            });
        }
    });


    $(document).on('click', '#btn_print', function () {
        $("input").each(function () {
            $(this).attr('value', $(this).val());
        });
        $("textarea").each(function () {
            $(this).html($(this).val());
        });
        $("select").each(function () {
            var selk = ($(this).prop('selectedIndex') != null) ? $(this).prop('selectedIndex') : 0;
            $(this).find('option').each(function () {
                if (parseInt($(this).prop('index')) == selk) {
                    $(this).attr('selected', true);
                }
                else {
                    $(this).attr('selected', false);
                }
            });
        });
        $(".tooltipimg").each(function(){
            $(this).css('visibility', 'hidden');
        });
        var printContents = document.getElementById("form_body").innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        
        window.print();
        document.body.innerHTML = originalContents;

        $(".tooltipimg").each(function(){
            $(this).css('visibility', 'visible');
        });
 
    });
    $(document).on('click', '#btn_save', function () {
        $("input").each(function () {
            $(this).attr('value', $(this).val());
        });
        $("textarea").each(function () {
            $(this).html($(this).val());
        });
        $("select").each(function () {
            var selk = ($(this).prop('selectedIndex') != null) ? $(this).prop('selectedIndex') : 0;
            $(this).find('option').each(function () {
                if (parseInt($(this).prop('index')) == selk) {
                    $(this).attr('selected', true);
                }
                else {
                    $(this).attr('selected', false);
                }
            });
        });
        var htmlElement = {};
        var invoiceNameElement = $("#purpose_1").val();
        var invoice_cDateElement = $("#cDate").val();
        var invoice_eDateElement = $("#eDate").val();
        var invoice_uNameElement = $("#uName").val();
        var invoice_total_priceElement = parseInt($("#display_total_price").val().replaceAll(',', ''));
        var invoice_memoElement = $("#memo_text").val();

        $("#form_body").find($("*")).each(function(){
            if($(this).attr("id") != "" && $(this).attr("id") != undefined && ($(this).val() != '' || $(this).attr('src') != '')) {
                let obj_property = $(this).attr("id");
                let obj_value = ($(this).val() == '')?$(this).attr('src'):$(this).val();
                htmlElement[obj_property] = obj_value;
   
            };
            if($(this).attr("id") != "" && $(this).attr("id") && $(this).attr("id").length > 6){
                if($(this).attr('id').toString().substring(0, 6) == 'title_'){
                    let obj_property = $(this).attr("id");
                    let obj_value = $(this).text();
                    //alert(obj_value);
                    htmlElement[obj_property] = obj_value;
                }
                if($(this).attr('id').toString().substring(0, 5) == 'subt_'){
                    let obj_property = $(this).attr("id");
                    let obj_value = $(this).text();
                    htmlElement[obj_property] = obj_value;
                }
            }
            if($(this).attr('id') == 'memo_text'){
                let obj_property = $(this).attr("id");
                let obj_value = $(this).text();
                htmlElement[obj_property] = obj_value;
            }

        });
        $('[id ^="reduce_pro_"]').each(function(){
            let obj_property = $(this).attr("id");
            let obj_value = $(this).val();
            htmlElement[obj_property] = obj_value;
        });

        $(".top-bar").find($("input")).each(function(){
            let obj_property = $(this).attr("id");
            let obj_value = $(this).val();
            if($(this).is(':checked')) obj_value = 'checked';
            htmlElement[obj_property] = obj_value;
        });
        
        let obj_property = "count_option_checked";
        let obj_value = count_option_checked;
        htmlElement[obj_property] = obj_value;


        htmlElement = JSON.stringify(htmlElement);
        //upload invoice html
        var hostUrl = $("#hostUrl").val();
        var postUrl = hostUrl + '/paper/invoice/save';
        var fd = new FormData();
        fd.append('file', htmlElement);

        fd.append('invoiceName', invoiceNameElement);
        fd.append('invoice_cDate', invoice_cDateElement);
        fd.append('invoice_eDate', invoice_eDateElement);
        fd.append('memo_text', invoice_memoElement);
        fd.append('total_price', invoice_total_priceElement);
        fd.append('send_name', invoice_uNameElement);
        fd.append('paper_id', paper_id);
        $.ajax({
            type: 'POST',
            url: postUrl,
            data: fd,
            contentType: false,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            success: function (data, status) {
                paper_id=data.edit_id;
                inv_state=data.inv_state;
                $('#saveModal').css('display', 'block');
            }
        }); // close ajax
    });

    $(document).on('focus', 'input', function () {
        $(this).select();
    });

    //modal display
    $(document).on('click', "[id^='timg_']", function () {
        $(".q-modal").css("display", "block");
        current_img_index = $(this).attr('id').replaceAll(/timg_/g, '');
        $(".img-upload-link-btn-1").each(function (){
            if($(this).parent().find("#item_id").val() == selected_item_id) return;
            $(this).css("display", "none");
        })
    });

    //upload url
    $(document).on('click', "#new_item_blank", function () {
        var input = document.createElement('input');
        input.type = 'file';
        input.onchange = e => {
            var file = e.target.files[0];
            var fd = new FormData();
            fd.append('file', file);
            fd.append('user_id', $("#user_id").val());
            //upload img & url
            var hostUrl = $("#hostUrl").val();
            var postUrl = hostUrl + '/api/v1/client/uploadImg';

            $.ajax({
                type: 'POST',
                url: postUrl,
                data: fd,
                contentType: false,
                enctype: 'multipart/form-data',
                cache: false,
                processData: false,
                success: function (data, status) {
                    const filePath = hostUrl + "/public/user_product/" + data.filename;
                    var itemHtml = '';
                    var select_img_url = filePath;
                    if (filePath == null) select_img_url = blankUrl;
                    var item_productName = "商品名";
                    var item_brandName = "ブランド";
                    var item_productID = "Q000000";
                    var item_price = 0;
                    var item_like = "NOLIKE";
                    var item_id = data.new_product_id;
                    if (item_productName.length > 10) { item_productName = item_productName.slice(0, 7); item_productName += "..." }
                                 
                    var item_sub = 0;
                    itemHtml += '<div class="img-view-item"><div class="img-item-img">';
                    itemHtml += '<input type="hidden" id="img_upload_url_1" value="' + select_img_url + '">';
                    itemHtml += '<input type="hidden" id="item_productID" value="' + item_productID + '">';
                    itemHtml += '<input type="hidden" id="item_price" value="' + item_price + '">';
                    itemHtml += '<input type="hidden" id="item_like" value="' + item_like + '">';
                    itemHtml += '<input type="hidden" id="item_id" value="' + item_id + '">';
                    // for (const [kkkey, option] of Object.entries(answer.options)) {
                    //     itemHtml += '<input type="hidden" id="item_'+ kkkey +'" value="' + option + '">';
                    // }
                    itemHtml += '<img alt="' + select_img_url + '" src="' + select_img_url + '" ></div>';
                    itemHtml += '<div class="img-item-down"><div class="img-item-brandName">' + item_brandName + '</div><div class="img-item-productName">' + item_productName + '</div><div class="img-item-price-sub">'+ item_price +'円</div><input type="hidden" class="img-item-sub" value="' + item_sub + '"></div><div class="img-upload-link-btn-1"><img src="' + checkUrl + '" style="height: 30px;"></div></div>';
                                 
                    $(".img-view-item").last().remove();

                    $("#img_view").append(itemHtml);
                    $("#img_view").append(makeNew_item_view());
                    if($(".img-view-item").css('flex-direction') == "row"){
                        $("#img_modal_xbtn").click();
                    }
                }
            }); // close ajax
        }
        input.click();
    });

    $(document).on('click', "#profile", function () {
        var input = document.createElement('input');
        input.type = 'file';
        var profile_path = $(this).attr('src');
        var p_filename = profile_path.split('/').pop();
        const questionMarkIndex = p_filename.indexOf("?");
        const new_String = questionMarkIndex !== -1 ? p_filename.substring(0, questionMarkIndex) : p_filename;
        input.onchange = e => {
            var file = e.target.files[0];
            var fd = new FormData();
            fd.append('file', file);
            fd.append('user_id', $("#user_id").val());
            fd.append('p_filename', new_String);
            //upload img & url
            var hostUrl = $("#hostUrl").val();
            var postUrl = hostUrl + '/api/v1/client/uploadProfile';

            $.ajax({
                type: 'POST',
                url: postUrl,
                data: fd,
                contentType: false,
                enctype: 'multipart/form-data',
                cache: false,
                processData: false,
                success: function (data, status) {
                    var profileUrl = hostUrl;
                    const randomNumber = Math.random();
                    const imageUrlWithCacheBuster = profileUrl + data+`?cache=${randomNumber}`;
                    $("#profile").attr("src", imageUrlWithCacheBuster);
                }
            }); // close ajax
        }
        input.click();
    });

    $(document).on('click', "#stamp", function () {
        var input = document.createElement('input');
        input.type = 'file';
        var stamp_path = $(this).attr('src');
        var s_filename = stamp_path.split('/').pop();
        const questionMarkIndex = s_filename.indexOf("?");
        const new_String = questionMarkIndex !== -1 ? s_filename.substring(0, questionMarkIndex) : s_filename;
        input.onchange = e => {
            var file = e.target.files[0];
            var fd = new FormData();
            fd.append('file', file);
            fd.append('user_id', $("#user_id").val());
            fd.append('s_filename', new_String);
            //upload img & url
            var hostUrl = $("#hostUrl").val();
            var postUrl = hostUrl + '/api/v1/client/uploadStamp';

            $.ajax({
                type: 'POST',
                url: postUrl,
                data: fd,
                contentType: false,
                enctype: 'multipart/form-data',
                cache: false,
                processData: false,
                success: function (data, status) {
                    var stampUrl = hostUrl;
                    const randomNumber = Math.random();
                    const imageUrlWithCacheBuster = stampUrl + data+`?cache=${randomNumber}`;
                    $("#stamp").attr("src", imageUrlWithCacheBuster);
                }
            }); // close ajax
        }
        input.click();
    });



    $(document).on('click', '.img-upload-link-btn', function () {
        var display_img_url = $(this).parent().find('#img_upload_img').attr('src');
        var currnent_img_id = 'timg_' + current_img_index;
        $('#' + currnent_img_id).attr('src', display_img_url);
        $(".q-modal").css("display", "none");
    });
////////////////////////////////////////////////////////////
    $(document).on('click', '.img-upload-link-btn-1', function () {
        var display_img_productID = $(this).parent().find('#item_productID').val();
        var display_img_url = $(this).parent().find('#img_upload_url_1').val();
        var display_img_title = $(this).parent().find('#item_h_name').val();
        var display_img_price = $(this).parent().find('#item_price').val();
        var currnent_img_id = 'timg_' + current_img_index;
        var productNum = $(this).parent().find('#item_id').val();
        $('#ID_'+current_img_index).val(display_img_productID);
        $('#' + currnent_img_id).attr('src', display_img_url);
        //for options
        // for(opt_i = 0; opt_i<varient_option_count; opt_i++){
        //     $(this).parent().find('#item_productID').val();
        // }
        
        varient_array.forEach(varient_element=>{
            //var option_code = $(this).parent().find('#item_'+varient_element).val();
            var xxxt = $(this).parent().find('[id*="'+ varient_element.value +'"]').val();
            var xxxob = $('#subt_'+varient_element.value+'_'+current_img_index);
            var xxxob_id = xxxob.attr('id');
            //$('#subt_'+varient_element+'_'+current_img_index).val($(this).parent().find('[id~="'+ varient_element +'"]').val());
            xxxob.text(xxxt);
        })
        $('#price_' + current_img_index).val(display_img_price); $('#price_' + current_img_index).change();
        $('#title_' + current_img_index).text(display_img_title);
        $('#productNum_' + current_img_index).val(productNum);
        $(".q-modal").css("display", "none");
        selected_item_id = productNum;
    });
    $('[id*="price"]').each(function () {
        updateTextView($(this));
    });
    updateTextView($('#reduce_plus'));
    $("#uName").keyup();
///////////////////////////////////////////////////////////////////////////
    $(document).on('keyup', '#search_input', function () {
        var filter = $(this).val();
        $(".img-view-item").each(function () {
            var title = $(this).find(".img-item-productName").text();
            if (title.indexOf(filter) > -1) {
                $(this).css('display', 'flex');
            }
            else {
                $(this).css('display', 'none');
            }
        });
    });

    $(document).on('click', '#img_modal_probtn', function () {
        like_flag = !like_flag;
        if(like_flag){
            $(this).css("background-image","url('../../public/img/img_03/tag_on.png')");
            var filter = 'LIKE';
            $(".img-view-item").each(function () {
                var title = $(this).find("#item_like").val();
                if (title == filter) {
                    $(this).css('display', 'flex');
                }
                else {
                    $(this).css('display', 'none');
                }
            });
        }
        else{
            $(this).css("background-image","url('../../public/img/img_03/tag_off.png')");
            $(".img-view-item").each(function () {
                $(this).css('display', 'flex');
            });
        }
    });



    $(document).on('click', '#img_modal_xbtn', function(){
        $(".img-view-item").css('flex-direction', 'row');
        $(".img-view-item").css('margin-bottom', '0');
        $(".img-view-item").css('width', '720px');
        $(".img-item-img").find("img").css("width", '70px');
        $(".img-item-img").find("img").css("height", '70px');
        $(".img-item-img").css("margin-bottom", '0');
        $(".img-item-down").css('width', '660px');
        $(".img-item-down").css('height', '70px');
        $(".img-item-down").css('flex-direction', 'row');
        $(".img-item-down").css('padding-left', '20px');

        $(".img-item-brandName").css('width', '200px');
        $(".img-item-brandName").css('font-size', '16px');
        $(".img-item-productName").css('justify-content', 'flex-start');
        $(".img-item-productName").css('width', '530px');
        $(".img-item-productName").css('font-size', '16px');
        $(".img-item-productName").css('line-height', '70px');

        $(".img-modal-xbtn").css("background-image","url('../../public/img/img_03/grid_list.png')");
        $(".img-modal-xybtn").css("background-image","url('../../public/img/img_03/grid_mobile.png')");
    });
    $(document).on('click', '#img_modal_xybtn', function(){
        $(".img-view-item").css('flex-direction', 'column');
        $(".img-view-item").css('margin-bottom', 'auto');
        $(".img-view-item").css('width', '120px');
        $(".img-item-img").find("img").css("width", '120px');
        $(".img-item-img").find("img").css("height", '120px');
        $(".img-item-img").css("margin-bottom", '10px');
        $(".img-item-down").css('width', '120px');
        $(".img-item-down").css('height', 'auto');
        $(".img-item-down").css('flex-direction', 'column');
        $(".img-item-down").css('padding-left', '0');
        
        $(".img-item-brandName").css('width', '120');
        $(".img-item-brandName").css('font-size', '12px');
        $(".img-item-productName").css('justify-content', 'center');
        $(".img-item-productName").css('width', '120');
        $(".img-item-productName").css('font-size', '12px');
        $(".img-item-productName").css('line-height', '16px');

        $(".img-modal-xbtn").css("background-image","url('../../public/img/img_03/grid_soting_1.png')");
        $(".img-modal-xybtn").css("background-image","url('../../public/img/img_03/grid_sorting.png')");
        
    });

    $(document).on('mouseenter', '.img-view-item', function (e) {
        $(this).find(".img-upload-link-btn-1").css("display", 'block');

    }).on('mouseleave', '.img-view-item', function (e) {
        if($(this).find("#item_id").val() == selected_item_id) return;
        $(this).find(".img-upload-link-btn-1").css("display", 'none');
    });

    ///////////////////////////////////////////////////


    $(document).on('change', '#reduce_price', function () {
        $('#display_reduce').val($(this).val());
        updateTextView($('#display_reduce'));

    });

    $(document).on('click', '.close', function () {
        $("#previewModal").css('display', 'none');
    });

    ///////////////////////////////////////////////
    $(document).on('click', '#first_ok', function () {
        var cd = $("#first_cDate").val().replace('-', '年').replace('-', '月') + '日';
        var ed = $("#first_eDate").val().replace('-', '年').replace('-', '月') + '日';
        $("#cDate").val(cd);
        $("#eDate").val(ed);
        $("#uName").val($("#first_select").val());
        $("#previewModal").css('display', 'none');
        $("#uName").keyup();
    });

    $(document).on('click', '#save_close', function(){
        $("#saveModal").css('display', 'none');
        hostUrl = $("#hostUrl").val();
        location.href = hostUrl + "/paper/invoice";
    });

    $(document).on('click','[id^="link_"]', function(){
        current_img_index = $(this).attr('id').replaceAll(/link_/g, '');
        var hostUrl = $("#hostUrl").val();
        var product_id = $(this).parent().parent().parent().find('[id^="productNum_"]').val();
        var linput_url = hostUrl + "/admin/userProduct/showNew/" + product_id;
        if(product_id < 0) linput_url = "https://";
        $("#linput_link").val(linput_url);
        $("#linkModal").css('display', 'block');
        $("#linput_link")
        //var product_id = $(this).parent().parent().parent();
        viewData(product_id);
    });

    $(document).on('click', '#bt_link_close', function(){
        $("#linkModal").css('display', 'none');
        $("#modalAddQuestion").css('display', 'none');
    });

    $(document).on('click', '.image-show-small', function(){
        var category_text = $(this).parent().find('.image-check-box-small').attr('id').replaceAll(/varient_check_/g, '');
        $(this).parent().find('[id^="varient_check_"]').prop('checked', !$(this).parent().find('[id^="varient_check_"]').prop('checked'));
        if($(this).parent().find('[id^="varient_check_"]').prop('checked') == true){
            //$(this).css("background-image","url('../../public/img/image_on.png')");
            $(".td-plus-" + category_text).each(function (){
                $(this).css('display', 'table-cell');
            });
            $(".th-plus-" + category_text).each(function (){
                $(this).css('display', 'table-cell');
            });
            count_option_checked++;
        } 
        else {
            //$(this).css("background-image","url('../../public/img/image_off.png')");
            $(".td-plus-" + category_text).each(function (){
                $(this).css('display', 'none');
            });
            $(".th-plus-" + category_text).each(function (){
                $(this).css('display', 'none');
            });
            count_option_checked--;
        }
        render_page_change(page_direction, count_option_checked);
    });

    $(document).on('change','#style_resize', function(){
        if($(this).val() == '0') page_direction = true;
        else  page_direction = false;
        //render_page_change(page_direction, count_option_checked);
        render_direction_changed(page_direction);
    });

    $(document).on('click', '.blank_new_row_img', function(){
        var cId = 0;
        var tId = 0;
        var rows = parseInt($("#select_resize").val());
        //re-render pdf
        pdfRender('add', cId, tId, rows);
    });
    ///////////////////////////////////////////
    $(document).on('DOMSubtreeModified', '.td-plus', function(){
        console.log($(this).find('[id^="subt_"]').attr('id'));
        var cur_row_num = parseInt($(this).find('[id^="subt_"]').attr('id').split("_").pop());
        var id_tpnum = $("#productNum_"+cur_row_num).val();
        save_inrow_data(id_tpnum, cur_row_num);
    });

    // $(document).on('DOMSubtreeModified', '[id^="title_"]', function(){
    //     var cur_row_num = parseInt($(this).find($('[id^="subt_"]')).attr('id').split("_").pop());
    //     var id_tpnum = $("#productNum_"+cur_row_num).val();
    //     save_inrow_data(id_tpnum, cur_row_num);
    // });

    $(document).on('change', '[id^="price_"]', function(){
        var cur_row_num = parseInt($(this).attr('id').split("_").pop());
        var id_tpnum = $("#productNum_"+cur_row_num).val();
        save_inrow_data(id_tpnum, cur_row_num);
    });

    $(document).on('click', '.open-modal',function(e){
        e.preventDefault();
        //var top = $(this).offset().top + 50;
        var top = $(this).offset().top - 160 - $(document).scrollTop();
        var left = $(this).offset().left;
        $('.input-modal-content').val($(this).text());
        $('.input-modal').css('top', top + 'px').show();
        $('.input-modal').css('left', left + 'px').show();
        temp_text_element = $(this);
        $('.input-modal-content').select();
    });

    $(document).on('click', '.input-modal-close', function(){
        $('.input-modal').hide();
    });

    $(document).on('click', '.input-modal-check', function(){
        temp_text_element.text( $('.input-modal-content').val());
        $('.input-modal').hide();
    });

    ///////////////////////////////////
    $(document).on('click', '#btn_mail', function(){
        $("#mailModal").css('display', 'block');
    });

    $(document).on('click', '.mail-close', function(){
        $("#mailModal").css('display', 'none');
        var tp_tp = $(".mail-first-line").first();
        tp_tp.attr('id',"mail_line_"+0);
        tp_tp.find("input").attr('id', 'mail_address_'+0);
        tp_tp.find("input").attr('name', 'mail_address_'+0);
        tp_tp.find("input").val('');
        tp_tp.find(".m-add-ic").css('rotate', '0deg');
        $(".mail-first-line").each(function(){
            $(this).remove();
        });
        $("#mail_send_form").append(tp_tp);
    });

    $(document).on('click', '.mail-modal-plusIcon', function(){
        $(".mail-first-line").each(function(){
            $(this).find(".m-add-ic").css('rotate', '45deg');
        });
        let last_mail_line = parseInt($(".mail-first-line").last().attr('id').replaceAll('mail_line_',''));
        let current_mail_line = parseInt($(this).parent().attr('id').replaceAll('mail_line_',''));
        if(current_mail_line == last_mail_line){
            var mail_input_tp = $(".mail-first-line").first().clone();
            mail_input_tp.attr('id',"mail_line_"+(last_mail_line + 1));
            mail_input_tp.find("input").attr('id', 'mail_address_'+(last_mail_line + 1));
            mail_input_tp.find("input").attr('name', 'mail_address_'+(last_mail_line + 1));
            mail_input_tp.find("input").val('');
            $("#mail_send_form").append(mail_input_tp);
        }
        else{
            $(this).parent().remove();
        }
        let mail_tp_text='';
        $(".mail-first-line").each(function(){
            mail_tp_text += '#' + $(this).find("input").val();
        });
        $("#mails_text").val(mail_tp_text);
        $(".mail-first-line").last().find(".m-add-ic").css('rotate', '0deg');
    });

    $(document).on('change', ".mail-item-input", function(){
        let mail_tp_text='';
        $(".mail-first-line").each(function(){
            mail_tp_text += '#' + $(this).find("input").val();
        })
        $("#mails_text").val(mail_tp_text);
    });
});

var modal = document.getElementById("q_modal");
var input_modal = document.getElementById("input_textarea");
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }

}








