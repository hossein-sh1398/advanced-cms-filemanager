<?php

namespace App\Utility\UploadFile;

use Illuminate\Support\Str;
use App\Utility\FileManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BaseFile
{
    public $file;
    public $mime_type;
    public $name;
    public $size;
    public $disk;
    public $fileInfo;
    public $optimize;
    public $url;
    public $type;

    public function __construct(UploadedFile $file, $disk = 'public_html')
    {
        $this->file = $file;
        $this->mime_type = $file->getClientOriginalExtension();
        $this->size = $file->getSize();
        $this->disk = $disk;
        $this->fileInfo = [];
        $this->optimize = null;
        $this->name = FileManager::generateFileName($this->url, $this->mime_type);
    }


    public function upload()
    {
        if ( is_null($this->file)) {
            return false;
        }

        $name = "$this->name.$this->mime_type";

        Storage::disk($this->disk)->putFileAs($this->url, $this->file, $name);

        $this->fileInfo = [
            'name' => $name,
            'mime_type' => $this->mime_type,
            'disk' => $this->disk,
            'size' => $this->size,
            'user_id' => auth()->id(),
            'url' => $this->url,
            'uuid' => Str::random(80) . time(),
            'type' => $this->type,
        ];

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
