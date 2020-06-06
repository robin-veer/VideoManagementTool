<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Tag extends Model
{
    protected $table = 'tags';
    protected $fillable = ['name'];

    public static function unique(): Collection
    {
        return Tag::select('name')->distinct()->get()->sort();
    }

    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class);
    }
}
