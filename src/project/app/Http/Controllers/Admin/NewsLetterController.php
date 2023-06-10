<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Utility\Table;
use App\Models\NewsLetter;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Utility\Export\Export;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Events\RegisteredNewsLettersEvent;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use App\Http\Requests\Admin\NewsLetterRequest;
use Symfony\Component\HttpFoundation\Response;

class NewsLetterController extends Controller
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
        $this->pageInfo->title = 'لیست اشتراک خبرنامه';

        $params = [
            'url' => route('admin.news.letters.ajax'),
            'model' => Str::replace('_', '.', (new NewsLetter())->getTable()),
            'table' => $this->table->toArray(),
        ];

        return view('admin.pages.news-letters.index', $params);
    }

    /**
     *
     * get comments
     *
     * @param Request $request
     */
    public function newsLetters(Request $request)
    {
        try {
            $newsLetters = $this->allNewsLetters();
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html'=> view('admin.pages.news-letters.data', compact('newsLetters'))->render(),
                'links' => view('admin.theme.elements.paginate-link', [
                    'models' => $newsLetters,
                    'table' => $this->table->toArray(),
                    ])->render(),
            ], Response::HTTP_OK);
        }
    }

    public function allNewsLetters($fields = '*')
    {
        return $this->table->get(NewsLetter::query(), $fields);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function create()
    {
        $this->pageInfo->title = 'عضویت در خبرنامه';

        return view('admin.pages.news-letters.create');
    }

    /**
     * @param NewsLetterRequest $request
     * @return RedirectResponse
     */
    public function store(NewsLetterRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            $data['ip'] = $request->ip();

            $newsLetter = NewsLetter::create($data);

            event(new RegisteredNewsLettersEvent($newsLetter));

            session()->flash('success', __('messages.newsLetters.subscribe'));
        } catch (Exception) {
            session()->flash('error', __('messages.error'));
        }

        return redirectAfterSave($request->get('save_type'), $newsLetter);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function edit(NewsLetter $newsLetter)
    {
        $this->pageInfo->title = 'ویرایش خبرنامه';

        return view('admin.pages.news-letters.create', compact('newsLetter'));
    }

    /**
     * @param NewsLetterRequest $request
     * @return RedirectResponse
     */
    public function update(NewsLetterRequest $request, NewsLetter $newsLetter): RedirectResponse
    {
        try {
            $newsLetter->update($request->validated());

            session()->flash('success', __('messages.edit-success'));
        } catch (Exception) {
            session()->flash('error', __('messages.error'));
        }

        return redirectAfterSave($request->get('save_type'), $newsLetter);
    }

    /**
     * @param NewsLetter $newsLetter
     * @return JsonResponse
     */
    public function destroy(NewsLetter $newsLetter): JsonResponse
    {
        try {
            $newsLetter->delete();

            return response()->json([
                'status' => true,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param NewsLetterRequest $request
     * @return JsonResponse
     */
    public function multipleDestroy(NewsLetterRequest $request): JsonResponse
    {
        try {
            NewsLetter::whereIn('id', $request->input('ids'))->delete();

            return response()->json(['status' => true]);

        } catch (Exception $e) {
            return response()->json(['status' => false], Response::HTTP_BAD_REQUEST);
        }
    }

    public function export()
    {
        $cols = __('columns.newsLetters');

        $newsLetters = $this->allNewsLetters(array_keys($cols));

        $list = [];
        foreach ($newsLetters as $newsLetter) {
            $list[] = [
                'email' => $newsLetter->email,
                'mobile' => $newsLetter->mobile,
                'ip' => $newsLetter->ip,
                'country' => $newsLetter->country,
                'active_email' => $newsLetter->active_email ? 'بله' : 'خیر',
                'active_mobile' => $newsLetter->active_mobile ? 'بله' : 'خیر',
                'created_at' => $newsLetter->created_at,
            ];
        }

        $export = new Export();

        if (request()->input('type') === 'excel' ) {
            return $export->excel($list, [$cols]);
        } elseif (request()->input('type') === 'csv') {
            return $export->csv($list, array_values($cols),);
        } else {
            $fileName = 'خبرنامه';

            $pdf = PDF::loadView('admin.theme.elements.pdf', [
                'data' => $list,
                'thead' => array_values($cols),
            ], [], [
                'format' => 'A4-L'
            ]);

            return $pdf->download("$fileName.pdf");
        }
    }

    public function verifyEmail(NewsLetter $newsLetter)
    {
        try {
            $newsLetter->active_email = true;

            $newsLetter->save();

            return response()->json([
                'status' => true,
                'message' => 'ایمیل با موفیت تایید شد'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function verifyMobile(NewsLetter $newsLetter)
    {
        try {
            $newsLetter->active_mobile = true;

            $newsLetter->save();

            return response()->json([
                'status' => true,
                'message' => 'شماره موبایل با موفیت تایید شد'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
