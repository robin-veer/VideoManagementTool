<?php


namespace App\Helpers;


use App\Tag;

class getTags
{

    /**
     * @return mixed
     */
    public static function getTags()
    {
        return Tag::where('id', '>=', 1)->select('name')->groupBy('name')->orderBy('name', 'asc')->get();
    }
}
