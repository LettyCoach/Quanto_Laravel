login(email, password, user_type) {
    return {
        status: "successful / invalidEmail / invalidPassword",
        id: 2,
        name: 'Papiko',
        full_name: 'サンプル株式会社',
        email: 'laneandyumiko@gmail.com',
        zip_code: '157 - 0399',
        address: '東京都渋谷区渋谷1 - 1 - 1',
        phone_number: '03 - 0123 - 4567',
        profile_url: 'https://quanto3.com/uploads/users/Free-Logo-Maker-Get-Custom-Logo-Designs-in-Minutes-Looka_(11).png',
    }
}

register(email, password, name, full_name, address, zip_code, phone_number, profile_url) {
    return {
        status: "successful/existEmail"
    }
}

getVerifyCode(email) {
    return {
        status: "successful/invalidEmail",
        verifyCode: "0000"
    }
}


verifyCode(email, verifyCode) {
    return {
        status: "successful/invalidEmail/invalidCode",
        resetPasswordToken: 'oqwuisd9f7u234hoeur0sdufo2340dfhod8u023js0dr8fu20394', //lenght = 60
    }
}

restPassword(email, resetPasswordToken, password){
    return {
        status: "successful/invalidEmail"
    }
}


products / getList(user_id){
    return [{
        id, name, brandName, category, sku, price, amount, color, size, material, detail, img_url, img_urls, barcode, other, isSaved, isFavorite
    }]
}


products / getByBarcode(user_id, user_type, barcode) {
    return {
        id, name, brandName, category, sku, price, amount, color, size, material, detail, img_url, img_urls, barcode, other, isSaved, isFavorite
    }
}


products / getSaveItemList(user_id)
return [{
    id, name, brandName, category, sku, price, amount, color, size, material, detail, imgUrl, img_urls, barcode, other, isSaved, isFavorite
}]

products / addSaveItem(user_id, product_id) {
    return {
        status: "successful/alreadyExist"
    }
}


products / makeInvoice([{id, name, brandName, category, sku, price, amount, color, size, material, detail, img_url, barcode, other }]){

    return {
        name: 'invoice',
        pdf_url: "http://192.168.113.30:9016/uploads/products/1678891645.0961.pdf",
    }
}




//////////follow
buyer/getUserList() {
    return [
        {
            id: 2,
            name: 'Papiko',
            full_name: 'サンプル株式会社',
            email: 'laneandyumiko@gmail.com',
            zip_code: '157 - 0399',
            address: '東京都渋谷区渋谷1 - 1 - 1',
            phone_number: '03 - 0123 - 4567',
            profile_url: 'https://quanto3.com/uploads/users/Free-Logo-Maker-Get-Custom-Logo-Designs-in-Minutes-Looka_(11).png',
            supplyed: false/true,
            supplyAccepted: false/true
        }
    ]
}

buyer / setFollow(buyer_id, supplyer_id) {
    return { 
        status: "successful/alreadyFollow"
    }
}

buyer / unsetFollow(buyer_id, supplyer_id) {
    return {
        status: "successful"
    }
}

supplyer / getFollowedList(supplyer_id) {
    return {
        id: 2,
        name: 'Papiko',
        full_name: 'サンプル株式会社',
        email: 'laneandyumiko@gmail.com',
        zip_code: '157 - 0399',
        address: '東京都渋谷区渋谷1 - 1 - 1',
        phone_number: '03 - 0123 - 4567',
        profile_url: 'https://quanto3.com/uploads/users/Free-Logo-Maker-Get-Custom-Logo-Designs-in-Minutes-Looka_(11).png',
        isAccepted: 'true / false'
    }
}

supplyer / setFollowAccept(supplyer_id, buyer_id){
    return {
        status: "successful/failed"
    }
}


{
    "purpose_1": "ご請求書",
    "cDate": "2023-08-02", //発行日:
    "uName": "test_sample",
    "profile": "/uploads/users/about.jpg", //logined user Profile image user()->profile_url
    "serial": "請求書No,Q1002---------44", //請求書No,Q{{ Auth::user()->id + 1000 --------
    "company": "サンプル株式会社", //user()->full_name
    "display_total_price": "386,091",
    "display_reduce": "38,609", // default 10%
    "zipCode": "〒", // :user()->zip_code
    "adress": "東京都渋谷区渋谷1-1-1", // user()->address
    "phone": "Tel：03-0123-4567", //user()->phone_number
    "stamp": "/uploads/users/7177231_add_photo_take_picture_icon_(2).png", // $userSettings = json_decode(Auth::user()->settings); $stamp_url = isset($userSettings->stamp_url) ? $userSettings->stamp_url : '';
    "eDate": "2023-08-30", // tomorrow day
    "row_0": "https://www.quanto3.com/public/img/ic_add.png", // fixed
    "link_0": "https://www.quanto3.com/public/img/ic_link.png", // fixed
    "del_0": "https://www.quanto3.com/public/img/ic_delete.png", // fixed
    "tooltip_edit_0": "https://www.quanto3.com/public/img/edit_query_m.png", // fixed
    "tooltip_close_0": "https://www.quanto3.com/public/img/ic_modal_close.png", // fixed
  
    //first record
    "ID_0": "99425128", // //  UserProduct Model's $model->getProductID
    "productNum_0": "98", // userProduct's Id
    "timg_0": "https://quanto3.com/public/user_product/230802042054.png", // user product Image url
    "title_0": "ラタン ダイニングチェア / Robert", //userProduct Model's name
    "subt__44Kr44Op44O8_0": "ベージュ", // option's value of nth record
    "subt__44K144Kk44K6_0": "約W620×D600×H810・SH460mm　5kg",
    "subt__57Sg5p2Q_0": "ラタン(ラッカー塗装)",
    "subt__U2l6ZQ___0": "　",
    "subt__Q29sb3I__0": "　",
    "subt__TWF0_0": "　",
    "subt__b3RoZXI__0": "　",
    "subt__44Oq44O844OJ44K_44Kk44Og_0": "　",
    "price_0": "84,000", // price of nth record
    "quantity_0": "3", // amount of nth record
    "current_price_0": "252,000", // price * amount
    "reduce_pro_0": "10", // default 10
    "reduce_plus_0": "25,200", // default 0
  
    //second record
    "row_1": "https://quanto3.com/public/img/ic_add.png",
    "link_1": "https://quanto3.com/public/img/ic_link.png",
    "del_1": "https://quanto3.com/public/img/ic_delete.png",
    "tooltip_edit_1": "https://quanto3.com/public/img/edit_query_m.png",
    "tooltip_close_1": "https://quanto3.com/public/img/ic_modal_close.png",
    "ID_1": "99425127",
    "productNum_1": "99",
    "timg_1": "https://quanto3.com/public/user_product/230802042505.png",
    "title_1": "ラタンミラー L",
    "subt__44Kr44Op44O8_1": "MULTI",
    "subt__44K144Kk44K6_1": "W350×D15×H720mm",
    "subt__57Sg5p2Q_1": "ガラス、ラタン\n1.8 kg",
    "subt__U2l6ZQ___1": "　",
    "subt__Q29sb3I__1": "　",
    "subt__TWF0_1": "　",
    "subt__b3RoZXI__1": "　",
    "subt__44Oq44O844OJ44K_44Kk44Og_1": "　",
    "price_1": "9,091",
    "quantity_1": "1",
    "current_price_1": "9,091",
    "reduce_pro_1": "10",
    "reduce_plus_1": "0",
  
    //third record
    "row_2": "https://quanto3.com/public/img/ic_add.png",
    "link_2": "https://quanto3.com/public/img/ic_link.png",
    "del_2": "https://quanto3.com/public/img/ic_delete.png",
    "tooltip_edit_2": "https://quanto3.com/public/img/edit_query_m.png",
    "tooltip_close_2": "https://quanto3.com/public/img/ic_modal_close.png",
    "ID_2": "334135513602",
    "productNum_2": "100",
    "timg_2": "https://quanto3.com/public/user_product/230802043014.png",
    "title_2": "STUNNING LURE(スタニングルアー)｜【Messy Weekend】サングラス",
    "subt__44Kr44Op44O8_2": "ライトグリーン",
    "subt__44K144Kk44K6_2": "F",
    "subt__57Sg5p2Q_2": "ハンドクラフトアセテートフレーム・100% UV400 (UVA+UVB) レンズ・Category 1~3 Triton (USA) レンズ",
    "subt__U2l6ZQ___2": "　",
    "subt__Q29sb3I__2": "　",
    "subt__TWF0_2": "　",
    "subt__b3RoZXI__2": "　",
    "subt__44Oq44O844OJ44K_44Kk44Og_2": "　",
    "price_2": "13,000",
    "quantity_2": "5",
    "current_price_2": "65,000",
    "reduce_pro_2": "10",
    "reduce_plus_2": "0",
  
    //4th record
    "row_3": "https://quanto3.com/public/img/ic_add.png",
    "link_3": "https://quanto3.com/public/img/ic_link.png",
    "del_3": "https://quanto3.com/public/img/ic_delete.png",
    "tooltip_edit_3": "https://quanto3.com/public/img/edit_query_m.png",
    "tooltip_close_3": "https://quanto3.com/public/img/ic_modal_close.png",
    "ID_3": "g4550538461164",
    "productNum_3": "101",
    "timg_3": "https://quanto3.com/public/user_product/230802043237.png",
    "title_3": "NY ヤンキースキャップ",
    "subt__44Kr44Op44O8_3": "パステルグリーン",
    "subt__44K144Kk44K6_3": "cm：30×23×20",
    "subt__57Sg5p2Q_3": "ポリエステル100%　（刺繍糸：ポリエステル100%）",
    "subt__U2l6ZQ___3": "　",
    "subt__Q29sb3I__3": "　",
    "subt__TWF0_3": "　",
    "subt__b3RoZXI__3": "　",
    "subt__44Oq44O844OJ44K_44Kk44Og_3": "　",
    "price_3": "5,500",
    "quantity_3": "10",
    "current_price_3": "55,000",
    "reduce_pro_3": "10",
    "reduce_plus_3": "0",
  
    //5th record
    "row_4": "https://quanto3.com/public/img/ic_add.png",
    "link_4": "https://quanto3.com/public/img/ic_link.png",
    "del_4": "https://quanto3.com/public/img/ic_delete.png",
    "tooltip_edit_4": "https://quanto3.com/public/img/edit_query_m.png",
    "tooltip_close_4": "https://quanto3.com/public/img/ic_modal_close.png",
    "ID_4": "99425108",
    "productNum_4": "102",
    "timg_4": "https://quanto3.com/public/user_product/230802050257.png",
    "title_4": "ルージュ・アンテルディ・クリーム・ベルベット / ジバンシイ",
    "subt__44Kr44Op44O8_4": "No.27 インフューズド・レッド",
    "subt__44K144Kk44K6_4": "　6.5mL",
    "subt__57Sg5p2Q_4": "　",
    "subt__U2l6ZQ___4": "　",
    "subt__Q29sb3I__4": "　",
    "subt__TWF0_4": "　",
    "subt__b3RoZXI__4": "　",
    "subt__44Oq44O844OJ44K_44Kk44Og_4": "　",
    "price_4": "5,000",
    "quantity_4": "1",
    "current_price_4": "5,000",
    "reduce_pro_4": "10",
    "reduce_plus_4": "500",
  
    "total_count": "20", // sum of product count
    "total_price": "386,091", //
    "reduce_price": "38,609",
    "memo_text": "● 金融機関名：〇〇銀行\n● 支店名：△△支店\n● 支店コード：123\n● 口座番号：9876543210\n● 口座種類：普通\n● 口座名義：カブシキガイシャ　テスト\n恐れ入りますが、振込手数料は御社負担でお願いします\nお振込み期限：◇◇年◇◇月◇◇日",
    "totalAmount10": "386,091", // current_price_0
    "totalAmount10s": "38,609", // reduce_price
    "totalAmount88": "0", // 0
    "totalAmount88s": "0", // 0
    "totalAmount8": "0", // 0
    "totalAmount8s": "0", //0
  
    "img_upload_url_1": "https://quanto3.com/public/img/blank-plus.png", // default
    "item_productID": "Q00066088", // last record's product Id
    "item_price": "81,728", // // last record's product price
    "item_like": "NOLIKE", // NoLike
    "item_h_name": "ゆったり座れるアームレスソファ",// last record's product name
    "item_id": "88", // // last record's product id
    "item__44Kr44Op44O8": "222", // default remove
    "item__57Sg5p2Q": "2232", // default remove
    "img_upload_img": "https://quanto3.com/public/img/blank.png", // default
    "img_upload_url": "blank.png", // default
    "hostUrl": "https://quanto3.com", // default
    "rowCount": "5", // number of current records
    "ic_add": "https://quanto3.com/public/img/ic_add.png", // default
    "ic_del": "https://quanto3.com/public/img/ic_delete.png", // default
    "ic_edit": "https://quanto3.com/public/img/edit_query.png", // default
    "ic_blank": "https://quanto3.com/public/img/blank-plus.png", // default
    "ic_link": "https://quanto3.com/public/img/ic_link.png", // default
    "ic_check": "https://quanto3.com/public/img/ic_check.png", // default
    "ic_newblank": "https://quanto3.com/public/img/blank.png", // default
    "price": "0", // default
    "count_product": "0", // default
    "tag_1": "https://quanto3.com/public/img/img_03/tag_off.png", // default
    "tag_2": "https://quanto3.com/public/img/img_03/tag_on.png", // default
    "image_show": "checked", // default
    "uName_font_size": "28", // default
    "uTitle_font_size": "40", // default
    "varient_check__44Kr44Op44O8": "checked", // default
    "varient_check__44K144Kk44K6": "checked", // default
    "varient_check__57Sg5p2Q": "checked", // default
    "varient_check__U2l6ZQ__": "", // default
    "varient_check__Q29sb3I_": "", // default
    "varient_check__TWF0": "", // default
    "varient_check__b3RoZXI_": "", // default
    "varient_check__44Oq44O844OJ44K_44Kk44Og": "", // default
    "count_option_checked": 3 // default
  }
  