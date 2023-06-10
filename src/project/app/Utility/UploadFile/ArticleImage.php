<?php

namespace App\Utility\UploadFile;

use App\Models\Media;
use App\Traits\ArticleImageTrait;
use App\Utility\UploadInterface;
use Illuminate\Http\UploadedFile;

class ArticleImage extends BaseFile implements UploadInterface
{
    use ArticleImageTrait;


    public function __construct(UploadedFile $file, $disk = 'public_html')
    {
        $year = date('Y');
        $month = date('m');

        parent::__construct( $file, $disk);

        $this->type = Media::IMAGE;
        $this->url = "cdn/uploads/articles/images/{$year}/{$month}";
    }
}
