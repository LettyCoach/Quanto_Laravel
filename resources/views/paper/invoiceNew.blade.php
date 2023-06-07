@extends('layouts.paper')

@section('main-content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<style>
    .container-fluid{
        padding-left: 10rem!important;
    }
</style>
    <!-- Page Heading -->
    <div class="" 
    style="background-color: rgb(105, 55, 255);line-height: 30px; border-radius: 15px; width: 120px; height: 30px; text-align:center; color: white;font-size: 14px; position:fixed; left: 250px;top: 80px;">{{ __('請求書作成') }}</div>
    @if ($errors->any())
        <div class="alert alert-danger border-left-danger" role="alert">
            <ul class="pl-4 my-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


<?php if (isset(Auth::user()->settings)) { 
    $userSettings = json_decode(Auth::user()->settings);
    $invoice = isset($userSettings->invoice) ? $userSettings->invoice : '';
    $member = isset($userSettings->member) ? $userSettings->member : '';
    $purpose = isset($userSettings->purpose) ? $userSettings->purpose : '';
    $payment_method = isset($userSettings->payment_method) ? $userSettings->payment_method : '';
    $stamp_url = isset($userSettings->stamp_url) ? $userSettings->stamp_url : '';
} else { $invoice = ''; $member = ''; $purpose = ''; $payment_method = ''; $stamp_url = ''; } 
    $profile_url = Auth::user()->profile_url != null ? url(Auth::user()->profile_url) : '';
    $dis_data=json_encode($answers);

?>    <script>var dis_data='{!! $dis_data !!}';</script>
<input type="hidden" id="user_id" value="{{Auth::user()->id}}">
    <div class="modal fade show" id="previewModal" style="display: block;">
        <div class="modal-dialog">
            <div class="modal-content" 
            style="width: 520px; top: 200px; height: 300px; left: 20%; border-radius: 20px; min-height: 320px;background: rgb(241,242, 255); box-shadow: 5px 5px 10px 1px grey; padding-bottom: 10px;">
                <div class="modal-header" style="border-bottom: 0; padding: 0;">
                    <button type="button" class="close" data-dismiss="modal" style="opacity:1;">
                        <img src="{{ asset('public/img/ic_modal_close.png') }}" 
                        style="position: absolute; top: -33px; right: -30px; width: 40px; height: 40px; ">
                    </button>
                </div>
                <div class="first-items">
                    <div class="first-line">
                        <div class="first-label">発行日の設定</div>
                        <div class="first-content"><input type="date" id="first_cDate" name="publish" value="{{$cDate}}"></div>
                    </div>
                    <div class="first-line">
                        <div class="first-label">有効日の設定</div>
                        <div class="first-content"><input type="date" id="first_eDate" name="expire" value="{{$eDate}}"></div>
                    </div>
                    <div class="first-line">
                        <div class="first-label">請求先の設定</div>
                        <div class="first-content">
                            <select class="first-select" id="first_select">
                                @foreach($members as $member)
                                <option value="{{$member->name}}">{{$member->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center" style="border-top: 0; padding: 0;">
                    <button id="first_ok" class="btn btn-primary m-auto" 
                    style="background-color: rgb(105, 55, 255); font-size: 16px; height: 35px; width: 80px; border-radius: 10px; box-shadow: 5px 5px 10px 1px grey; opacity:1;">登録</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade show" id="saveModal" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content" style="width: 500px; min-height: 200px;top: 150px; left: 40%;border-radius: 20px;background: rgb(241,242, 255); box-shadow: 5px 5px 10px 1px grey; padding-bottom: 10px;">
                <div class="save-items">
                    <div class="save-line">
                        請求書を 保存しました
                    </div>
                </div>
                <div class="modal-footer justify-content-center" style="border-top: 0;">
                    <button class="btn btn-primary m-auto" 
                            style="background-color: rgb(105, 55, 255); font-size: 20px; width: 100px; height: 40px; border-radius: 15px; box-shadow: 5px 5px 10px 1px grey;" 
                            id="save_close" onclick="javascript:change_toEdit('{{route('paper.invoice.edit',['id'=>0])}}')">閉じる</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade show" id="linkModal" style="display: none;">
        <div class="link-modal-content" style="position:fixed; width: 800px; min-height: 100px;bottom: 50px; left: 30%;">
            <div class="link-items">
                <input type='text' class="link-line-input" id="linput_link" value="https://">
            </div>
            <div class="link-modal-buttons">
                <button class="btn btn-primary m-auto" style=" font-size: 16px; width: 60px;" id="bt_link_close">追加</button>
                <button class="btn btn-secondary m-auto" style=" font-size: 16px; width: 60px;" id="bt_link_close">取消</button>
            </div>
        </div>
    </div>







            <div style="width:100%; display: flex; justify-content: center;">
                <div class=" top-bar">
                    <div class="fix-tool" >
                        <div style="margin: 5px 20px; text-align: center!important;">
                            <div>
                                <input type="checkbox" class="image-check-box" id="image_show" name="image_show" value="imgOK" checked>
                                <label class="image-show" for="image_show">&nbsp;</label>    
                            </div>
                            <div class="image-show" for="image_show">画像表示</div>
                        </div>
                        <div class="set-div">
                            <input type='number' value="28" id="uName_font_size" class="set-font-size">
                            <div style="font-size: 11px;font-weight: bold;padding-top:2px;">会社名</div>
                            <div class="set-font-size-px">px</div>
                        </div>
                        <div class="set-div">
                            <input type='number' value="40" id="uTitle_font_size" class="set-font-size">
                            <div style="font-size: 11px;font-weight: bold;padding-top:2px;">タイトル</div>
                            <div class="set-font-size-px">px</div>
                        </div>
                    </div>
                    <div class="fix-btns">
                        <div class="fix-btn"><div class="btn-grad" id="btn_mail"><img id="btn_mail_img" src="{{ asset('public/img/ic_mail.png') }}" style="height:40px;"></div><p>メール</p></div>
                        <div class="fix-btn"><div class="btn-grad" id="btn_print"><img id="btn_mail_img" src="{{ asset('public/img/ic_print.png') }}" style="height:40px;"></div><p>印刷</p></div>
                        <div class="fix-btn"><div class="btn-grad" id="btn_save"><img id="btn_mail_img" src="{{ asset('public/img/ic_save.png') }}" style="height:40px;"></div><p>保存</p></div>

                        <div class="fix-opt">
                            <div style="position: relative;">
                                <select class="opt-grad" id="select_resize" style="background: url({{ asset('public/img/ic_select_arrow.png') }}) no-repeat center center;background-size: cover;">
                                    <option value="8">&nbsp;&nbsp;8行表示</option>
                                    <option value="9">&nbsp;&nbsp;9行表示</option>
                                    <option value="10" selected>10行表示</option>
                                    <option value="11">11行表示</option>
                                    <option value="12">12行表示</option>
                                    <option value="13">13行表示</option>
                                    <option value="14">14行表示</option>
                                    <option value="15">15行表示</option>
                                </select>
                            </div>
                            <p>表示行数</p>
                        </div>
                        <div class="fix-dropdown">
                            <button onclick="myFunction()" class="fix-dropbtn" style="background: url({{ asset('public/img/ic_select_arrow.png') }}) no-repeat center center;background-size: cover;">バリエーション</button>
                            <div id="varient_resize" class="fix-dropdown-content">
                            @foreach($productOptions as $key=>$productOption)
                                <div class="fix-dropdown-item">        
                                    <div>
                                        <input type="checkbox" class="image-check-box-small" id="varient_check_{{$key}}" name="" value="" {{ ($productOption == "カラー" || $productOption == "サイズ" || $productOption == "素材")?'checked' : '' }}>
                                        <label class="image-show-small" for="color_show">&nbsp;</label>    
                                    </div>
                                    <p>{{$productOption}}</p>
                                </div>
                            @endforeach
                            </div>
                            <p>バリエーシヨン</p>
                        </div>
                        <div class="fix-opt">
                            <div style="position: relative;">
                                <select class="opt-grad" id="style_resize" style="background: url({{ asset('public/img/ic_select_arrow.png') }}) no-repeat center center;background-size: cover;">
                                    <option value="0" selected>スタンダード</option>
                                    <option value="1">ワイド</option>
                                </select>
                            </div>
                            <p>スタイル</p>
                        </div>

                    </div>
                </div>
            </div>
            <div class="blank" id="blank"></div>
    <div class="form-body" id="form_body">

        <div class="print-content" id="invoice">
            <div id="page1" class="page1">
                <div class="flex-between mb1">
                    <div class="pro33"></div>
                    <div class="pro33 flex-center text-center p2 text-center t6 b8"><input id="purpose_1" class="input10" value="ご請求書"></div>
                    <div class="pro33 text-right t1 b2">発行日:<input id="cDate" class="input2 w125 text-right" value="{{$cDate}}"> </div>
                </div>
                <div class="flex mb1">
                <div class="pro60">
                    <div id="uNameDiv" class="t4 uline-grey pb-1"><input id="uName" class="input9 text-right" value="{{Auth::user()->name}}"> 様</div>
                    <div id="uMethodDiv" class="uline-grey pb5 text-left t2 ufit"> 支払方法：<input id="uMethod" class="input1 w200" value="{{ $payment_method}}"></div>
                </div>
                <div class="flex-between p2">
                    <div class="p2">
                        <img id="profile" alt="profile" src="{{  Auth::user()->profile_url }}" style="border-style:solid; border-width:1px; height:50px; width:50px" />
                    </div>
                    <div class="t1-col">
                        <input id="serial" class="input2 w200" value="請求書No,Q{{ Auth::user()->id + 1000 }}---------">
                        <input id="company" class="input2 w200" value="{{ Auth::user()->full_name }}">
                        <div id="invoice_num">{{ $invoice}}</div>
                    </div>
                </div>
            </div>
            <div class="flex pro100">
                <div class="flex-between pro60">
                    <div class="flex-end pb3">
                        <div class="uline-grey pb1">
                            <span class="t1">ご請求金額&nbsp;</span>
                            <input class="input4 w250" id="display_total_price" value="0">
                            <span class="t5 b4" style="vertical-align: 3px;">円&nbsp;</span>
                            <span>(税込)</span><input type="text" class="display-reduce" value="0" id="display_reduce">円
                        </div>
                    </div>

                </div>

                <div class="profile-block t1 pro40">
                    <div class="flex-center p2 text-center w150" style="width: 80px;">
                        <p>住所</p>
                    </div>
                    <div class="profile-sub-block flex p2">
                        <div>
                        <input id="zipCode" class="input2 w200" value="〒{{ Auth::user()->zip_code }}">
                        <input id="adress" class="input2 w200" value="{{ Auth::user()->address }}">
                        <input id="phone" class="input2 w200" value="Tel：{{ Auth::user()->phone_number }}">
                        </div>
                    </div>
                    <div class="flex-center p3">
                        <img
                            alt="stamp" id="stamp"
                            src="{{  $stamp_url  }}" 
                            style="height:70px; width:70px" />
                    </div>
                </div>
            </div>
            <div>
                <div class="t1"> 有効期間 <input id="eDate" class="input2 w200" value="{{$eDate}}"></div>
                <hr style="border-top:2px solid blue">
                <p class="text-center t2">内容明細</p>
            </div>

            <div  id="main_table">
                <div class = "blank_new_row">
                    <img src="{{ asset('public/img/ic_add.png') }}" class="blank_new_row_img" alt="">
                </div>
                <table cellpadding="1" cellspacing="0" class="main-table">
                    <thead>
                        <tr>
                            <th class="th-ID">ID</th>
                            <th class="th1">内容</th>
                            @foreach($productOptions as $key=>$productOption)
                                <th class="th-plus th-plus-{{$key}}" {{ ($productOption == "カラー" || $productOption == "サイズ" || $productOption == "素材")?'' : 'style=display:none;' }}>        
                                    {{$productOption}}
                                </th>
                            @endforeach
                            <th class="th2">単価</th>
                            <th class="th3">数量</th>
                            <th class="th4">金額(円)</th>
                            <th class="th5">消費税</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php $currentPrice=0; $i=0; ?>
                            <?php $totalPrice=0; $totalCount=1;?>
                            <td class="td-ID">
                                <div class="td-a1-d1 tooltipimg">
                                        <img src="{{ asset('public/img/edit_query.png') }}" class="img1"/>
                                        <div class="tooltiptext">
                                            <div><img src="{{ asset('public/img/ic_add.png') }}" id="row_{{ $i }}" class="img2 tooltip-row"/></div><p>画像追加</p>
                                            <div><img src="{{ asset('public/img/ic_link.png') }}" id="link_{{ $i }}" class="img2 tooltip-link"/></div><p>URLリンク</p>
                                            <div><img src="{{ asset('public/img/ic_delete.png')}}" id="del_{{ $i }}" class="img2 tooltip-del"/></div>
                                        </div>
                                        <img src="{{ asset('public/img/ic_modal_close.png') }}" id="tooltip_close_{{ $i }}" class="tooltip-close"/>
                                        <div class="tooltip-edit-div"><img src="{{ asset('public/img/edit_query_m.png') }}" id="tooltip_edit_{{ $i }}" class="tooltip-edit"/><p>編集</p></div>
                                        <input class="td-ID-input" id="ID_{{ $i }}" value="Q0000">
                                        <input type="hidden" id="productNum_{{ $i }}" value="-1">
                                </div>
                            </td>
                            <td class="td-a1"> &nbsp; 
                                    
                                    <div class="flex-center"><img alt="product" id="timg_{{ $i }}"
                                            src="" onerror="this.onerror=null; this.onload=null; if (!this.attributes.src.value) this.attributes.src.value='{{ asset("public/img/blank-plus.png") }}';"
                                            class="td-a1-d2-img" />
                                    </div>             
                                    <textarea class="td-a1-input" id="title_{{ $i }}">タイトル</textarea>
                            </td>

                            @foreach($productOptions as $key=>$productOption)
                                <td class="td-plus td-plus-{{$key}}" {{ ($productOption == "カラー" || $productOption == "サイズ" || $productOption == "素材")?'' : 'style=display:none;' }}>
                                    <textarea class="td-subt-input td-input-{{$key}}" id="subt_{{$key}}_{{ $i }}"></textarea>
                                </td>
                            @endforeach




                            <td class="td-a2"><input class="td-a2-input" id="price_{{ $i }}" value="0"><span>円</span></td>
                            <td class="td-a3"><input class="td-a3-input"   id="quantity_{{ $i }}" value="1"></td>
                            <td class="td-a4"> <input class="td-a4-input"  id="current_price_{{ $i }}" value="0">円</td>
                            <td class="td-a5 reduce-pro-td"> 
                                <select name="pets" class="reduce-pro" id="reduce_pro_0">
                                    <option value="10">10%</option>
                                    <option value="8">8%(軽減税率)</option>
                                    <option value="8">8%</option>
                                    <option value="0">0%</option>
                                </select>
                                <input  class="td-a5-input" id="reduce_plus_0" value="{{ ceil($totalPrice*0.08) }}">円
                            </td>
                        </tr>
                        <tr>
                            <td class="td-r1" colspan="5">合計</td>
                            <td ></td>
                            <td class="td-r3"><input  class="td-r3-input" id="total_count" value="{{ $totalCount }}"> </td>
                            <td class="td-r4"> <input  class="td-r4-input" id="total_price" value="{{ $totalPrice }}">円</td>
                            <td class="td-r5"> 消費税(内税)<input  class="td-r5-input" id="reduce_price" value="0">円</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div style="position: relative;">
            <textarea style="width:680px; height: 100px; border: 1px solid grey; padding: 5px; box-sizing: border-box; margin-left: 10px;margin-top:30px; font-size: 20px;" placeholder="(備考)" text="afaefafe" id="memo_text"></textarea>
            <div class="detail_price">
                <div>
                    <p>10%対象&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="sinput"id="totalAmount10" value="0">円</p>
                    <p>&nbsp;&nbsp;消費税(内税)<input class="sinput"id="totalAmount10s" value="0">円</p>
                </div>
                <div>
                    <p>8%(軽減税率)<input class="sinput"id="totalAmount88" value="0">円</p>
                    <p>&nbsp;&nbsp;消費税(内税)<input class="sinput"id="totalAmount88s" value="0">円</p>
                </div>
                <div>
                    <p>8%対象 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="sinput"id="totalAmount8" value="0">円</p>
                    <p>&nbsp;&nbsp;消費税(内税)<input class="sinput" id="totalAmount8s" value="0">円</p>
                </div>
            </div>
        </div>
        <style id="page_style">
            @page{size: A4; margin: 0;}
        </style>
    </div>

        <div id="q_modal" class="q-modal">
            <div class="img-modal" id="img_modal" >
                <img class="img-close-btn" src="{{asset('public/img/ic_modal_close.png')}}">
                <div class="img-modal-main">
                    <div class="img-modal-title">
                        <div class="img-modal-title-text">画像を選択</div>
                    </div>
                    <div class="img-modal-search-bar">
                        <input type="text" id="search_input" placeholder="Search for names..">
                        <button class="img-modal-probtn img-modal-search-btn" id="img_modal_probtn">&nbsp;</button>
                        <button class="img-modal-xybtn img-modal-search-btn" id="img_modal_xybtn">&nbsp;</button>
                        <button class="img-modal-xbtn img-modal-search-btn" id="img_modal_xbtn">&nbsp;</button>
                    </div>
                    <div class="img-view" id="img_view">
                    </div>
                    <div class="img-upload">
                        <div class="img-upload-img"><img id="img_upload_img" src="{{ asset('public/img/blank.png') }}" style="height: 90px;"></div>
                        <div class="img-upload-url"> 新規追加する &nbsp;&nbsp;<input id="img_upload_url" style="width:150px;" readonly value="blank.png"></div>
                        <div class="img-upload-link-btn"><img src="{{ asset('public/img/ic_check.png') }}" style="height: 50px;"></div>
                    </div>
                </div>
            </div> 
        </div>
        <?php $i=1; ?>
        <input type="hidden" id="hostUrl" value="{{ url('/') }}">
        <input type="hidden" id="rowCount" value="{{ $i }}">
        <input type="hidden" id="ic_add" src="{{ asset('public/img/ic_add.png') }}">
        <input type="hidden" id="ic_del" src="{{ asset('public/img/ic_delete.png')}}">
        <input type="hidden" id="ic_edit" src="{{ asset('public/img/edit_query.png')}}">
        <input type="hidden" id="ic_blank" src="{{ asset('public/img/blank-plus.png')}}">
        <input type="hidden" id="ic_link" src="{{ asset('public/img/ic_link.png')}}">
        <input type="hidden" id="ic_check" src="{{ asset('public/img/ic_check.png')}}">
        <input type="hidden" id="ic_newblank" src="{{ asset('public/img/blank.png')}}">

        @foreach($models as $ii => $model)
        @endforeach
        <!-- Modal -->
        <div class="modal fade" id="modalAddQuestion" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width : 1400px">
                <div class="modal-content" style="width:1400px; min-height : calc(100vh - 80px)">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">商品情報</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#modalAddQuestion').modal('toggle')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row m-0 px-2">
                            <div class="col-6 p-0 flex flex-column align-items-center">
                                <div class="img_pan_main">
                                    <div class="swiper mySwiper" id="mySwiper_main">
                                        <div class="swiper-wrapper" id = "slide_img_pan_main">
                                            <div class="swiper-slide">
                                                <img src="{{ url('public/img/img_03/delete.png') }}" alt="" >
                                            </div>
                                            <div class="swiper-slide">
                                                <img src="{{ url('public/img/img_03/delete.png') }}" alt="" >
                                            </div>
                                        </div>
                                        <div class="swiper-button-next"></div>
                                        <div class="swiper-button-prev"></div>
                                    </div>
                                </div>
                                <div class="img_pan">
                                    <div class="swiper mySwiper" id="mySwiper">
                                        <div class="swiper-wrapper" id = "slide_img_pan">
                                            <div class="swiper-slide">
                                                <img src="{{ url('public/img/img_03/delete.png') }}" alt="" >
                                            </div>
                                            <div class="swiper-slide">
                                                <img src="{{ url('public/img/img_03/delete.png') }}" alt="" >
                                            </div>
                                        </div>
                                        <div class="swiper-button-next"></div>
                                        <div class="swiper-button-prev"></div>
                                    </div>
                                </div>
                                
                                <div class="m-4 flex justify-content-end" style="width : 400px" >
                                    <a href="javascript:viewImageList()" class="font-weight-bold">もっと見る</a>
                                </div>
                            </div>
                            <div class="col-6 p-0 pr-4" id="info_pan">
                                <div class="row m-0 mt-3" id="productID">デザインTシャツブラック</div>
                                <div class="row m-0 mt-3" id="name">デザインTシャツブラック</div>
                                <div class="row m-0 mt-3" id="price">980デ</div>
                                <div class="row m-0 mt-3" >
                                    <div class="col-3 p-0 flex">
                                        <div class="pr-2 change-count" onclick="changeCount(-1)">-</div>
                                        <input type="text" id = "count_product" value = "0">
                                        <div class="pl-2 change-count" onclick="changeCount(1)">+</div>
                                    </div>
                                    <div class="col-3 p-0">
                                        <input type="button" class="btn btn-primary" value="カートに追加">
                                    </div>
                                    <div class="col-1 p-0">
                                        <img src="{{ url('public/img/img_03/tag_off.png') }}" alt="" class="tag" id="tag_1" onclick="setSave(1)" >
                                        <img src="{{ url('public/img/img_03/tag_on.png') }}" alt="" class="tag" id="tag_2" onclick="setSave(0)" style="display: none">
                                    </div>
                                </div>
                                <div class="row m-0 mt-4" id="product_detail">商品説明</div>
                                <div class="row m-0 mt-3" id="detail">デザインTシャツブラック</div>
                                <div class="row m-0 mt-3" id="product_info">商品詳細</div>
                                <div class="row m-0 mt-3 info">
                                    <div class="col-3 p-0">ブランド名</div>
                                    <div class="col-9 p-0">デザインク</div>
                                </div>
                                <div class="row m-0 mt-3 info">
                                    <div class="col-3 p-0">sku</div>
                                    <div class="col-9 p-0">123-4567</div>
                                </div>
                                <div class="mt-3" id="options">
                                    <div class="row m-0 mt-3 info">
                                        <div class="col-3 p-0">デザイ</div>
                                        <div class="col-9 p-0">デザイ-1</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    
        
        <!-- Modal -->
        <div class="modal fade" id="modalImageViewList" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width : 900px; min-width: 900px;">
                <div class="modal-content" style="width:900px; min-height: 360px; background-color: #f1f2ff; border : 0; box-shadow: 5px 5px 10px grey;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">商品画像</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#modalImageViewList').modal('toggle')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class = "user_product_img_pan">
                            <div class="user_product_img_first" id = "userProductImage_div_0">
                                <img src = "" id="userProductImage_0" alt = "img" onclick="viewImage(this.src);">
                            </div>
                        @php
                            for ($ii = 1; $ii < 18; $ii ++) {
                                $style = "display:none";
                                $src = "";
                        @endphp
                                <div id = "userProductImage_div_{{$ii}}" class="sub_image_pan" style = "{{$style}}">
                                    <img src = "{{$src}}" id="userProductImage_{{$ii}}" alt = "img" class="view_image" onclick="viewImage(this.src);">
                                </div>
                        @php
                            }
                        @endphp
                        </div>
    
                    </div>
                    
                    </div>
                </div>
            </div>
        
    
        
        <!-- Modal -->
        <div class="modal fade" id="modalImageView" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width : 640px; min-width: 640px">
                <div class="modal-content" style="width:640px;">
                    <div class="modal-body">
                        <img src="" alt="" class="img_view" id="img_view1">
    
                    </div>
                </div>
            </div>
        </div>
    
        <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
        <script>
            var product_id = 0;
            function viewData(id) {
                
                product_id = id;
                $.get(`/admin/userProduct/show/${id}`, function(data) {
                    const obj = JSON.parse(data);
                    $('#productID').html(obj.productID);
                    // $('.main_img').attr('src', obj.main_img);
                    let rlt = "";
                    for (let ii = 0; ii < 18; ii ++) {
                        $("#userProductImage_div_" + ii).css('display', 'none');
                        $("#userProductImage_" + ii).attr('src', "");
                    }
    
    			obj.img_urls.forEach((e, ii) => {
                    if (e.state !== '') {
                        $("#userProductImage_div_" + ii).css('display', 'none');
                        return;
                    }
                    rlt += `<div class="swiper-slide"><img src="${e.url}" alt="" ></div>`;
                    $("#userProductImage_" + ii).attr('src', e.url);
                    $("#userProductImage_div_" + ii).css('display', 'block');
		
		})
                    $('#slide_img_pan_main').html(rlt);
                    $('#slide_img_pan').html(rlt);
    
                    $('#name').html(obj.name);
                    $('#price').html(obj.price + '円');
                    $('#detail').html(obj.detail);
                    $('#brandName').html(obj.brandName);
                    $('#sku').html(obj.sku);
                    rlt = "";
                    obj.options.forEach((e, ii) => {
                        rlt += `<div class="row m-0 mt-3 info"><div class="col-3 p-0">${e.name}</div><div class="col-9 p-0">${e.descriptions}</div></div>`;
                    })
                    $('#options').html(rlt);
                    if (obj.tag === true) {
                        $("#tag_1").css('display', 'none');
                        $("#tag_2").css('display', 'block');
                    }
                    else {
                        $("#tag_1").css('display', 'block');
                        $("#tag_2").css('display', 'none');
                    }
                    $('#modalAddQuestion').modal('toggle');
                })
    
            }
    
            const viewImageList = () => {
                
                $('#modalImageViewList').modal('toggle');
            }
    
            const viewImage = (src) => {
                
                $("#img_view1").attr("src", src);
                $('#modalImageView').modal('toggle');
            }
    
            setSave = (flag) => {
                $.get(`/admin/userProduct/setTag`, {'product_id': product_id, 'flag': flag}, function(data) {
                    if (flag === 1) {
                        $("#tag_1").css('display', 'none');
                        $("#tag_2").css('display', 'block');
                    }
                    else {
                        $("#tag_1").css('display', 'block');
                        $("#tag_2").css('display', 'none');
                    }
                });
            }
    
            const changeCount = (val) => {
                let rlt = $("#count_product").val();
                rlt = parseInt(rlt);
                if (typeof val != "number") {
                    rlt = 0;
                }
                rlt += val;
                if (rlt < 0) rlt = 0;
                $("#count_product").val(rlt);
            }
    
            function deleteData(url) {
    
                if (window.confirm("本当に削除しますか？") == false) return;
                location.href = url;
    
            }
    
            var swiper = new Swiper("#mySwiper_main", {
                slidesPerView: 1,
                loop: true,
                spaceBetween: 30,
                freeMode: true,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
            });
            
            var swiper = new Swiper("#mySwiper", {
                slidesPerView: 4,
                loop: true,
                spaceBetween: 30,
                freeMode: true,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false,
                },
            });
        </script>
    
    
    
@endsection

@section('js')
<script>var paper_id=0; </script>
<script src="{{asset('public/js/invoice.js')}}"></script>

@endsection
