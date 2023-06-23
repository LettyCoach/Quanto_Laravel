<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Content;

class LP extends Model
{
    use HasFactory;

    protected $table = 'lps';

    public function statuses()
    {
        return $this->belongsTo(Status::class, 'status');
    }

    public function getDefaultContentSets()
    {

        for ($type = 1; $type <= 3; $type++) { //表示するタイプ順に3タイプを順に収める

            for ($i = 1; $i <= 3; $i++) { //3回づつ実行

                $content_ids[] = self::addNewContent($this->id, $type);

            }

        }

        //コレクションとして取得
        $contents = Content::whereIn('id', $content_ids)->get()->sortBy("ord");

        return $contents;

    }

    private function addNewContent($lp_id, $type)
    {

        $content = new Content();
        $content->lp_id = $lp_id;
        $content->type = $type;
        $content->save();

        $content->ord = $content->id;
        $content->save();
        return $content->id;

    }

    //コンテンツの有無を調べる
    public function checkContentExist($type)
    {

        $result = false;

        $content = Content::where('lp_id', $this->id)->where('type', $type)->first();

        if (!empty($content))
            $result = true;

        return $result;

    }

}