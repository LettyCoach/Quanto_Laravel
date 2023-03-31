<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProduct extends Model
{
    use HasFactory;

    public function userProductColor()
    {
        return $this->belongsTo(UserProductColor::class, 'color_id');
    }

    public function userProductSize()
    {
        return $this->belongsTo(UserProductSize::class, 'size_id');
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
        foreach($rlt as $i => $item) {
            $rlt[$i] = url('/public/user_product/'. $item);
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

    public function getCategoryIds()
    {

        $rlt = explode("_", $this->category_ids);
        array_shift($rlt);
        array_pop($rlt);
        return $rlt;
    }

    public function getCategoryText()
    {
        $listCategoryId = $this->getCategoryIds();

        $rlt = "";
        foreach ($listCategoryId as $i => $id) {
            if ($i > 0)
                $rlt .= '、';
            $rlt .= UserProductCategory::find($id)->name;
        }

        return $rlt;
    }

    public function getMaterials()
    {
        $rlt = explode("_m_", $this->materials);
        array_shift($rlt);
        array_pop($rlt);
        return $rlt;
    }

    public function getMaterialsText()
    {
        $tmp = $this->getMaterials();
        $rlt = "";
        if (count($tmp) > 0)
            $rlt = $tmp[0];
        for ($i = 1; $i < count($tmp); $i++) {
            $rlt .= '、' . $tmp[$i];
        }

        return $rlt;
    }
}