<?php

namespace App\Utility\UploadFile;

use App\Models\Media;
use App\Utility\UploadInterface;
use Illuminate\Http\UploadedFile;
use App\Utility\UploadFile\BaseFile;

class Favicon extends BaseFile implements UploadInterface
{
    public function __construct(UploadedFile $file, $disk = 'public_html')
    {
        $year = date('Y');
        $month = date('m');

        parent::__construct( $file, $disk);

        $this->type = Media::IMAGE;
        $this->url = "cdn/settings/favicons/{$year}/{$month}";
    }
}
