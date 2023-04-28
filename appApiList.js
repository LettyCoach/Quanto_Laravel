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
    id, name, brandName, category, sku, price, amount, color, size, material, detail, img_url, img_urls, barcode, other, isSaved, isFavorite
}]

products / addSaveItem(user_id, product_id) {
    return {
        status: "successful/alreadyExist"
    }
}


products / makeInvoice([{ name, brandName, category, sku, price, amount, color, size, material, detail, img_url, barcode, other }]){

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
