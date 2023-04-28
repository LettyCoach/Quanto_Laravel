<!DOCTYPE html>
<html lang="en">
	<meta charset="utf-8">
	<title>PDF Editor</title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="description" content="">
    <link rel="stylesheet" href="{{asset('public/css/pdf.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/pdf_table.css')}}">
    <script src="{{asset('public/js/jquery.min.js')}}"></script>
    <script>var dis_data='{{$pdfArray["display"]["answers"]}}';var dis_total='{{$pdfArray["total"]}}';</script>
    <script src="{{asset('public/js/preData.js')}}"></script>
    <script src="{{asset('public/js/easy-number-separator.js')}}"></script>
</head>
<body>
    <?php
        $count=count($pdfArray['img_urls']);
        $totalPrice=0;
        $totalCount=0;

        if (isset($pdfArray['user']['settings'])) { 
            $userSettings = json_decode($pdfArray['user']['settings'], true);
            $invoice = isset($userSettings['invoice']) ? $userSettings['invoice'] : '';
            $stamp_url = isset($userSettings['stamp_url']) ? $userSettings['stamp_url'] : '';
        } else { $invoice = ''; ; $stamp_url = ''; } 
        $profile_url = $pdfArray['user']['profile_url'] != null ? $pdfArray['user']['profile_url'] : '';
        $dis_data=isset($answers)?json_encode($answers): '';
    ?>    
<div class="page-layout">
    <div class="footer" style="padding-top: 10px; color: #000; margin-top: 20px; text-align: center;"><span style="font-size:16px; font-weight: 800;">POWERED BY</span> <span  style="font-size:24px;font-weight: 800;">QUANTO</span></div>
    <div style="width:100%; display: flex; justify-content: flex-end;">
        <div class="fix-btns flex-center" style="z-index: 10">
            <div class="btn-grad" id="btn_print"><img id="btn_pr_img" src="{{ asset('public/img/ic_print.png') }}" style="height:30px;"></div>
            <select class="select-resize" id="select_resize">
                <option value="8">ページに 8行</option>
                <option value="9">ページに 9行</option>
                <option value="10" selected>ページに 10行</option>
                <option value="11">ページに 11行</option>
                <option value="12">ページに 12行</option>
                <option value="13">ページに 13行</option>
                <option value="14">ページに 14行</option>
                <option value="15">ページに 15行</option>
            </select>
        </div>
    </div>
    <div style="width: 100%; height: 50px;  background: grey;" id="blank"></div>
    <div id="pdf">
        <div id="page1" class="page1">
            @if ($count<=10)
            <div class="flex-between mb1">
                <div class="pro33"><img id="logo" src="{{ asset('public/img/pdf_logo.png') }}" style="height:50px;"/></div>
                <div class="pro33 flex-center text-center p2 text-center t6 b8"><input id="purpose_1" class="input10" style="height: 60px;" value="{{$pdfArray['mark']['purpose']}}"></div>
                <div class="pro33 text-right t1 b2 text-center">発行日:<input id="cDate" class="input2 w125 text-right" value="{{$pdfArray['date']}}"> </div>
            </div>
            <div class="flex mb1">
                <div class="pro60">
                    <div id="uNameDiv" class="t4 uline-grey pb-1"><input id="uName" class="input9 text-right" value="{{$pdfArray['name']}}"> 様</div>
                    <div id="uMethodDiv" class="uline-grey pb5 text-left t2 ufit"> 支払方法：<input id="uMethod" class="input1 w200" value="{{ $pdfArray['mark']['payment_method']}}"></div>
                </div>
                <div class="flex-between p2">
                    <div class="p2">
                        <img id="profile" alt="profile" src="{{ $profile_url }}" style="border-style:solid; border-width:1px; height:50px; width:50px" />
                    </div>
                    <div class="t1">
                        <div>請求書No,Q{{ $pdfArray['user']['id'] + 1000 }}-{{ $pdfArray['survey']['id']}}-{{ $pdfArray['branch']['count'] }}</div>
                        <input id="company" class="input2 w200" value="{{ $pdfArray['user']['full_name'] }}">
                        <div id="invoice">{{ $invoice}}</div>
                    </div>
                </div>
            </div>
            <div class="flex pro100">
                <div class="flex-between pro60">
                    <div class="flex-end pb3">
                        <div class="uline-grey pb1">
                            <span class="t1">ご請求金額&nbsp;&nbsp;</span><input class="input4 w250 text-left number-separator" id="display_total_price" value="{{ $pdfArray['total'] }}"><span class="t5 b4" style="vertical-align: 3px;">円&nbsp;</span></div>
                    </div>

                </div>

                <div class="flex-between t1 pro40">
                    <div class="flex-center p2 text-center w150">
                        <p>住所</p>
                    </div>
                    <div class="flex p2">
                        <div>
                        <input id="zipCode" class="input2 w200" value="〒{{ $pdfArray['user']['zip_code'] }}">
                        <input id="adress" class="input2 w200" value="{{ $pdfArray['user']['address'] }}">
                        <input id="phone" class="input2 w200" value="Tel：{{ $pdfArray['user']['phone_number'] }}">
                        </div>
                    </div>
                    <div class="flex-center p3">
                        <img
                            alt="stamp" id="stamp"
                            src="{{ $stamp_url }}" 
                            style="height:70px; width:70px" />
                    </div>
                </div>
            </div>
            <div>
                <div class="t1"> 有効期間 <input id="eDate" class="input2 w200" value="{{ $pdfArray['expire'] }}"></div>
                <hr style="border-top:2px solid blue">
                <p class="text-center t2">内容明細</p>
            </div>
            <div  id="main_table">
                <table cellpadding="1" cellspacing="0" class="main-table">
                    <thead>
                        <tr>
                            <th class="th1">内容</div></th>
                            <th class="th2">単価</div></th>
                            <th class="th3">数量</th>
                            <th class="th4">金額(円)</th>
                        </tr>
                    </thead>
                    <tbody>

                        @for($i=0; $i<$count; $i++)
                        <tr>
                            <?php $currentPrice=(int)$pdfArray['quantities'][$i]*(int)$pdfArray['prices'][$i];?>
                            <td class="td-a1">  
                                    <div class="td-a1-d1 tooltip"><img src="{{ asset('public/img/edit_query.png') }}" class="img1"/>
                                        <div class="tooltiptext">
                                            <div><img src="{{ asset('public/img/ic_delete.png')}}" id="del_{{ $i }}" class="img2 del"/></div>
                                            <div><img src="{{ asset('public/img/ic_add.png') }}" id="row_{{ $i }}" class="img2 row"/></div>
                                        </div>
                                    </div>
                                    <div class="flex-center"><img alt="product" id="timg_{{ $i }}"
                                            src="{{ $pdfArray['img_urls'][$i] }}" onerror="this.onerror=null; this.onload=null; if (!this.attributes.src.value) this.attributes.src.value='{{ asset("public/img/blank.png") }}';"
                                            class="td-a1-d2-img" />
                                    </div>
                                    <input class="td-a1-input" id="title_{{ $i }}" value="{{ $pdfArray['titles'][$i] }}">
                            </td>
                            <td><input class="td-a2-input" id="price_{{ $i }}" value="{{ (int)$pdfArray['prices'][$i] }}"><span>円</span></td>
                            <td class="td-a3"><input class="td-a3-input"   id="quantity_{{ $i }}" value="{{ (int)$pdfArray['quantities'][$i]}}"></td>
                            <td class="td-a4"> <input class="td-a4-input"  id="current_price_{{ $i }}" value="{{ $currentPrice }}">円</td>
                            <?php $totalPrice+=$currentPrice; $totalCount+=(int)$pdfArray['quantities'][$i];?>
                        </tr>
                        @endfor
                        <tr>
                            <td class="td-r1">合計</td>
                            <td ></td>
                            <td class="td-r3"><input  class="td-r3-input" id="total_count" value="{{ $totalCount }}"> </td>
                            <td class="td-r4"> <input  class="td-r4-input" id="total_price" value="{{ $totalPrice }}">円</td>
                        </tr>
                        <tr><td class="td-t1" id="reduce_pro_td"><select name="pets" id="reduce_pro"><option value="pro8">消費税(8%対象)</option><option value="pro10">消費税(10%対象)</option></select></td><td ></td><td class="td-t3"></td><td class="td-t4"> (内税)<input  class="td-t4-input" id="reduce_plus" value="{{ ceil($totalPrice*0.08/10)*10 }}">円</td></tr>  
                    </tbody>
                </table>
            </div>
        </div>
            @else
            <div class="flex-between mb1">
                <div class="pro33"><img id="logo" src="{{ asset('public/img/pdf_logo.png') }}" style="height:50px;"/></div>
                <div class="pro33 flex-center text-center p2 text-center t6 b8"><input id="purpose_1" class="input10" style="height: 60px;" value="{{$pdfArray['mark']['purpose']}}"></div>
                <div class="pro33 text-right t1 b2 text-center">発行日:<input id="cDate" class="input2 w125 text-right" value="{{$pdfArray['date']}}"> </div>
            </div>
            <div class="flex mb1">
                <div class="pro60">
                    <div id="uNameDiv" class="t4 uline-grey pb-1"><input id="uName" class="input9 text-right" value="{{$pdfArray['name']}}"> 様</div>
                    <div id="uMethodDiv" class="uline-grey pb5 text-left t2 ufit"> 支払方法：<input id="uMethod" class="input1 w200" value="{{ $pdfArray['mark']['payment_method']}}"></div>
                </div>
                <div class="flex-between p2">
                    <div class="p2">
                        <img id="profile" alt="profile" src="{{ $profile_url }}" style="border-style:solid; border-width:1px; height:50px; width:50px" />
                    </div>
                    <div class="t1">
                    <div>請求書No,Q{{ $pdfArray['user']['id'] + 1000 }}-{{ $pdfArray['survey']['id']}}-{{ $pdfArray['branch']['count'] }}</div>
                        <input id="company" class="input2 w200" value="{{ $pdfArray['user']['full_name'] }}">
                        <div id="invoice">{{ $invoice}}</div>
                    </div>
                </div>
            </div>
            <div class="flex pro100">
                <div class="flex-between pro60">
                    <div class="flex-end pb3">
                        <div class="uline-grey pb1">
                        <span class="t1">ご請求金額&nbsp;&nbsp;</span><input class="input4 w250 text-left number-separator" id="display_total_price" value="{{ $pdfArray['total'] }}"><span class="t5 b4" style="vertical-align: 3px;">円&nbsp;</span></div>
                    </div>

                </div>

                <div class="flex-between t1 pro40">
                    <div class="flex-center p2 text-center w150">
                        <p>住所</p>
                    </div>
                    <div class="flex p2">
                        <div>
                        <input id="zipCode" class="input2 w200" value="〒{{ $pdfArray['user']['zip_code'] }}">
                        <input id="adress" class="input2 w200" value="{{ $pdfArray['user']['address'] }}">
                        <input id="phone" class="input2 w200" value="Tel：{{ $pdfArray['user']['phone_number'] }}">
                        </div>
                    </div>
                    <div class="flex-center p3">
                        <img
                            alt="stamp" id="stamp"
                            src="{{ $stamp_url }}"
                            style="height:70px; width:70px" />
                    </div>
                </div>
            </div>
            
            <div>
                <div class="t1"> 有効期間 <input id="eDate" class="input2 w200" value="{{ $pdfArray['expire'] }}"></div>
                <hr style="border-top:2px solid blue">
                <p class="text-center t2">内容明細 1/2</p>
            </div>
            <div  id="main_table">
                <table cellpadding="1" cellspacing="0" class="main-table">
                    <thead>
                        <tr>
                            <th class="th1">内容</div></th>
                            <th class="th2">単価</div></th>
                            <th class="th3">数量</th>
                            <th class="th4">金額(円)</th>
                        </tr>
                    </thead>
                    <tbody>

                        @for($i=0; $i < 10; $i++)
                        <tr>
                            <?php $currentPrice=(int)$pdfArray['quantities'][$i]*(int)$pdfArray['prices'][$i];?>
                            <td class="td-a1">  
                                    <div class="td-a1-d1 tooltip"><img src="{{ asset('public/img/edit_query.png') }}" class="img1"/>
                                        <div class="tooltiptext">
                                            <div><img src="{{ asset('public/img/ic_delete.png')}}" id="del_{{ $i }}" class="img2 del"/></div>
                                            <div><img src="{{ asset('public/img/ic_add.png') }}" id="row_{{ $i }}" class="img2 row"/></div>
                                        </div>
                                    </div>
                                    <div class="flex-center"><img alt="product" id="timg_{{ $i }}"
                                            src="{{ $pdfArray['img_urls'][$i] }}" onerror="this.onerror=null; this.onload=null; if (!this.attributes.src.value) this.attributes.src.value='{{ asset("public/img/blank.png") }}';"
                                            class="td-a1-d2-img" />
                                    </div>
                                    <input class="td-a1-input" id="title_{{ $i }}" value="{{ $pdfArray['titles'][$i] }}">
                            </td>
                            <td><input class="td-a2-input" id="price_{{ $i }}" value="{{ (int)$pdfArray['prices'][$i] }}"><span>円</span></td>
                            <td class="td-a3"><input class="td-a3-input"   id="quantity_{{ $i }}" value="{{ (int)$pdfArray['quantities'][$i]}}"></td>
                            <td class="td-a4"> <input class="td-a4-input"  id="current_price_{{ $i }}" value="{{ $currentPrice }}">円</td>
                            <?php $totalPrice+=$currentPrice; $totalCount+=(int)$pdfArray['quantities'][$i];?>
                        </tr>
                        @endfor
                        <tr>
                            <td class="td-r1">小計</td>
                            <td ></td>
                            <td class="td-r3"><input  class="td-r3-input" id="total_count_sub" value="{{ $totalCount }}"> </td>
                            <td class="td-r4"> <input  class="td-r4-input" id="total_price_sub" value="{{ $totalPrice }}">円</td>
                        </tr>               
                    </tbody>
                </table>
            </div>
            <div style="page-break-after:always"><span style="display:none"></span></div>
        </div>
        <div class="break"></div>
        <div id="page2" class="page2">
            <div class="flex-between mb1">
                <div class="pro33"><img id="logo" src="{{ asset('public/img/pdf_logo.png') }}" style="height:50px;"/></div>
                <div class="pro33 flex-center text-center p2 text-center t6 b8"><input id="purpose_2" class="input10" style="height: 60px;" value="{{$pdfArray['mark']['purpose']}}" readonly></div>
                <div class="pro33 text-right t1 b2 text-center">発行日:<input id="cDate" class="input2 w125 text-right" value="{{$pdfArray['date']}}"> </div>
            </div>
            <div>
                <hr style="border-top:2px solid blue">
                <p class="text-center t2">内容明細 2/2</p>
            </div>
            <div  id="main_table2">
                <table cellpadding="1" cellspacing="0" class="main-table">
                    <thead>
                        <tr>
                            <th class="th1">内容</div></th>
                            <th class="th2">単価</div></th>
                            <th class="th3">数量</th>
                            <th class="th4">金額(円)</th>
                        </tr>
                    </thead>
                    <tbody>

                        @for($i=10; $i<$count; $i++)
                        <tr>
                            <?php $currentPrice=(int)$pdfArray['quantities'][$i]*(int)$pdfArray['prices'][$i];?>
                            <td class="td-a1">  
                                    <div class="td-a1-d1 tooltip"><img src="{{ asset('public/img/edit_query.png') }}" class="img1"/>
                                        <div class="tooltiptext">
                                            <div><img src="{{ asset('public/img/ic_delete.png')}}" id="del_{{ $i }}" class="img2 del"/></div>
                                            <div><img src="{{ asset('public/img/ic_add.png') }}" id="row_{{ $i }}" class="img2 row"/></div>
                                        </div>
                                    </div>
                                    <div class="flex-center"><img alt="product" id="timg_{{ $i }}"
                                            src="{{ $pdfArray['img_urls'][$i] }}" onerror="this.onerror=null; this.onload=null; if (!this.attributes.src.value) this.attributes.src.value='{{ asset("public/img/blank.png") }}';"
                                            class="td-a1-d2-img" />
                                    </div>
                                    <input class="td-a1-input" id="title_{{ $i }}" value="{{ $pdfArray['titles'][$i] }}">
                            </td>
                            <td><input class="td-a2-input" id="price_{{ $i }}" value="{{ (int)$pdfArray['prices'][$i] }}"><span>円</span></td>
                            <td class="td-a3"><input class="td-a3-input"   id="quantity_{{ $i }}" value="{{ (int)$pdfArray['quantities'][$i]}}"></td>
                            <td class="td-a4"> <input class="td-a4-input"  id="current_price_{{ $i }}" value="{{ $currentPrice }}">円</td>
                            <?php $totalPrice+=$currentPrice; $totalCount+=(int)$pdfArray['quantities'][$i];?>
                        </tr>
                        @endfor
                        <tr>
                            <td class="td-r1">合計</td>
                            <td ></td>
                            <td class="td-r3"><input  class="td-r3-input" id="total_count" value="{{ $totalCount }}"> </td>
                            <td class="td-r4"> <input  class="td-r4-input" id="total_price" value="{{ $totalPrice }}">円</td>
                        </tr>
                        <tr><td class="td-t1" id="reduce_pro_td"><select name="pets" id="reduce_pro"><option value="pro8">消費税(8%対象)</option><option value="pro10">消費税(10%対象)</option></select></td><td ></td><td class="td-t3"> </td><td class="td-t4"> (内税)<input  class="td-t4-input" id="reduce_plus" value="{{ ceil($totalPrice*0.08/10)*10 }}">円</td></tr>  
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        <textarea style="width:100%; height: 100px; border: 1px solid grey; padding: 5px; box-sizing: border-box; margin-top:20px; font-size: 20px;" placeholder="(備考)"></textarea>
        </div>
        <input type="hidden" id="hostUrl" value="{{ url('/') }}">
        <input type="hidden" id="rowCount" value="{{ $i }}">
        <input type="hidden" id="ic_add" src="{{ asset('public/img/ic_add.png') }}">
        <input type="hidden" id="ic_del" src="{{ asset('public/img/ic_delete.png')}}">
        <input type="hidden" id="ic_edit" src="{{ asset('public/img/edit_query.png')}}">
        <input type="hidden" id="ic_blank" src="{{ asset('public/img/blank.png')}}">
        <input type="hidden" id="ic_link" src="{{ asset('public/img/ic_check.png')}}">
        <input type="hidden" id="main_title" src="{{ $pdfArray['survey']['purpose']}}">
        <div id="q_modal" class="modal">
            <div class="img-modal" id="img_modal" >
                <div class="img-modal-main">
                    <div class="img-modal-title">
                        <div class="img-modal-title-text">画像を選択</div>
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
    </div>
    <script src="{{asset('public/js/pdfEdit.min.js')}}"></script>
</body>

</html>