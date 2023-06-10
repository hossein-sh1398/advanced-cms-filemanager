<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Utility\Table;
use Illuminate\Support\Str;
use App\Utility\FileManager;
use Illuminate\Http\Request;
use App\Utility\Export\Export;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Storage;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class FileManagerController extends Controller
{
    public $path;
    public $temp;

    public function __construct($enableAuthorize = true)
    {
        parent::__construct($enableAuthorize);

        $this->table = new Table();

        $this->path = '/cdn/uploads';

        $this->start = count(explode('/', $this->path)) - 1;
    }

    /**
     *
     * @return View
     */
    public function index(): View
    {
        $this->pageInfo->title = 'مدریت فایل ها و فولدرها';

        $extensions = [
            'jpg',
            'gif',
            'png',
            'jpeg',
            'mp4',
            'pdf',
            'csv',
            'txt',
        ];

        $params = [
            'path' => $this->path,
            'start' => $this->start,
            'url' => route('admin.file.manager.ajax'),
            'extensions' => $extensions,
        ];

        return view('admin.pages.file-managers.index', $params);
    }

    /**
     *
     * get list file and folders
     *
     * @param Request $request
     */
    public function list(Request $request)
    {
        try {
            $list = $this->getList();
        } catch (Exception $e) {
           if ($request->ajax()) {
               return response()->json([
                   'success' => false,
                   'message' => $e->getMessage(),
               ], Response::HTTP_BAD_REQUEST);
           }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.pages.file-managers.data', compact('list'))->render(),
                'links' => view('admin.theme.elements.paginate-link', [
                    'models' => $list,
                    'table' => $this->table->toArray(),
                ])->render(),
            ], Response::HTTP_OK);
        }
    }

    public function getList()
    {
        $path = request()->query('path');

        $size = request()->query('size', 10);
        $query = request()->query('query', null);
        $dir = request()->query('dir', 'desc');
        $sort = request()->query('sort', 'name');

        $stringExtension = request()->query('extension', null);

        if ((bool)request()->query('is_full_search')) {
            $list = FileManager::all($this->path);
        } else {
            $list = FileManager::list($path);
        }

        if ($dir === 'asc') {
            $list = $list->sortBy($sort);
        } else {
            $list = $list->sortByDesc($sort);
        }

        if ($query) {
            $list = $list->filter(function ($item) use ($query) {
                return Str::contains($item['name'], $query);
            });
        }

        if ($stringExtension) {
            $extensions = explode(',', $stringExtension);

            $extensions = array_filter($extensions);

            if (count($extensions)) {
                $list = $list->whereIn('extension', $extensions);
            }
        }

        $list = $this->mapHandler($list);

        $list = $list->paginate($size)->withQueryString();

        return $list;
    }

    private function mapHandler($list)
    {
        return $list->map(function ($item) {
            $type = $item['type'];

            $item['size'] = $this->sizeHandler($item);

            list($name, $icon) = $this->mimeTypeIconHandler($item);

            $item['helper'] = compact('type', 'name', 'icon');

            if ($item['type'] === 'file') {
                $item["createdAtFa"] = verta($item["createdAt"])->format('j F Y ساعت H:i');

                $item["updatedAtFa"] = verta($item["updatedAt"])->format('j F Y ساعت H:i');
            }

            return $item;
        });
    }

    /**
     *
     * destroy tag
     *
     * @param Tag $tag
     * @return JsonResponse|ResponseFactory
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            FileManager::delete($request->query('path'));

            return response()->json([
                'status' => true,
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => false], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     *
     *
     * @param Tag $tag
     * @return JsonResponse|ResponseFactory
     */
    public function makeDirectory(Request $request): JsonResponse
    {
        try {
            $path = $request->input('path') . '/' . $request->input('name');

            FileManager::makeDirectory($path);

            return response()->json([
                'status' => true,
                'message' => 'دایرکتوری با موفقیت ایجاد شد'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     *
     * @return JsonResponse|ResponseFactory
     */
    public function rename(Request $request): JsonResponse
    {
        try {
            $currentName = $request->input('old');
            $newName = $request->input('new');

            return FileManager::rename($currentName, $newName);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     *
     *
     * @return JsonResponse|ResponseFactory
     */
    public function upload(Request $request): JsonResponse
    {
        try {
            FileManager::fileUpload($request->query('path'), $request->file('file'));

            return response()->json(['status' => true]);
        } catch (Exception $e) {
            return response()->json(['status' => false], Response::HTTP_BAD_REQUEST);
        }
    }


    public function download(Request $request)
    {
        try {
            return FileManager::download($request->query('path'));
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     *
     * delete multiple selected
     *
     * @return JsonResponse|ResponseFactory
     */
    public function multipleDestroy(Request $request): JsonResponse
    {
        try {
            FileManager::delete($request->input('ids'));
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'status' => true,
        ]);
    }

    private function mimeTypeIconHandler($item): array
    {
        if ($item['type'] == 'folder') {
            return ['Directory', 'folder'];
        }

        $txtType = $item['extension'] === 'txt' ? 'Text' : 'CSV';
        $txtIcon = $item['extension'] === 'txt' ? 'file-text' : 'file-csv';
        return match ($item['mimeType']) {
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif' => ['Image', 'image'],
            'text/plain' => [$txtType, $txtIcon],
            'application/pdf' => ['PDF', 'file-pdf'],
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => ['EXCEL', 'file-excel'],
            'video/mp4' => ['Video', 'file-video'],
            'audio/mpeg' => ['Audio', 'file-audio'],
            'application/zip' => ['Zip', 'file-zip'],
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['Word', 'file-word'],
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => ['PowerPoint', 'file-powerpoint'],
            default => ['file', 'file'],
        };
    }

    private function sizeHandler($item): array
    {
        $kb = round($item['size'] / 1000, 1);
        $mb = round($item['size'] / (1000 * 1024), 2);
        return [
            'byte' => round($item['size']),
            'kb' => $kb,
            'mb' => $mb,
            'show' => $kb < 1000 ? "$kb KB" : "$mb MB",
            'class' => $kb < 1000 ? 'primary' : 'info',
        ];
    }

    public function export()
    {
        $cols = __('columns.file-manager');

        $all = (bool) request()->query('all', false);

        if ($all) {
            $files = FileManager::all($this->path);

            $files = $this->mapHandler($files);
        } else {
            $files = $this->getList();
        }

        $list = [];
        foreach ($files as $file) {

            $list[] = [
                'name' => $file['name'],
                'type' => $file['type'],
                'mimeType' => $file['mimeType'],
                'extension' => $file['extension'] ?? '-',
                'size' => $file['size']['show'],
                'createdAt' => $file['createdAtFa'],
            ];
        }

        $export = new Export();

        if (request()->input('type') === 'excel' ) {
            return $export->excel($list, [$cols]);
        } elseif (request()->input('type') === 'csv') {
            return $export->csv($list, array_values($cols),);
        } else {
            $fileName = 'files';

            $pdf = PDF::loadView('admin.theme.elements.pdf', [
                'data' => $list,
                'thead' => array_values($cols),
            ], [], [
                'format' => 'A4-L'
            ]);

            return $pdf->download("$fileName.pdf");
        }
    }

    public function showDetails()
    {
        $path = request()->query('path');

        $data = FileManager::getFolderDetails($path);

        $data['size'] = $this->sizeHandler($data);

        foreach ($data['labels'] as $value) {
            $data['colors'][] = '#' . stringToColor($value);
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function ftpImage()
    {
        $path = request()->query('path');

        $file = Storage::get($path);

        $mimeType = Storage::mimeType($path);

        $file = base64_encode($file);

        if (str_contains($mimeType, 'image/')) {
            return "<img class='w-100-percent h-100-percent' alt='{$mimeType}' src='data:{$mimeType};base64,{$file}' />";
        } elseif (str_contains($mimeType, 'video/')) {
            return "<video autoplay class='w-100-percent h-100-percent' controls><source src='data:{$mimeType};base64,{$file}' type='{$mimeType}'></video>";
        } else if(str_contains($mimeType, 'audio/')) {
            return "<audio autoplay controls> <source src='data:{$mimeType};base64,{$file}' type='{$mimeType}'></audio>";
        }
    }
}
