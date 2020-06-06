<?php

namespace App\Helpers;

use App\Http\Controllers\Controller;
use App\Video;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class VideoFilterController extends Controller
{
    /**
     * @var Collection
     */
    protected $videos;

    public static function FilterVideos(Array $filter): LengthAwarePaginator
    {
        if (!isset($filter['order'])) {
            $filter['order'] = null;
        }

        if (!isset($filter['time'])) {
            $filter['time'] = null;
        }

        $videos = Video::where('category', '!=', 'unknown');

        if (isset($filter['tag'])) {
            if (is_array($filter['tag'])) {

                foreach ($filter['tag'] as $tag) {
                    $videos = $videos->whereHas('tags', function ($q) use ($tag) {
                        $q->where('name', $tag);
                    });
                }
            } else {
                $videos = $videos->whereHas('tags', function ($q) use ($filter) {
                    $q->where('name', $filter['tag']);
                });
            }
        }

        switch ($filter['order']) {
            case 'mv':
                $videos = $videos->orderBy('views', 'desc');
                break;

            case 'lg':
                $videos = $videos->orderBy('duration', 'desc');
                break;

            default:
                $videos = $videos->orderBy('recorded_at', 'desc');
                break;
        }

        if (isset($filter['daterange'])) {
            $dates = explode(' - ', $filter['daterange']);
            $videos = $videos->whereDate('recorded_at', '<=', Carbon::parse($dates[1]));
            $videos = $videos->whereDate('recorded_at', '>=', Carbon::parse($dates[0]));
        }

        switch ($filter['time']) {
            case 'y':
                $videos = $videos->where('recorded_at', '>=', Carbon::now()->subDays(365));
                break;

            case 'm':
                $videos = $videos->where('recorded_at', '>=', Carbon::now()->subDays(31));
                break;

            case 'w':
                $videos = $videos->where('recorded_at', '>=', Carbon::now()->subDays(7));
                break;

            default:
                break;
        }


        return $videos->paginate(50);
    }
}
