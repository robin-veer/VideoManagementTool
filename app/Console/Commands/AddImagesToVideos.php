<?php

namespace App\Console\Commands;

use App\Helpers\CollectVideos;
use App\Helpers\VideoImageManager;
use App\Video;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Illuminate\Console\Command;

class AddImagesToVideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:video:images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates images for all videoss';

    /**
     * @var FFMpeg
     */
    private $ffmpeg;

    /**
     * @var FFProbe
     */
    private $ffprobe;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/local/bin/ffprobe',
            'timeout'          => 3600, // The timeout for the underlying processw
            'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
        ]);

        $this->ffprobe = FFProbe::create();

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $videos = Video::where([
//            ['thumbnail', null],
            ['is_valid', true]
        ])->get();

        foreach ($videos as $video) {
            $videoManager = new VideoImageManager($video, $this->ffmpeg);
            $videoManager->processVideo();

            CollectVideos::getDuration($video);
        }
    }

    private function isValidFile(Video $video): bool
    {
        try {
            $duration = $this->ffprobe
                ->streams($video->getAbsolutePath())
                ->videos()
                ->first()
                ->get('duration');

            if ($video->duration === 0) {
                $video->setDuration($duration);
            }

        } catch (\Exception $e) {
            $this->warn("$video->file_name is invalid!");
//            $video->setIsValid(false);
//            $video->setDuration(0);
        }

        return $video->is_valid;
    }
}
