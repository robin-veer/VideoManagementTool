<?php

namespace App\Http\Controllers;

use App\Helpers\VideoFilterController;
use App\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use League\Flysystem\FileExistsException;

class VideoController extends Controller
{

    public function index(Request $request): View
    {
        return view('home', [
            'videos' => VideoFilterController::FilterVideos($request->request->all()),
            'count'  => Video::known()->count()
        ]);
    }

    public function showAll(Request $request): View
    {
        return view('home', [
            'videos' => VideoFilterController::FilterVideos($request->request->all()),
        ]);
    }

    public function showUnsorted(Request $request): View
    {
        return view('home', [
                'videos' => Video::unsorted()->orderBy('duration', 'desc')->get()
            ]
        );
    }


    public function show(Video $video): View
    {
        return view('video', [
            'video'          => $video->load('tags'),
            'sidebar_videos' => $video->getSimilarVideos()
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $video = Video::find($request['video']);
        $old_path = "public/" . $video->path;

        $video->category = $request['category'];
        $video->type = $request['sub-category'];

        $new_path = "/" . $video->category . "/" . $video->type . "/" . $video->file_name;

        $video->path = $new_path;

        try {
            Storage::move($old_path, 'public/' . $new_path);
        } catch (FileExistsException $exception) {
            Storage::delete($old_path);
        }
        $video->save();

        return redirect(route('video.sort'));
    }
}
