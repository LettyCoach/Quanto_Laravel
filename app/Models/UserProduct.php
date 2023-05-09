<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserProduct extends Model
{
    use HasFactory;

    /**
     * The roles that belong to the UserProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(UserProductCategory::class, 'product2_categories', 'product_id', 'category_id');
    }

    // public function userProductColor()
    // {
    //     return $this->belongsTo(UserProductColor::class, 'color_id');
    // }

    // public function userProductSize()
    // {
    //     return $this->belongsTo(UserProductSize::class, 'size_id');
    // }

    public function getProductID() {
        return sprintf("Q%05d%03d", $this->user_id, $this->id);
    }

    public function getImageUrls()
    {

        $rlt = explode("_", $this->img_urls);
        array_shift($rlt);
        array_pop($rlt);
        return $rlt;
    }

    public function getImageUrlFirst()
    {
        $rlt = $this->getImageUrls();
        if (count($rlt) == 0)
            return "public/img/blank-plus.png";
        return "public/user_product/" . $rlt[0];
    }

    public function getImageUrlsFullPath()
    {
        $rlt = $this->getImageUrls();
        foreach ($rlt as $i => $item) {
            $rlt[$i] = url('/public/user_product/' . $item);
        }
        return $rlt;
    }

    public function getImageUrlFirstFullPath()
    {
        $rlt = $this->getImageUrlsFullPath();
        if (count($rlt) == 0)
            return url('/public/user_product/blank-plus.png');
        return $rlt[0];
    }

    public function getCategoryIds_()
    {
        $models = $this->categories;
        $rlt = "_";
        foreach($models as $i => $model) {
            $rlt .= $model->id . "_";
        }

        return $rlt;
    }

    public function getCategoryText()
    {
        $models = $this->categories;

        $rlt = "";
        foreach ($models as $i => $model) {
            if ($i > 0)
                $rlt .= '、';
            $rlt .= $model->name;
        }

        return $rlt;
    }

    public function getOptionsText() {

        $options = $this->options;
        print_r($options);
        return json_encode($this->options);
    }

    public function getAllOptionNames() {
        $models = self::get();
        $rlt = ['カラー', 'サイズ', '素材'];

        foreach($models as $model) {
            if ($model->options == '') continue;
            $options = json_decode($model->options);
            if (count($options) == 0) continue;
            foreach($options as $option) {
                $name = $option->name;
                if (array_search($name, $rlt) != false) continue;
                array_push($rlt, $name);
            }
        }

        return $rlt;
    }

    public function getOptions() {
        $rlt = [];
        $options = $this->options;
        if ($options == '') return $rlt;

        $options = json_decode($options);
        foreach($options as $option) {
            $name = $option->name;
            $descriptions = $option->description;
            $dscString = "";
            foreach($descriptions as $i => $description) {
                if ($i > 0) $dscString .= ", ";
                $dscString .= $description;
            }
            $rlt[$name] = $dscString;
        }

        return $rlt;
    }

    // public function getMaterials()
    // {
    //     $rlt = explode("_m_", $this->materials);
    //     array_shift($rlt);
    //     array_pop($rlt);
    //     return $rlt;
    // }

    // public function getMaterialsText()
    // {
    //     $tmp = $this->getMaterials();
    //     $rlt = "";
    //     if (count($tmp) > 0)
    //         $rlt = $tmp[0];
    //     for ($i = 1; $i < count($tmp); $i++) {
    //         $rlt .= '、' . $tmp[$i];
    //     }

    //     return $rlt;
    // }
}
