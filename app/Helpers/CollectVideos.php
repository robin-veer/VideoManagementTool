<?php

namespace App\Helpers;

use App\Video;
use FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use Illuminate\Support\Collection;

class CollectVideos
{
    const VIDEO_PATH = "/Users/pxlwdgts/Desktop/playground/Stranger/public/";
    const UNKNOWN_PATH = "storage/public/unknown/unknown";

    const DEFAULT_CATEGORY = "unknown";

    /**
     * @var Collection
     */
    protected $videos;

    public function processVideos(): void
    {
        $videos = Video::where([['thumbnail', null], ['valid_file', true]])->get();
        echo "Generating thumbnails.... \n";

        foreach ($videos as $video) {
            if (!$video->thumbnail) {
                $this->getImage($video);
            }
        }

        $videos = Video::where([
            ['duration', null],
            ['valid_file', true]
        ])->get();

        foreach ($videos as $video) {
            if (!$video->duration) {
                $this->getDuration($video);
            }
        }
    }

    public function getImage(Video $video): Video
    {
        $ffmpeg = FFMpeg\FFMpeg::create([
            'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/local/bin/ffprobe',
            'timeout'          => 3600, // The timeout for the underlying processw
            'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
        ]);


        $location = dirname(self::VIDEO_PATH) . "/public/thumbnails/" . $video->id . "_thumbnail.jpg";
        $relativeLocation = "/thumbnails/" . $video->id . "_thumbnail.jpg";

        try {
            $stream = $ffmpeg->open('storage/app/public' . $video->getPath());

            /** @var FFMpeg\Media\Video $stream */
            $stream
                ->frame(TimeCode::fromSeconds(10))
                ->save($location);
        } catch (\Exception $e) {
            dump($e->getMessage());
        }

        $video->thumbnail = $relativeLocation;
        $video->save();

        return $video;
    }

    public static function getDuration(Video $video): Video
    {
        $ffprobe = FFMpeg\FFProbe::create([
            'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/local/bin/ffprobe',
            'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
        ]);

        $location = 'storage/app/public' . $video->getPath();
        try {
            $duration = $ffprobe
                ->streams($location)
                ->videos()
                ->first()
                ->get('duration');
            $video->duration = $duration;
            $video->save();

            echo "Success: $video->id";
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
        }

        return $video;
    }
}
