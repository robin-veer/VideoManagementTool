<?php

namespace App\Console\Commands;

use App\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ScanForNewVideos extends Command
{
    const SEACH_FOLDER = 'app/public/videos';
    const DEFAULT_CATEGORY = 'unknown';
    const DEFAULT_TYPE = null;
    const PUBLIC = "/videos/";


    protected $signature = 'fetch:videos:new';

    protected $description = 'Scans the default folder for new video\'s';

    /**
     * @var string
     */
    private $path;

    public function __construct()
    {
        $this->path = storage_path(self::SEACH_FOLDER);

        parent::__construct();
    }

    public function handle(): void
    {
        $fileCollection = $this->scanDir();
        $collection = $this->getVideoFilesOnly($fileCollection);

        foreach ($collection as $fileName) {
            if ($this->doesAlreadyExists($fileName)) {
                continue;
            }

            $this->info("New video found!");
            $this->createVideo($fileName);
        }
    }

    private function scanDir(): Collection
    {
        if (is_dir($this->path)) {
            return new Collection(scandir($this->path));
        } else {
            throw new \Exception("$this->path is not a directory");
        }
    }

    private function getVideoFilesOnly(Collection $collection): Collection
    {
        return $collection->filter(function ($item) {
            $pathInfo = pathinfo($this->path . "/$item");

            if (strtolower($pathInfo['extension']) == 'mp4') {
                return true;
            }

            return false;
        });
    }

    private function doesAlreadyExists(string $fileName): bool
    {
        return Video::where('file_name', $fileName)->count() !== 0;
    }

    private function createVideo(string $fileName): void
    {
        Video::create([
            'file_name'   => $fileName,
            'path'        => self::PUBLIC . $fileName,
            'category'    => self::DEFAULT_CATEGORY,
            'type'        => self::DEFAULT_TYPE,
            'is_valid'    => true,
            'recorded_at' => null,
        ]);
    }
}