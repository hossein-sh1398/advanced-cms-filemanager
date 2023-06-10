<?php

namespace App\Traits;

use App\Models\Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

trait ArticleImageTrait {

    public function settings()
    {
        $configs = Config::whereIn('key', [
            'image_optimize_active',
            'image_optimize_value',
            'image_watermark_active',
            'image_watermark_file',
            'image_watermark_opacity',
            'image_watermark_percent',
            'image_watermark_position',
            'image_resize_active',
            'image_resize_value',
            ])->pluck('value', 'key')->toArray();

        $list = [
            'optimize' => [
                'active' => $configs['image_optimize_active'] ?? false,
                'value' => $configs['image_optimize_value'] ?? 0
            ],
            'watermark' => [
                'active' => $configs['image_watermark_active'] ?? false,
                'file' => $configs['image_watermark_file'] ?? null,
                'opacity' => $configs['image_watermark_opacity'] ?? null,
                'percent' => $configs['image_watermark_percent'] ?? null,
                'position' => $configs['image_watermark_position'] ?? null,
            ],
            'resize' => [
                'active' => $configs['image_resize_active'] ?? false,
                'size' => $configs['image_resize_value'] ?? []
            ],
        ];

        return $list;
    }

    public function resize($settings)
    {
        $list = [];

        foreach ($settings['resize']['size'] as $value) {
            if (count($value) == 2 && isset($value[0], $value[1])) {
                $thumbnail = Image::make($this->file)->resize($value[0], $value[1]);

                if ($settings['watermark']['active']) {
                    $thumbnail = $this->setWaterMark($settings, $thumbnail);
                }

                $name = "{$this->name}_{$value[0]}_{$value[1]}.{$this->mime_type}";

                if (!File::exists(public_path('cdn/temp'))) {
                    File::makeDirectory(public_path('cdn/temp'));
                }

                $temporaryPath = public_path('cdn/temp/'. $name);

                $thumbnail->save($temporaryPath, $this->optimize($settings));

                $list[] = [
                    'temporaryPath' => $temporaryPath,
                    'name' => $name,
                    'size' => $thumbnail->filesize(),
                ];
            }
        }

        return $list;
    }

    private function setWaterMark($settings, $file)
    {
        if ($settings['watermark']['file']) {
            $waterMark = Storage::get($settings['watermark']['file']);

            $waterMark = Image::make($waterMark);

            $file->insert($waterMark, $settings['watermark']['position'], 10, 10);
        }

        return $file;
    }

    private function optimize($settings)
    {
        if ($settings['optimize']['active']) {
            return $settings['optimize']['value'] ?? null;
        }

        return null;
    }

    public function upload()
    {
        $settings = $this->settings();

        $name = "{$this->name}.{$this->mime_type}";

        $file = Image::make($this->file);

        if (!File::exists(public_path('cdn/temp'))) {
            File::makeDirectory(public_path('cdn/temp'));
        }

        $temporaryPath = public_path('cdn/temp/'. $name);

        if ($settings['watermark']['active']) {

            $file = $this->setWaterMark($settings, $file);
        }

        $file->save($temporaryPath, $this->optimize($settings));

        Storage::putFileAs($this->url, $temporaryPath , $name);

        $this->fileInfo[] = [
            'name' => $name,
            'url' => $this->url,
            'user_id' => auth()->id(),
            'uuid' => Str::random(80) . time(),
            'size' => $file->filesize(),
            'disk' => $this->disk,
            'mime_type' => $this->mime_type,
            'type' => $this->type,
        ];

        File::delete($temporaryPath);

        if ($settings['resize']['active']) {
            $listFile = $this->resize($settings);

            if (is_array($listFile) && count($listFile)) {
                foreach ($listFile as $value) {
                    Storage::putFileAs($this->url, $value['temporaryPath'], $value['name']);

                    $this->fileInfo[] = [
                        'name' => $value['name'],
                        'url' => $this->url,
                        'user_id' => auth()->id(),
                        'uuid' => Str::random(80) . time(),
                        'size' => $value['size'],
                        'disk' => $this->disk,
                        'mime_type' => $this->mime_type,
                        'type' =>$this->type,
                    ];

                    File::delete($value['temporaryPath']);
                }
            }
        }

        return true;
    }

    public function getFileInfo()
    {
        if ($this->file) {
            return $this->fileInfo;
        }

        return false;
    }
}
