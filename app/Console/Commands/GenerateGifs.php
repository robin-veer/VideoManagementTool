<?php

namespace App\Console\Commands;

use App\Video;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Console\Command;

class GenerateGifs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:preview';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
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
            ['has_gif', false],
            ['is_valid', true]
        ])->get();

        /** @var Video $video */
        foreach ($videos as $video):

            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
                'ffprobe.binaries' => '/usr/local/bin/ffprobe',
                'timeout'          => 3600, // The timeout for the underlying process
                'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
            ]);

            /** @var \FFMpeg\Media\Video $gif */
            $gif = $ffmpeg->open($video->getAbsolutePath());

            $clip_count = floor($video->duration / 60);
            $clips_array = [];

            $temp_dir = public_path() . "/gifs/" . date("U");
            mkdir($temp_dir, 0777, true);

            try {
                $this->info("Converting clips");
                for ($i = 1; $i <= $clip_count; $i++) {
                    $start_time = ($i * 60) - 5;
                    $file_name = $i . "_" . $video->file_name;
                    $file_name = str_replace(' ', '_', $temp_dir . "/" . $file_name);

                    $gif->gif(TimeCode::fromSeconds($start_time), new Dimension(640, 480),
                        1)->save($file_name);

                    array_push($clips_array, $file_name);
                }
            } catch (\Exception $e) {
                $this->warn($e->getMessage());
            }

            if (count($clips_array) > 0) {
                try {
                    $output = $ffmpeg->open($clips_array[0]);
                    $output
                        ->concat($clips_array)
                        ->saveFromSameCodecs(public_path('/gifs/') . $video->file_name);

                    $video->has_gif = true;
                    $video->save();
                } catch (\FFMpeg\Exception\RuntimeException $e) {
                    echo $e . "\n";
                    echo $video->id . "\n";
                }
            } else {
                $this->info("No clips where found");
            }
            foreach (scandir($temp_dir) as $file) {
                $rm_path = $temp_dir . '/' . $file;
                if (is_file($rm_path)) {
                    unlink($rm_path);
                }
            }

            rmdir($temp_dir);
        endforeach;
    }
}
