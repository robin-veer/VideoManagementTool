<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property Carbon $video_created_at;
 * @property mixed $id
 * @property string $path
 * @property mixed $file_name
 * @property bool $is_valid
 * @property int $duration
 * @property mixed $thumbnail
 * @property carbon $recorded_at
 * @property bool $has_gif
 */
class Video extends Model
{
    /**
     * @var string
     */
    protected $table = 'videos';

    protected $fillable = [
        'views',
        'file_name',
        'path',
        'category',
        'type',
        'recorded_at',
    ];

    protected $casts = [
        'options'     => 'array',
        'is_valid'    => 'bool',
        'recorded_at' => 'date'
    ];

    public static function unsorted(): Builder
    {
        return self::where('category', 'unknown');
    }

    public static function known(): Builder
    {
        return self::where('category', '!=', 'unknown');
    }

    public static function videosByTag(string $tag): Collection
    {
        return Video::whereHas('tags', function ($query) use ($tag) {
            $query->where('name', '=', $tag);
        })->get();
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getAbsolutePath(): string
    {
        return storage_path('app/public' . $this->path);
    }

    public function getGif(): ?string
    {
        if ($this->has_gif) {
            return "/gifs/$this->file_name";
        } else {
            return null;
        }
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getDateAgo(): string
    {
        return $this->recorded_at->diffForHumans();
    }

    public function setIsValid(bool $bool): void
    {
        $this->is_valid = $bool;
        $this->save();
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
        $this->save();
    }

    public function getAbsoluteThumbnailPath(): string
    {
        return public_path($this->thumbnail);
    }

    public function getSimilarVideos(int $maxResults = 8): Collection
    {
        $tags = $this->tags->pluck('name');

        return Video::known()->whereHas('tags', function ($query) use ($tags) {
            return $query->whereIn('name', $tags);
        })->limit($maxResults)->get();
    }
}
