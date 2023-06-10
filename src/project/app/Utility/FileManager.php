<?php

namespace App\Utility;

use Exception;
use ZipArchive;
use Illuminate\Support\Str;
use App\Utility\UploadInterface;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FileManager
{
    public $uploadWay;

    public function uploadWay(UploadInterface $uploadWay)
    {
        $this->uploadWay = $uploadWay;
    }

    public function upload()
    {
        return $this->uploadWay->upload();
    }

    /**
     * Get info file uploaded
     *
     * @return array|bool
     */
    public function getFileInfo()
    {
        return $this->uploadWay->getFileInfo();
    }

    public static function files($path)
    {
        return Storage::files($path);
    }

    public static function allFiles($path)
    {
        return Storage::allFiles($path);
    }

    public static function directories($path)
    {
        return Storage::directories($path);
    }

    public static function allDirectories($path)
    {
        return Storage::directories($path);
    }

    public static function fileListInfo($files)
    {
        $list = [];

        foreach ($files as $file) {
            $name = basename($file);
            $hasMedia = false;
            if (class_exists('App\Models\Media')) {
                $hasMedia = \App\Models\Media::where('name', $name)->exists();
            }

            $list[] = [
                'size' => Storage::size($file),
                'updatedAt' => date(
                    'Y-m-d H:i:s',
                    Storage::lastModified($file)
                ),
                'updatedAtFa' => date(
                    'Y-m-d H:i:s',
                    Storage::lastModified($file)
                ),
                'createdAt' => date(
                    'Y-m-d H:i:s',
                    Storage::lastModified($file)
                ),
                'createdAtFa' => date(
                    'Y-m-d H:i:s',
                    Storage::lastModified($file)
                ),
                'name' => $name,
                'extension' => pathinfo($file)['extension'],
                'basename' => pathinfo($file)['filename'],
                'path' => $file,
                'hasMedia' => $hasMedia,
                'mimeType' => Storage::mimeType($file),
                'type' => 'file',
            ];
        }
        return $list;
    }

    public static function directoryListInfo($directories)
    {
        $list = [];
        $i = 0;
        foreach ($directories as $dir) {
            $list[$i] = [
                'type' => 'folder',
                'mimeType' => 'ندارد',
                'path' => $dir,
                'size' => self::getFolderSize($dir),
                'name' => basename($dir),
                'details' => self::getFolderDetails($dir),
                'zipName' => basename($dir) . '.zip',
                'extension' => '-',
                'basename' => basename($dir),
            ];
            if (config('filesystems.default') !== 'ftp') {
                $n = [
                    'createdAt' => date(
                        'Y-m-d H:i:s',
                        Storage::lastModified($dir)
                    ),
                    'createdAtFa' => date(
                        'Y-m-d H:i:s',
                        Storage::lastModified($dir)
                    ),
                    'updatedAt' => date(
                        'Y-m-d H:i:s',
                        Storage::lastModified($dir)
                    ),
                    'updatedAtFa' => date(
                        'Y-m-d H:i:s',
                        Storage::lastModified($dir)
                    ),
                ];

                $list[$i] = array_merge($list[$i], $n);
            }
            $i++;
        }

        return $list;
    }

    public static function all($path)
    {
        return collect(
            self::directoryListInfo(self::allDirectories($path))
        )->merge(self::fileListInfo(self::allFiles($path)));
    }

    public static function list($path)
    {
        return collect(
            self::directoryListInfo(self::directories($path))
        )->merge(self::fileListInfo(self::files($path)));
    }

    public static function getFolderSize($dir)
    {
        $files = Storage::allFiles($dir);

        $files_with_size = [];

        if (count($files)) {
            foreach ($files as $file) {
                $files_with_size[] = self::getSizeFile($file);
            }

            return array_sum($files_with_size);
        }

        return 0;
    }

    public static function getSizeFile($file)
    {
        if (Storage::exists($file)) {
            return Storage::size($file);
        }

        return 0;
    }

    public static function isFile($path)
    {
        return Storage::mimeType($path);
    }

    public static function delete($paths)
    {
        if (is_string($paths)) {
            if (Storage::exists($paths)) {
                if (self::isFile($paths)) {
                    if (class_exists('App\Models\Media')) {
                        $media = \App\Models\Media::firstWhere(
                            'name',
                            basename($paths)
                        );

                        if ($media) {
                            $media->delete();
                        }
                    }

                    return Storage::delete($paths);
                } else {
                    return Storage::deleteDirectory($paths);
                }
            }
        } elseif (is_array($paths)) {
            foreach ($paths as $path) {
                if (self::isFile($path)) {
                    if (class_exists('App\Models\Media')) {
                        $media = \App\Models\Media::firstWhere(
                            'name',
                            basename($path)
                        );

                        if ($media) {
                            $media->delete();
                        }
                    }
                    Storage::delete($path);
                } else {
                    Storage::deleteDirectory($path);
                }
            }
        }

        return true;
    }

    public static function makeDirectory($path)
    {
        if (Storage::exists($path)) {
            throw new Exception(
                'دایرکتوری با چنین نامی وجود دارد، لطفا نام دیگری وارد نمایید'
            );
        }
        return Storage::makeDirectory($path);
    }

    public static function rename($currentName, $newName)
    {
        $newName = Str::lower($newName);

        if (self::isFile($currentName)) {
            $newName = $newName . '.' . pathinfo($currentName)['extension'];

            $newName = pathinfo($currentName)['dirname'] . '/' . $newName;

            if (!Storage::exists($newName)) {
                Storage::move($currentName, $newName);

                if (class_exists('App\Models\Media')) {
                    $file = \App\Models\Media::firstWhere(
                        'name',
                        pathinfo($currentName)['basename']
                    );

                    if ($file) {
                        $file->name = pathinfo($newName)['basename'];

                        $file->save();
                    }
                }

                return response()->json(
                    [
                        'status' => true,
                        'message' => 'نام فایل با موفقیت تغییر کرد',
                    ],
                    Response::HTTP_OK
                );
            } else {
                throw new Exception(
                    'فایلی با چنین نامی وجود دارد، لطفا نام دیگری وارد نمایید'
                );
            }
        } else {
            $newName = pathinfo($currentName)['dirname'] . '/' . $newName;

            if (!Storage::exists($newName)) {
                Storage::move($currentName, $newName);

                return response()->json(
                    [
                        'status' => true,
                        'message' => 'نام دایرکتوری با موفقیت تغییر کرد',
                    ],
                    Response::HTTP_OK
                );
            } else {
                throw new Exception(
                    'دایرکتورای با چنین نامی وجود دارد، لطفا نام دیگری وارد نمایید'
                );
            }
        }
    }

    public static function fileUpload($path, $file)
    {
        $extension = $file->getClientOriginalExtension();

        $name = self::generateFileName($path, $extension);

        $name .= '.' . $extension;

        return Storage::putFileAs($path, $file, $name);
    }

    public static function download($path)
    {
        if (self::isFile($path)) {
            return Storage::download($path);
        }

        if (!count(self::allFiles($path))) {
            throw new Exception(
                ' به دلیل خالی فولدر مورد نظر امکان دانلود ممکن نمی باشد'
            );
        }

        $disk = self::disk('public_html');

        $temp = 'cdn/temp';

        $disk->makeDirectory($temp);

        $zipPath =
            $temp .
            '/' .
            self::generateFileName($temp, '', 'public_html') .
            '.zip';

        self::zip($zipPath, $path);

        return $disk->download($zipPath);
    }

    public static function zip($zipPath, $path)
    {
        $zip = new ZipArchive();

        $files = self::allFiles($path);

        if (
            $zip->open(
                Storage::disk('public_html')->url($zipPath),
                ZipArchive::CREATE
            )
        ) {
            foreach ($files as $file) {
                $zip->addFromString(basename($file), Storage::get($file));
            }

            $zip->close();
        }
    }

    public static function disk($disk)
    {
        return Storage::disk($disk);
    }

    public static function getFolderDetails($path)
    {
        $files = self::allFiles($path);

        $list = [];
        foreach ($files as $file) {
            $list[] = pathinfo($file)['extension'];
        }

        $arr = array_count_values($list);

        return [
            'labels' => array_keys($arr),
            'values' => array_values($arr),
            'countFile' => count($files),
            'countDir' => self::countDir($path),
            'size' => self::getFolderSize($path),
        ];
    }

    public static function countDir($path)
    {
        return count(self::allDirectories($path));
    }

    public static function generateFileName(
        $path,
        $extension = null,
        $disk = null
    ) {
        if (!$disk) {
            $disk = config('systems.default');
        }

        $list = array_merge(range('a', 'z'), range(1, 9));

        while (true) {
            shuffle($list);

            $name = Str::substr(implode('', $list), 0, 10);

            if ($extension) {
                if (!Storage::disk($disk)->exists("$path/$name.$extension")) {
                    return $name;
                }
            } elseif (!Storage::disk($disk)->exists("$path/$name")) {
                return $name;
            }
        }
    }
}
