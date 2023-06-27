<!-- HTMLコード -->
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || []; w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            }); var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : ''; j.async = true; j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl; f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-TX99LKT');</script>
    <!-- End Google Tag Manager -->

    <!-- User segmentation start -->
    <script>
        window.usetifulTags = {
            userId: "EXAMPLE_USER_ID", segment: "EXAMPLE_SEGMENT_NAME", language: "EXAMPLE_LANGUAGE_CODE", role: "EXAMPLE_ROLE", firstName: "EXAMPLE_NAME",
        };</script>
    <!-- User segmentation end -->
    <!-- Usetiful script start -->
    <script>
        (function (w, d, s) {
            var a = d.getElementsByTagName('head')[0];
            var r = d.createElement('script');
            r.async = 1;
            r.src = s;
            r.setAttribute('id', 'usetifulScript');
            r.dataset.token = "8ac11e1e170e52e1e546ea65f4bc7f33";
            a.appendChild(r);
        })(window, document, "https://www.usetiful.com/dist/usetiful.js");</script>
    <!-- Usetiful script end -->

    <title>QUANTO</title>

    <?php
    $survey_token = (isset($_GET['id'])) ? $_GET['id'] : -1;
    $server_host = Config::get('constants.serverHost');
    ?>

    <!-- css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">

    <!-- javascript -->
    <script src="<?php echo $server_host . ""; ?>public/js/lib/jquery.min.js"></script>
    <script src="<?php echo $server_host . ""; ?>public/js/lib/popper.min.js"></script>
    <script src="<?php echo $server_host . ""; ?>public/js/lib/bootstrap.min.js"></script>
    <script src="<?php echo $server_host . ""; ?>public/js/lib/math.js"></script>
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>

</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TX99LKT" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header" style="border: none;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><img
                                src="<?php echo $server_host; ?>front/assets/img/close_cross_icon.png" width=32
                                height=32></span>
                    </button>
                </div>
                <div class="modal-body d-flex align-items-center fd-c">
                    <p><img></p>
                    <p class="product-title"></p>
                    <p class="product-price"></p>
                    <div class="counter py-2" style="width: 113px;">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <button type="button" class="quantity-left-minus btn btn-outline-primary btn-number"
                                    onClick="qLeftMinus(event, 'modal')" data-type="minus" data-field="">
                                    <span class="glyphicon glyphicon-minus" style="pointer-events: none;">-</span>
                                </button>
                            </span>
                            <input type="text" id="quantity" name="quantity" class="form-control input-number"
                                style="text-align: center; pointer-events: none;">
                            <span class="input-group-btn">
                                <button type="button" class="quantity-right-plus btn btn-outline-primary btn-number"
                                    onClick="qRightPlus(event, 'modal')" data-type="plus" data-field="">
                                    <span class="glyphicon glyphicon-plus" style="pointer-events: none;">+</span>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"
                        onClick="handleSelectAnswer(MODAL_O.elm, MODAL_O.qID, MODAL_O.aID)">追加</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo $server_host; ?>api/v1/client/pdf" method="POST" enctype="multipart/form-data"
                    target="_blank">
                    <div class="modal-body d-flex fd-c">
                        <div class="row">
                            <div class="col-6">発行日の設定</div>
                            <div class="col-6"><input class="calendar publish pl-1" type="date" name="publish"></div>
                        </div>
                        <div class="row">
                            <div class="col-6">有効期限の設定</div>
                            <div class="col-6"><input class="calendar expire pl-1" type="date" name="expire"></div>
                        </div>
                        <div class="row">
                            <div class="col-6">請求先の設定</div>
                            <div class="col-6"><input type="text" name="name" style="width: 100%;"></div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $survey_token; ?>">
                        <input type="hidden" name="user_id">
                        <input type="hidden" name="img_urls" value="[]">
                        <input type="hidden" name="titles" value="[]">
                        <input type="hidden" name="prices" value="[]">
                        <input type="hidden" name="quantities" value="[]">
                        <input type="hidden" name="taxes" value="[]">
                        <input type="hidden" name="total" value=0>
                        <input type="hidden" name="question_code" value="[]">
                        <input type="hidden" name="parentCategory" value="[]">
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary m-auto"
                            style="background-color: #6962FF;">プレビュー</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <header id="header" class="header">
        <div class="site-header">
            <div class="site-header-inner">
                <div class="brand-wrapper">
                    <div id="brand" class="brand"><img src="" alt="" /></div>
                    <p id="brand-name" class="brand-name"></p>
                </div>
                <div id="brand-desc" class="brand-desc"></div>
                <div id="title-desc" class="title-desc">
                    <h1 id="title" class="title"><span></span></h1>
                    <p id="description" class="description"></p>
                </div>
                <div id="btn-start" class="btn-start">START</div>
                <div id="qrcode-area" class="qrcode-area">
                    <img id="qrcode-area-img" class="qrcode-area-img" src="" />
                    QRコードをスキャンすると<br>スマホからもオーダー出来ます。
                </div>
                <div class="logo-img-wrapper">
                    <img class="logo-img" src="" />
                </div>
                <div id="progress-row" class="progress-row">
                    <div class="point"></div>
                    <div id="progress-inner" class="progress-inner"></div>
                    <div class="point"></div>
                </div>
            </div>
        </div>
    </header>
    <form action="<?php echo $server_host; ?>api/v1/client/save" method="POST" enctype="multipart/form-data">
        <div id="content" class="content">
            <input type="hidden" name="survey_id" value="<?= $survey_token; ?>" />
            <div id="survey" class="survey">
            </div>

        </div>
        <footer>
            <img class="logo-img" src="" />
            <button type="button" id="prevBtn" class="btn btn-secondary footer-back" disabled
                onclick="nextPrev(-1)">戻る</button>
            <button type="button" id="nextBtn" class="btn btn-primary footer-next" disabled data-nextQuestion=""
                onclick="nextPrev(1)">次へ</button>
            <button type="submit" id="submitBtn" class="btn btn-primary footer-next" style="display: none">送信</button>
            <div class="footer-order">
                <div class="footer-order-list">
                    <div class="col h-100p pc" style="display: block !important;">
                        <div class="row title">
                            <div class="col"></div>
                            <div class="col text-center">ご注文内容</div>
                            <div class="col">
                                <div class="footer-order-hide"></div>
                            </div>
                        </div>
                        <hr class="mb-1">
                        <div class="row of-a m-0" style="height: calc(100% - 155px);">
                            <div class="col item h-100p p-0"></div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col">
                            </div>
                            <div class="col text-center">
                                <button type="button" class="btn btn-primary m-auto" data-toggle="modal"
                                    data-target="#previewModal">プレビュー</button>
                            </div>
                            <div class="col pc">
                                <div class="row m-0">
                                    <div class="col footer-order-count fw-b">
                                        0点
                                    </div>
                                    <div class="col footer-order-amount">
                                        0円
                                    </div>
                                </div>
                            </div>
                            <div class="col sp">
                                <div class="row m-0 footer-order-count fw-b pl-3">
                                    0点
                                </div>
                                <div class="row footer-order-amount m-0 pl-3">
                                    0円
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col h-100p sp" style="display: none !important;">
                        <div class="row title">
                            <div class="col"></div>
                            <div class="col text-center">ご注文内容</div>
                            <div class="col">
                                <div class="footer-order-hide"></div>
                            </div>
                        </div>
                        <hr>
                        <div class="row of-a" style="height: calc(100% - 155px);">
                            <div class="col item h-100p"></div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col p-0 text-center">
                                <div class="footer-order-count fw-b">
                                    0点
                                </div>
                            </div>
                            <div class="col text-center">
                                <button type="button" class="btn btn-primary m-auto" data-toggle="modal"
                                    data-target="#previewModal">プレビュー</button>
                            </div>
                            <div class="col footer-order-amount p-0 text-center">
                                0円
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-order-bottom">
                    <div class="footer-order-count">
                        0
                    </div>
                    <div class="footer-order-amount c-w">
                        0円
                    </div>
                    <div class="footer-order-skip">
                        スキップ
                    </div>
                    <div class="footer-order-show"></div>
                </div>
            </div>
        </footer>
    </form>
    <div id="loading-area">
        <div class="loader-wrapper">
            <div class="loader">Loading...</div>
        </div>
    </div>
    <script type="text/javascript">
        var survey_id = '<?= $survey_token; ?>';
    </script>
    <script src="./assets/js/script.js?v=20220815v2"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-toggle="popover"]').popover();
        });
    </script>
</body>

</html>