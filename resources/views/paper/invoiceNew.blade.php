@extends('layouts.paper')

@section('main-content')
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
                                <option value="株式会社ニコニコ亭">株式会社ニコニコ亭</option>
                                <option value="株式会社カゴロモフーズ">株式会社カゴロモフーズ</option>
                                <option value="バンザイ株式会社">バンザイ株式会社</option>
                                <option value="日本フード株式会社">日本フード株式会社</option>
                                <option value="株式会社デリーアンドコー">株式会社デリーアンドコー</option>
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
                <input type='text' class="link-line-input" value="https://">
            </div>
            <div class="link-modal-buttons">
                <button class="btn btn-primary m-auto" style=" font-size: 16px; width: 60px;" id="link_close">追加</button>
                <button class="btn btn-secondary m-auto" style=" font-size: 16px; width: 60px;" id="link_close">取消</button>
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
                            <div id="barient_resize" class="fix-dropdown-content">
                                <div class="fix-dropdown-item">
                                    <div>
                                        <input type="checkbox" class="image-check-box-small" id="color_show" name="color_show" value="colorOK" checked>
                                        <label class="image-show-small" for="color_show">&nbsp;</label>    
                                    </div>
                                    <p>カラー</p>
                                </div>
                                <div class="fix-dropdown-item">
                                    <div>
                                        <input type="checkbox" class="image-check-box-small" id="size_show" name="size_show" value="sizeOK" checked>
                                        <label class="image-show-small" for="size_show">&nbsp;</label>    
                                    </div>
                                    <p>サイズ</p>
                                </div>
                                <div class="fix-dropdown-item">
                                    <div>
                                        <input type="checkbox" class="image-check-box-small" id="metiarial_show" name="metiarial_show" value="metiarialOK" checked>
                                        <label class="image-show-small" for="metiarial_show">&nbsp;</label>    
                                    </div>
                                     <p>素材</p>
                                </div>
                            </div>
                            <p>バリエーシヨン</p>
                        </div>
                        <div class="fix-opt">
                            <div style="position: relative;">
                                <select class="opt-grad" id="style_resize" style="background: url({{ asset('public/img/ic_select_arrow.png') }}) no-repeat center center;background-size: cover;">
                                    <option value="A4" selected>スタンダード</option>
                                    <option value="A4 landscape">ワイド</option>
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
                    <div class="t1">
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

                <div class="flex-between t1 pro40">
                    <div class="flex-center p2 text-center w150">
                        <p>住所</p>
                    </div>
                    <div class="flex p2">
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
                <table cellpadding="1" cellspacing="0" class="main-table">
                    <thead>
                        <tr>
                            <th class="th1">内容</th>
                            <th class="th-color">カラー</th>
                            <th class="th-size">サイズ</th>
                            <th class="th-metiarial">コットン</th>
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
                            <td class="td-a1"> &nbsp; 
                                    <div class="td-a1-d1 tooltipimg">
                                        <img src="{{ asset('public/img/edit_query.png') }}" class="img1"/>
                                        <div class="tooltiptext">
                                            <div><img src="{{ asset('public/img/ic_add.png') }}" id="row_{{ $i }}" class="img2 tooltip-row"/></div><p>画像追加</p>
                                            <div><img src="{{ asset('public/img/ic_link.png') }}" id="link_{{ $i }}" class="img2 tooltip-link"/></div><p>URLリンク</p>
                                            <div><img src="{{ asset('public/img/ic_delete.png')}}" id="del_{{ $i }}" class="img2 tooltip-del"/></div>
                                        </div>
                                        <img src="{{ asset('public/img/ic_modal_close.png') }}" id="tooltip_close_{{ $i }}" class="tooltip-close"/>
                                        <div class="tooltip-edit-div"><img src="{{ asset('public/img/edit_query_m.png') }}" id="tooltip_edit_{{ $i }}" class="tooltip-edit"/><p>編集</p></div>
                                    </div>
                                    <div class="flex-center"><img alt="product" id="timg_{{ $i }}"
                                            src="" onerror="this.onerror=null; this.onload=null; if (!this.attributes.src.value) this.attributes.src.value='{{ asset("public/img/blank-plus.png") }}';"
                                            class="td-a1-d2-img" />
                                    </div>
                                    <textarea class="td-a1-input" id="title_{{ $i }}">タイトル</textarea>
                            </td>
                            <td class="td-color"><textarea class="td-color-input" id="subt_color_{{ $i }}">カラー</textarea></td>
                            <td class="td-size"><textarea class="td-size-input" id="subt_size_{{ $i }}">サイズ</textarea></td>
                            <td class="td-metiarial"><textarea class="td-metiarial-input" id="subt_metiarial_{{ $i }}">コットン</textarea></td>                 
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
                            <td class="td-r1">合計</td>
                            <td ></td>
                            <td class="td-r3"><input  class="td-r3-input" id="total_count" value="{{ $totalCount }}"> </td>
                            <td class="td-r4" colspan="2"> <input  class="td-r4-input" id="total_price" value="{{ $totalPrice }}">円</td>
                            <td class="td-r5" colspan="3"> 消費税(内税)<input  class="td-r5-input" id="reduce_price" value="0">円</td>
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
    </div>

        <div id="q_modal" class="q-modal">
            <div class="img-modal" id="img_modal" >
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

@endsection

@section('js')
<script>var paper_id=0; </script>
<script src="{{asset('public/js/invoice.js')}}"></script>


@endsection
