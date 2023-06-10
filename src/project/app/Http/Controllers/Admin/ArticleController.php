<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Tag;
use App\Models\Media;
use App\Utility\Table;
use App\Models\Article;
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
use Illuminate\Support\Facades\Storage;
use App\Utility\UploadFile\ArticleImage;
use App\Utility\UploadFile\ArticleVideo;
use App\Http\Requests\Admin\ArticleRequest;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Symfony\Component\HttpFoundation\Response;
use App\Utility\UploadFile\ArticleSpecificImage;

class ArticleController extends Controller
{
    public function __construct($enableAuthorize = true)
    {
        parent::__construct($enableAuthorize);

        $this->table = new Table(['isPaginate' => true, 'isSearch' => true,]);
    }

    /**
     *
     * @return View
     */
    public function index(): View
    {
        $this->pageInfo->title = 'لیست مقالات';

        $this->pageInfo->icon = 'fa fa-book';


        $params = [
            'categories' => ArticleCategory::pluck('title', 'id')->toArray(),
            'table' => $this->table->toArray(),
            'url' => route('admin.articles.ajax'),
            'model' => Str::replace('_', '.', (new Article)->getTable()),
        ];

        return view('admin.pages.articles.index', $params);
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getArticles(Request $request): JsonResponse
    {
        try {
            $articles = $this->allArticles();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'html'=> view('admin.pages.articles.data', compact('articles'))->render(),
                    'links' => view('admin.theme.elements.paginate-link', [
                        'models' => $articles,
                        'table' => $this->table->toArray(),
                    ])->render(),
                ], 201);
            }

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function allArticles($fields = '*')
    {
        $active = request()->input('active', null);

        $special = request()->input('special', null);

        $category = request()->input('category', null);

        $array = [];

        if ($this->table->isFilter) {
            if ($active) {
                $active = $active == 'published' ? 1 : 0;
                $array[] = ['key' => 'active', 'op' => '=', 'value' => $active];
            }
            if ($special) {
                $special = $special == 'special' ? 1 : 0;
                $array[] = ['key' => 'special', 'op' => '=', 'value' => $special];
            }

            if ($category) {
                $array[] = ['key' => 'category_id', 'op' => '=', 'value' => $category];
            }
        }

        return $this->table->get(Article::filters($array), $fields);
    }

    public function export()
    {
        $cols = __('columns.articles');

        $articles = $this->allArticles(array_keys($cols));

        $list = [];
        foreach ($articles as $article) {
            $list[] = [
                'title' => $article->title,
                'content' => $article->content,
                'active' => $article->active ? 'فعال' : 'غیر فعال',
                'special' => $article->special ? 'ویژه' : 'معمولی',
                'category' => $article->category->title,
                'user' => $article->user->name,
                'meta_description' => $article->meta_description,
                'published_at' => $article->published_at,
                'created_at' => $article->created_at,
                'count' => $article->count,
            ];
        }

        $export = new Export();

        if (request()->input('type') === 'excel' ) {
            return $export->excel($list, [$cols]);
        } elseif (request()->input('type') === 'csv') {
            return $export->csv($list, array_values($cols),);
        } else {
            $fileName = 'articles';

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
     * @param Article $article
     * @return View
     */
    public function edit(Article $article): View
    {
        try {
            $this->pageInfo->title = 'ویرایش مقاله';

            $articleCategories = ArticleCategory::latest()->pluck('title', 'id')->toArray();

            $tags = Tag::latest()->pluck('title')->toArray();

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return view('admin.pages.articles.create', compact('article', 'articleCategories', 'tags'));
    }

    /**
     *
     * @param ArticleRequest $request
     * @param Article $article
     * @return RedirectResponse
     */
    public function update(ArticleRequest $request, Article $article): RedirectResponse
    {
        preg_match_all('@src="([^"]+)"@' , $request->get('content'), $match);

        $src = array_pop($match);

        preg_match_all('@src="([^"]+)"@' , $article->content, $match1);

        $src1 = array_pop($match1);

        foreach ($src1 as $s) {
            if (! in_array($s, $src)) {
                FileManager::delete(Str::after($s, 'storage'));
            }
        }

        if ($tags = $request->input('tags')) {
            $tags = Str::replace(['value', '[', ']', ':', '"', '}', '{'], '', $request->get('tags'));

            $request->merge(['tags', $tags]);
        }

        $data = $request->validated();

        $data['active'] = $request->boolean('active');

        $data['special'] = $request->boolean('special');

        try {
            DB::beginTransaction();

            $article->update($data);

            if ($request->hasFile('photo')) {
                foreach($article->specificImage as $image) {
                    FileManager::delete("$image->url/$image->name");

                    $image->delete();
                }

                $fm = new FileManager;

                $fm->uploadWay(new ArticleSpecificImage($request->file('photo')));

                if ($fm->upload()) {
                    $article->images()->createMany($fm->getFileInfo());
                }
            }

            // Start store tag
            if ($tags) {
                $tags = explode(',', $tags);

                $tagsId = [];

                foreach($tags as $title) {
                    $tagsId[]= Tag::firstOrCreate(['title' => $title])->id;
                }

                $article->tags()->sync($tagsId);
            }

            // Upload video
            $videoList = session()->get(Article::SESSION_VIDEO . auth()->id());

            if (is_array($videoList) && count($videoList)) {
                $article->videos()->createMany($videoList);
            }

            // Upload Gallery Images
            $result = session()->get(Article::SESSION_PHOTO . auth()->id());

            if (is_array($result) && count($result)) {
                foreach ($result as $images) {
                    $article->images()->createMany($images);
                }
            }

            DB::commit();

            // Remove Session Video Adn Gallery
            session()->forget(Article::SESSION_VIDEO . auth()->id());
            session()->forget(Article::SESSION_PHOTO . auth()->id());

            session()->flash('success', __('messages.edit-success'));
        } catch (Exception $e) {
            DB::rollBack();

            session()->flash('error', __('messages.error'));
        }

        return redirectAfterSave($request->get('save_type'), $article);
    }

    /**
     *
     * @return View
     */
    public function create(): View
    {
        $this->pageInfo->title = 'ایجاد مقاله';

        $articleCategories = ArticleCategory::latest()->pluck('title', 'id')->toArray();

        $tags = Tag::latest()->pluck('title')->toArray();

        return view('admin.pages.articles.create', compact('articleCategories', 'tags'));
    }

    /**
     *
     * @param ArticleRequest $request
     * @return RedirectResponse
     */
    public function store(ArticleRequest $request): RedirectResponse
    {
        $html = $request->get('content');

        preg_match_all( '@src="([^"]+)"@' , $html, $match );

        $src = array_pop($match);

        $img = cache()->get(Article::SESSION_CKEDITOR. auth()->id(). '-0', []);

        foreach ($img as $s) {
            if (! in_array($s, $src)) {
                FileManager::delete(Str::after($s, 'storage'));
            }
        }

        cache()->forget(Article::SESSION_CKEDITOR. auth()->id(). '-0', []);

        $tags = Str::replace(['value', '[', ']', ':', '"', '}', '{'], '', $request->get('tags'));

        $request->merge(['tags', $tags]);

        $data = $request->validated();

        $data['active'] = $request->boolean('active');

        $data['special'] = $request->boolean('special');

        try {
            DB::beginTransaction();

            $article = auth()->user()->articles()->create($data);

            $fm = new FileManager;

            $fm->uploadWay(new ArticleSpecificImage($request->file('photo')));

            if ($fm->upload()) {
                $article->specificImage()->createMany($fm->getFileInfo());
            }

            if ($tags) {
                $tags = explode(',', $tags);

                $tagsId = [];

                foreach($tags as $title) {
                    $tagsId[]= Tag::firstOrCreate(['title' => $title])->id;
                }

                $article->tags()->sync($tagsId);
            }

            // Store Video
            $videoList = session()->get(Article::SESSION_VIDEO . auth()->id());

            if (is_array($videoList) && count($videoList)) {
                $article->videos()->createMany($videoList);
            }

            // Store Gallery
            $result = session()->get(Article::SESSION_PHOTO . auth()->id());

            if (is_array($result) && count($result)) {
                foreach ($result as $images) {
                    $article->images()->createMany($images);
                }
            }

            DB::commit();

            // Delete Session File
            session()->forget(Article::SESSION_VIDEO . auth()->id());
            session()->forget(Article::SESSION_PHOTO . auth()->id());

            session()->flash('success', __('messages.store-success'));
        } catch (Exception $e) {
            DB::rollBack();

            session()->flash('error', __('messages.error'));
        }

        return redirectAfterSave($request->get('save_type'), $article);
    }

    /**
     * upload video for article
     *
     * @param ArticleRequest $request
     * @return JsonResponse
     */
    public function uploadVideos(ArticleRequest $request): JsonResponse
    {
        try {
            $fm = new FileManager;

            $fm->uploadWay(new ArticleVideo($request->file('video')));

            if ($fm->upload()) {
                $result = session()->get(Article::SESSION_VIDEO . auth()->id());

                $result[] = $fm->getFileInfo();

                session()->put(Article::SESSION_VIDEO . auth()->id(), $result);

                $urls = [];

                foreach ($result as $k => $value) {
                    $urls[] = [
                        'url' => 'storage/' . $value['url'] . '/' . $value['name'],
                        'index' => $k,
                    ];
                }

                return response()->json([
                    'status' => true,
                    'urls' => $urls,
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * upload photo for article
     *
     * @param ArticleRequest $request
     * @return JsonResponse
     */
    public function uploadPhotos(ArticleRequest $request): JsonResponse
    {
        try {
            $fm = new FileManager;

            $fm->uploadWay(new ArticleImage($request->file('photo')));

            if ($fm->upload()) {
                $result = session()->get(Article::SESSION_PHOTO . auth()->id()) ?? [];

                $result[] = $fm->getFileInfo();

                session()->put(Article::SESSION_PHOTO . auth()->id(), $result);

                $urls = [];

                foreach ($result as $k1 => $value1) {
                    foreach ($value1 as $k2 => $value2) {
                        $urls[] = [
                            'url' => 'storage/' . $value2['url'] . '/' . $value2['name'],
                            'index' => [$k1, $k2],
                        ];
                    }
                }

                return response()->json([
                    'status' => true,
                    'urls' => $urls,
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function destroy(Article $article): JsonResponse
    {
        try {
            DB::beginTransaction();

            $this->fullDelete($article);

            DB::commit();

            return response()->json([
                'status' => true,
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Full delete article
     *
     * @param Article $article
     * @return void
     */
    private function fullDelete(Article $article)
    {
        foreach($article->images as $image) {
            FileManager::delete($image->url. '/' . $image->name);

            $image->delete();
        }

        foreach($article->videos as $video) {
            FileManager::delete($video->url. '/' . $video->name);

            $video->delete();
        }

        foreach ($article->specificImage as $specificImage) {
            FileManager::delete($specificImage->url. '/' . $specificImage->name);

            $specificImage->delete();
        }

        $article->delete();

        return true;
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function multipleDestroy(Request $request): JsonResponse
    {
        $this->validate($request, $this->multipleDestroyRoles());

        try {
            $articles = Article::whereIn('id', $request->input('ids'))->get();

            foreach ($articles as $article) {
                DB::beginTransaction();

                $this->fullDelete($article);

                DB::commit();
            }

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'status' => true,
        ]);
    }



    private function multipleDestroyRoles()
    {
        return [
            'ids' => ['array', 'required',],
            'ids.*' => ['exists:articles,id'],
        ];
    }

    public function deleteMedia(Media $media)
    {
        try {
            FileManager::delete($media->url. '/' . $media->name);

            $media->delete();
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'status' => true,
        ]);
    }

    public function ck_upload(Request $request)
    {
        $fm = new FileManager;

        $fm->uploadWay(new ArticleImage($request->file('upload')));

        if ($fm->upload()) {
            $result = $fm->getFileInfo();

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');

            $msg = 'تصویر با موفقیت آپلود شد';

            $url = env('APP_URL') . '/storage/' . $result[0]['url'] . '/' . $result[0]['name'];

            $img = cache()->get(Article::SESSION_CKEDITOR. auth()->id(). '-0', []);

            $img[] = $url;

            cache()->put(Article::SESSION_CKEDITOR. auth()->id(). '-0', $img);

            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url' , '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');

            return $response;
       }
    }

    public function deletePermanentlyGallery(Request $request)
    {
        $index = $request->input('index');

        if (is_array($index) && count($index) == 2) {
            $result = session()->get(Article::SESSION_PHOTO . auth()->id()) ?? [];

            if (isset($result[$index[0]][$index[1]])) {
                $media = $result[$index[0]][$index[1]];

                Storage::delete($media['url']. '/'. $media['name']);

                unset($result[$index[0]][$index[1]]);

                if (! count($result[$index[0]])) {
                    unset($result[$index[0]]);
                }
            }

            session()->put(Article::SESSION_PHOTO . auth()->id(), $result);

            $urls = [];

            foreach ($result as $k1 => $value1) {
                foreach ($value1 as $k2 => $value2) {
                    $urls[] = [
                        'url' => 'storage/' . $value2['url'] . '/' . $value2['name'],
                        'index' => [$k1, $k2],
                    ];
                }
            }

            return response()->json([
                'status' => true,
                'urls' => $urls,
            ]);
        }

        return response()->json([
            'status' => true,
        ]);
    }


    public function deletePermanentlyVideo(Request $request)
    {
        $index = $request->input('index');

        $result = session()->get(Article::SESSION_VIDEO . auth()->id()) ?? [];

        if (isset($result[$index])) {
            $media = $result[$index];

            FileManager::delete($media['url']. '/'. $media['name']);

            unset($result[$index]);
        }

        session()->put(Article::SESSION_VIDEO . auth()->id(), $result);

        $urls = [];

        foreach ($result as $k => $value) {
            $urls[] = [
                'url' => 'storage/' . $value['url'] . '/' . $value['name'],
                'index' => $k,
            ];
        }

        return response()->json([
            'status' => true,
            'urls' => $urls,
        ]);
    }


    public function editStatus(Article $article)
    {
        try {
            $article->active = !$article->active;

            $article->save();
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

    public function editSpecial(Article $article)
    {
        try {
            $article->special = !$article->special;

            $article->save();
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
