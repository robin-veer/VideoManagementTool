<?php

namespace App\Http\Controllers;

use App\Helpers\VideoFilterController;
use App\Tag;
use App\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TagController extends Controller
{
    /**
     * @var Collection $tags
     */
    protected $tags;

    public function __construct()
    {
        $this->tags = Tag::withCount('videos')->get()->sortBy('name');
    }

    public function show(Request $request, string $tag): View
    {
        $params['tag'] = explode('+', $tag);

        return View('home', [
            'videos' => VideoFilterController::FilterVideos($params),
            'tags' => $params['tag']
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $params = $request->request->all();
        $video = Video::find($params['id']);
        $tags = $params['tags'];

        foreach ($params['tags'] as $tag) {
            $tag = Tag::firstOrCreate(['name' => ucwords(strtolower($tag))]);
            $video->tags()->save($tag);
        }

        return redirect(route('video.show', $video));
    }
}
