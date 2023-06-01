<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Auth;
use File;

class UserProductCategory extends Model
{
    use HasFactory;

    private $addImgURL = "public/img/img_03/plus_img.png";
    private $blankImgURL = "public/img/blank-plus.png";
    private $imgPath = "public/user_product_category/";

    /**
     * The roles that belong to the UserProductCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function productes(): BelongsToMany
    {
        return $this->belongsToMany(UserProduct::class, 'product2_categories', 'category_id', 'product_id');
    }

    public function getImageUrl($flag = false)
    {
        $url = $this->main_img_url;

        if ($url == "" || !File::exists($this->imgPath . $url)) {
            if ($flag == false)
                return $this->addImgURL;
            else {
                return $this->blankImgURL;
            }
        }

        return $this->imgPath . $url;
    }

    public function getImageUrlFullPath($flag = false)
    {
        return url($this->getImageUrl($flag));
    }

    public function getProductes() {
        $productes = $this->productes;
        $rlt = [];
        foreach($productes as $product) {
            array_push($rlt, [
                "id" => $product->id,
                "img_url" => $product->getImageUrlFirstFullPath('blank')
            ]);
        }

        return json_encode($rlt);
    }

    public function getProductesAll() {
        $user_id = Auth::user()->id;
        $productes = null;
        if (Auth::user()->isAdmin()) {
            $productes = UserProduct::all();
        } else {
            $productes = UserProduct::where('user_id', $user_id)->get();
        }

        $rlt = [];
        foreach($productes as $product) {
            array_push($rlt, [
                "id" => $product->id,
                "name" => $product->name,
                "img_url" => $product->getImageUrlFirstFullPath('blank')
            ]);
        }

        return json_encode($rlt);
    }
}
