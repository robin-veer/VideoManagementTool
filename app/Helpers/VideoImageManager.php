<?php


namespace App\Helpers;


use App\Enums\PathTypes;
use App\Video;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Support\Facades\Storage;

class VideoImageManager
{
    /**
     * @var Video
     */
    private $video;

    /**
     * @var string
     */
    private $imageName;

    /**
     * @var string
     */
    private $imageLocation;

    /**
     * @var \FFMpeg\Media\Video
     */
    private $stream;

    /**
     * @var FFMpeg
     */
    private $ffmpeg;

    /**
     * @var int
     */
    private $amount;

    public function __construct(Video $video, FFMpeg $ffmpeg)
    {
        $this->ffmpeg = $ffmpeg;
        $this->video = $video;
        $this->imageName = $video->id . "-$this->amount-" . "_thumbnail.jpg";
        $this->imageLocation = public_path(PathTypes::THUMBNAIL_PATH);
        $this->amount = 0;
    }

    public function processVideo(): void
    {
        $this->stream = $this->ffmpeg->open(Storage::disk('public')->path($this->video->path));
        $this->saveImage(2);
    }

    private function saveImage(int $time): void
    {
        $location = $this->imageLocation . $this->video->id . "_$this->amount" . "_thumbnail.jpg";

        $this->stream
            ->frame(TimeCode::fromSeconds($time))
            ->save($location);

        $this->video->thumbnail = PathTypes::THUMBNAIL_PATH . $this->video->id . "_$this->amount" . "_thumbnail.jpg";

        $this->video->save();
    }

    private function cropImage(string $location): bool
    {
        try {
            $im = imagecreatefromjpeg($location);

            $im2 = imagecrop($im, [
                'x'      => 10,
                'y'      => 100,
                'width'  => 640,
                'height' => 340,
            ]);

            if ($im2 !== false) {
                imagejpeg($im2, $location);
                imagedestroy($im2);
            }

            imagedestroy($im);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


}