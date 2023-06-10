<?php

namespace App\Utility\UploadFile;

use App\Models\Media;
use App\Utility\UploadInterface;
use App\Traits\ArticleImageTrait;
use Illuminate\Http\UploadedFile;

class ArticleSpecificImage extends BaseFile implements UploadInterface
{
    use ArticleImageTrait;

    public function __construct(UploadedFile $file, $disk = 'public_html')
    {
        $year = date('Y');
        $month = date('m');

        parent::__construct( $file, $disk);

        $this->type = Media::SPECIFIC_IMAGE;
        $this->url = "cdn/uploads/articles/specific/{$year}/{$month}";
    }
}
