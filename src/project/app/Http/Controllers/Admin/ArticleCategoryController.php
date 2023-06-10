<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Config;
use App\Utility\Table;
use Illuminate\Support\Str;
use App\Utility\FileManager;
use Illuminate\Http\Request;
use App\Utility\Export\Export;
use App\Models\ArticleCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Symfony\Component\HttpFoundation\Response;
use App\Utility\UploadFile\ArticleCategoryImage;
use App\Http\Requests\Admin\ArticleCategoryRequest;

class ArticleCategoryController extends Controller
{
    private  $defaultImageUrl;

    public function __construct($enableAuthorize = true)
    {
        parent::__construct($enableAuthorize);

        $this->defaultImageUrl = Config::where('key', 'image_article_category')->first()->value;

        $this->table = new Table(['isPaginate' => true,]);
    }

    /**
     *
     * @return View
     */
    public function index(): View
    {
        $this->pageInfo->title = 'لیست دسته بندی مقالات';

        $table = $this->table->toArray();

        $url = route('admin.article.categories.ajax');
        $model = Str::replace('_', '.', (new ArticleCategory)->getTable());
        $table = $this->table->toArray();

        return view('admin.pages.article-categories.index', compact('table', 'url', 'model'));
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getArticleCategories(Request $request): JsonResponse
    {
        try {
            $articleCategories = $this->allCategories();

            $defaultImageUrl = $this->defaultImageUrl;
        } catch (Exception $e) {

        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html'=> view('admin.pages.article-categories.data', compact('articleCategories', 'defaultImageUrl'))->render(),
                'links' => view('admin.theme.elements.paginate-link', [
                    'models' => $articleCategories,
                    'table' => $this->table->toArray(),
                ])->render(),
            ], 201);
        }
    }

    public function allCategories($fields = '*')
    {
        return $this->table->get(ArticleCategory::query(), $fields);
    }



    public function export()
    {
        $cols = __('columns.article-categories');

        $categories = $this->allCategories(array_keys($cols));

        $list = [];
        foreach ($categories as $category) {
            $list[] = [
                'title' => $category->title,
                'comment' => $category->comment ? "باز" : 'بسته',
                'parent' => $category->parent->title ?? '',
                'created_at' => $category->created_at,
            ];
        }

        $export = new Export();

        if (request()->input('type') === 'excel' ) {
            return $export->excel($list, [$cols]);
        } elseif (request()->input('type') === 'csv') {
            return $export->csv($list, array_values($cols),);
        } else {
            $fileName = 'article-categories';

            $pdf = PDF::loadView('admin.theme.elements.pdf', [
                'data' => $list,
                'thead' => array_values($cols),
            ], [], [
                'format' => 'A4-L'
            ]);

            return $pdf->download("$fileName.pdf");
        }
    }

    /**
     *
     * @param EditArticleCategoryRequest $request
     * @param ArticleCategory $articleCategory
     * @return RedirectResponse
     */
    public function update(ArticleCategoryRequest $request, ArticleCategory $articleCategory): RedirectResponse
    {
        $data = $request->validated();

        $data['comment'] = $request->boolean('comment');

        try {
            DB::beginTransaction();

            $articleCategory->update($data);

            if ($request->hasFile('photo')) {

                $fileManager = new FileManager;

                $fileManager->uploadWay(new ArticleCategoryImage($request->file('photo')));

                if ($fileManager->upload()) {
                    foreach($articleCategory->images as $image) {
                        FileManager::delete($image->url . '/' . $image->name);

                        $image->delete();
                    }

                    $articleCategory->images()->create($fileManager->getFileInfo());
                }
            }

            DB::commit();

            session()->flash('success', __('messages.edit-success'));

        } catch (Exception $e) {
            DB::rollBack();

            session()->flash('error', __('messages.error'));
        }

        return redirectAfterSave($request->get('save_type'), $articleCategory);
    }

    /**
     *
     * @return View
     */
    public function create(): View
    {
        $this->pageInfo->title = 'ایجاد دسته بندی مقالات';

        $articleCategories = ArticleCategory::latest()->pluck('title', 'id')->toArray();

        $defaultImageUrl = $this->defaultImageUrl;

        return view('admin.pages.article-categories.create', compact('articleCategories', 'defaultImageUrl'));
    }

    /**
     *
     * @return View
     */
    public function edit(ArticleCategory $articleCategory): View
    {
        $this->pageInfo->title = 'ویرایش دسته بندی مقالات';

        $articleCategories = ArticleCategory::latest()->pluck('title', 'id')->toArray();

        $defaultImageUrl = $this->defaultImageUrl;

        return view('admin.pages.article-categories.create', compact('articleCategories', 'articleCategory', 'defaultImageUrl'));
    }

    /**
     *
     * @param ArticleCategoryRequest $request
     * @return RedirectResponse
     */
    public function store(ArticleCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['comment'] = $request->boolean('comment');

        try {
            DB::beginTransaction();

            $category = ArticleCategory::create($data);

            if ($request->hasFile('photo')) {

                $fm = new FileManager;

                $fm->uploadWay(new ArticleCategoryImage($request->file('photo')));

                if ($fm->upload()) {
                    $category->images()->create($fm->getFileInfo());
                }
            }

            DB::commit();

            session()->flash('success', __('messages.store-success'));

            return redirectAfterSave($request->get('save_type'), $category);

        } catch (Exception $e) {
            DB::rollBack();

            session()->flash('error', __('messages.error'));
        }
    }

    /**
     *
     * @param ArticleCategory $articleCategory
     * @return JsonResponse
     */
    public function destroy(ArticleCategory $articleCategory): JsonResponse
    {
        try {
            DB::beginTransaction();

            if ($articleCategory->articles->isEmpty() || $articleCategory->childs->isEmpty()) {
                throw new Exception('به دلیل داشتن وابستگی امکان حذف دسته نیست');
            }

            $articleCategory->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'دسته با موفقیت حذف شد'
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function multipleDestroy(Request $request): JsonResponse
    {
        $request->validate($this->multipleDestroyRoles());

        try {
            $categories = ArticleCategory::with('articles')->whereIn('id', $request->input('ids'))->get();

            DB::beginTransaction();

            foreach ($categories as $category) {
                if ($category->articles->isEmpty() || $category->childs->isEmpty()) {
                    throw new Exception('به دلیل داشتن وابستگی امکان حذف دسته نیست');
                }

                $category->delete();
            }

            DB::commit();

            return response()->json(['status' => true]);
          } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    private function multipleDestroyRoles()
    {
        return [
            'ids' => ['array', 'required'],
            'ids.*' => ['required', 'exists:article_categories,id'],
        ];
    }

    public function editStatus(ArticleCategory $articleCategory)
    {
        try {
            $articleCategory->comment = !$articleCategory->comment;

            $articleCategory->save();
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'status' => true,
            'message' => 'ویرایش با موفقیت انجام شد'
        ]);
    }
}
