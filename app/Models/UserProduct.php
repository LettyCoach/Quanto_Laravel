<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use File;
use Auth;

class UserProduct extends Model
{
    use HasFactory;

    private $addImgURL = "public/img/img_03/add_plus.png";
    private $blankImgURL = "public/img/blank-plus.png";
    private $imgPath = "public/user_product/";

    /**
     * The roles that belong to the UserProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(UserProductCategory::class, 'product2_categories', 'product_id', 'category_id');
    }

    /**
     * The roles that belong to the UserProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'save_items', 'product_id', 'user_id');
    }

    public function getProductID() {
        return sprintf("Q%05d%03d", $this->user_id, $this->id);
    }

    public function getShortName() {
        $name = $this->name;

        if (mb_strlen($name, 'UTF-8') > 7) {
            return mb_substr($name, 0, 6, 'UTF-8') . '...';
        }
        return $name;
    }

    public function getFullPath($url, $flag) {

        if ($url == "" || !File::exists($this->imgPath . $url)) {
            if ($flag == "add")
                return $this->addImgURL;
            else if($flag == "blank") {
                return $this->blankImgURL;
            }
        }
        return $this->imgPath . $url;
    }

    public function getImageUrls()
    {
        $rlt = [];
        if (strlen($this->img_urls) > 0) {
            $rlt = json_decode($this->img_urls, true);
        }

        $limit = 10;
        $cnt = count($rlt);

        if($cnt < $limit) {
            for ($i = 0; $i < $limit - $cnt; $i ++){
                array_push($rlt, [
                    'name' => '',
                    'url' => '',
                    'state' => 'blank'
                ]);
            }
        }

        $limit = 18;
        $cnt = count($rlt);
        for ($i = $cnt; $i < $limit; $i ++){
            array_push($rlt, [
                'name' => '',
                'url' => '',
                'state' => 'none'
            ]);
        }


        foreach($rlt as $i => $e) {
            
            // if($e['state'] == '') continue;

            if ($i == 0) {
                $rlt[$i]['url'] = $this->getFullPath($e['name'], "add");
            }
            else {
                $rlt[$i]['url'] = $this->getFullPath($e['name'], "blank");
            }
        }

        return $rlt;
    }

    public function getImageUrlsFullPath() {

        $rlt = $this->getImageUrls();
        foreach($rlt as $i => $e) {

            if ($i == 0) {
                $rlt[$i]['url'] = url($this->getFullPath($e['name'], "add"));
            }
            else {
                $rlt[$i]['url'] = url($this->getFullPath($e['name'], "blank"));
            }
        }

        return $rlt;
    }

    public function getImageUrls_JSON() {
        $rlt = $this->getImageUrls();
        return json_encode($rlt);
    }

    public function getImageUrlFirst($flag = 'add')
    {
        $rlt = $this->getImageUrls();
        
        return $this->getFullPath($rlt[0]['name'], $flag);
    }

    public function getImageUrlFirstFullPath($flag = 'add')
    {
        $rlt = $this->getImageUrls();
        
        return url($this->getImageUrlFirst($flag));
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

        $options = $this->getOptions();
        $rlt = "";
        $i = 0;
        foreach($options as $name => $option) {
            if ($i > 0) $rlt .= "<br>";
            if ($name != 'カラー' && $name != 'サイズ' && $name != '素材') {
                $rlt .= "<b>$name</b><br>";
            }
            $rlt .= $option;
            $i ++;
        }

        return $rlt;
    }

    public function getAllOptionNames() {
        $user_id = Auth::user()->id;
        $models = self::where('id', $user_id)->get();
        $rlt = ['カラー', 'サイズ', '素材'];

        foreach($models as $model) {
            if ($model->options == '') continue;
            $options = json_decode($model->options);
            if (count($options) == 0) continue;
            foreach($options as $option) {
                $name = $option->name;
                if (array_search($name, $rlt) !== false) continue;
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

        $names = $this->getAllOptionNames();
        $tmp = [];
        foreach($names as $i => $name) {
            if (isset($rlt[$name]) == false) continue;
            $tmp[$name] = $rlt[$name];
        }

        return $tmp;
    }

    public function getOptionsArray() {
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
            array_push($rlt, [
                'name' => $name,
                'descriptions' => $dscString
            ]);
        }

        return $rlt;
    }

    public function txtPrice() {
        
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
