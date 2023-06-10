<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Tag;
use App\Utility\Table;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Utility\Export\Export;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\TagRequest;
use App\Http\Repositories\TagRepository;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Routing\ResponseFactory;

class TagController extends Controller
{
    public function __construct($enableAuthorize = true)
    {
        parent::__construct($enableAuthorize);

        $this->table = new Table(['isPaginate' => true, 'isCsv' => true,]);
    }

    /**
     *
     * @return View
     */
    public function index(): View
    {
        $this->pageInfo->title = 'لیست کلمات کلیدی';

        $params = [
            'model' => Str::replace('_', '.', (new Tag())->getTable()),
            'url' => route('admin.tags.ajax'),
            'table' => $this->table->toArray(),
        ];

        return view('admin.pages.tags.index', $params);
    }

    /**
     *
     * get tags
     *
     * @param Request $request
     */
    public function tags(Request $request)
    {
        $this->pageInfo->title = 'لیست کلمات کلیدی';

        try {
            $tags = $this->allTags();
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html'=> view('admin.pages.tags.data', compact('tags'))->render(),
                'links' => view('admin.theme.elements.paginate-link', [
                    'models' => $tags,
                    'table' => $this->table->toArray(),
                    ])->render(),
            ], Response::HTTP_OK);
        }
    }

    private function allTags($fields = '*')
    {
       return $this->table->get(Tag::query(), $fields);
    }

    /**
     *
     * show view edit tag
     *
     * @param Tag $tag
     * @return View
     */
    public function edit(Tag $tag): View
    {
        $this->pageInfo->title = 'ویرایش کلمات کلیدی';

        return view('admin.pages.tags.create', compact('tag'));
    }

    /**
     *
     * update tag
     *
     * @param TagRequest $request
     * @param Tag $tag
     * @return RedirectResponse
     */
    public function update(TagRequest $request, Tag $tag): RedirectResponse
    {
        try {
            TagRepository::update($tag, $request->input('title'));

            session()->flash('success', 'عملیات با موفقیت انجام شد!');
        } catch (Exception $e) {
            session()->flash('error', 'خطا در انجام عملیات، لطفا مجدد تلاش نمایید');
        }

        return redirectAfterSave($request->get('save_type'), $tag);
    }

    /**
     *
     * show create view tag
     *
     * @return View
     */
    public function create() : View
    {
        $this->pageInfo->title = 'ایجاد کلمات کلیدی';

        return view('admin.pages.tags.create');
    }

    /**
     *
     *
     * @param TagRequest $request
     * @return RedirectResponse
     */
    public function store(TagRequest $request): RedirectResponse
    {
        try {
            $tag = TagRepository::create($request->input('title'));

            session()->flash('success', 'عملیات با موفقیت انجام شد!');
        } catch (Exception $e) {
            session()->flash('error', 'خطا در انجام عملیات، لطفا مجدد تلاش نمایید');
        }

       return redirectAfterSave($request->get('save_type'), $tag);
    }

    /**
     *
     * destroy tag
     *
     * @param Tag $tag
     * @return JsonResponse|ResponseFactory
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $tag->load('articles');

        try {
            TagRepository::delete($tag);

            return response()->json([
                'status' => true,
                'message' => 'کلمه کلیدی با موفقیت حذف شد'
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
     * delete multiple selected tag
     *
     * @param TagRequest $request
     * @return JsonResponse|ResponseFactory
     */
    public function multipleDestroy(TagRequest $request): JsonResponse
    {
        try {
            TagRepository::multipleDestroy($request->input('ids'));
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'status' => true,
        ]);
    }

    public function export()
    {
        $cols = __('columns.tags');

        $tags = $this->allTags(array_keys($cols));

        $list = [];
        foreach ($tags as $tag) {
            $list[] = [
                'title' => $tag->title,
                'created_at' => $tag->created_at,
            ];
        }

        $export = new Export();

        if (request()->input('type') === 'excel' ) {
            return $export->excel($list, [$cols]);
        } elseif (request()->input('type') === 'csv') {
            return $export->csv($list, array_values($cols));
        } else {
            $fileName = 'tags';

            $pdf = PDF::loadView('admin.theme.elements.pdf', [
                'data' => $list,
                'thead' => array_values($cols),
            ], [], [
                'format' => 'A4-L'
            ]);

            return $pdf->download("$fileName.pdf");
        }
    }
}
