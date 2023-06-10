<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Utility\Table;
use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Utility\Export\Export;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Http\Repositories\CommentRepository;
use App\Http\Requests\Admin\CommentRequest;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Routing\ResponseFactory;

class CommentController extends Controller
{
    public function __construct($enableAuthorize = true)
    {
        parent::__construct($enableAuthorize);

        $this->table = new Table();
    }
    /**
     *
     * @return View
     */
    public function index(): View
    {
        $this->pageInfo->title = 'لیست نظرات';

        $this->pageInfo->icon = 'fas fa-comments';

        $params = [
            'url' => route('admin.comments.ajax'),
            'model' => Str::replace('_', '.', (new Comment())->getTable()),
            'table' => $this->table->toArray(),
        ];

        return view('admin.pages.comments.index', $params);
    }

    /**
     *
     * get comments
     *
     * @param Request $request
     */
    public function comments(Request $request)
    {
        try {
            $comments = $this->allComments();
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
                'html'=> view('admin.pages.comments.data', compact('comments'))->render(),
                'links' => view('admin.theme.elements.paginate-link', [
                    'models' => $comments,
                    'table' => $this->table->toArray(),
                ])->render(),
            ], Response::HTTP_OK);
        }
    }

    public function allComments($fields = '*')
    {
        $active = request()->input('active', null);

        $read = request()->input('read', null);

        $array = [];

        if ($this->table->isFilter) {
            if ($active) {
                $active = $active === 'verified' ? true : false;
                $array[] = ['key' => 'active', 'op' => '=', 'value' => $active ];
            }
            if ($read) {
                $read = $read === 'reviewed' ? true : false;
                $array[] = ['key' => 'read', 'op' => '=', 'value' => $read ];
            }
        }

        return $this->table->get(Comment::where('parent_id', 0)->filters($array), $fields);
    }

    public function export()
    {
        $cols = __('columns.comments');

        $comments = $this->allComments(array_keys($cols));

        $list = [];
        foreach ($comments as $comment) {
            $list[] = [
                'active' => $comment->active ? 'تایید شده' : 'تایید نشده',
                'read' => $comment->read ? 'بله' : 'خیر',
                'user' => $comment->user->name,
                'content' => $comment->content,
                'ip' => $comment->id,
                'country' => $comment->country,
                'created_at' => $comment->created_at,
            ];
        }

        $export = new Export();

        if (request()->input('type') === 'excel' ) {
            return $export->excel($list, [$cols]);
        } elseif (request()->input('type') === 'csv') {
            return $export->csv($list, array_values($cols),);
        } else {
            $fileName = 'comments';

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
     * @param CommentRequest $request
     * @param Comment $comment
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(CommentRequest $request)
    {
        $data = $request->validated();

        try {
            if (class_exists($data['commentable_type'])) {
                $model = $data['commentable_type']::find($data['commentable_id']);

                if ($model) {
                    $comment = $model->comments()->create([
                        'content' => $data['content'],
                        'active' => true,
                        'read' => true,
                        'user_id' => auth()->id(),
                        'ip' => $request->ip(),
                        'parent_id' => $data['parent_id'],
                    ]);

                    $answerHtml = '<div class="comment">
                            <div class="d-flex justify-content-start align-items-center mb-4">
                                <div class="comment-user-avatar cursor-default"><img src="' . url($comment->user->avatar()) . '"></div>
                                <div class="details">
                                    <p class="cursor-default">' . $comment->user->name . '</p>
                                    <span class="cursor-default"> ' . $comment->created_at . '</span>
                                </div>
                            </div>
                            <div>
                                <p>' . $comment->content . '</p>
                            </div>
                        </div>';

                    return response()->json([
                        'status' => true,
                        'html' => $answerHtml,
                        'message' => 'ثبت دیدگاه ا نجام شد'
                    ]);
                }
            }

            throw new Exception('خطا به دلیل ارسال اطلاعات نادرست');
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     *
     * destroy comment
     *
     * @param Comment $comment
     * @return JsonResponse|ResponseFactory
     */
    public function destroy(Comment $comment): JsonResponse
    {
        try {
            CommentRepository::delete($comment);

            return response()->json([
                'status' => true,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    public function show(Request $request, Comment $comment)
    {
        if (! $comment->read) {
            $comment->read = true;

            $comment->save();
        }

        if ($request->ajax()) {
            return response()->json([
                    'success' => true,
                    'html'=> view('admin.pages.comments.modal', compact('comment'))->render()
                ], Response::HTTP_OK);
            }
            try {
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }

    /**
     *
     * delete multiple selected comment
     *
     * @param Request $request
     * @return JsonResponse|ResponseFactory
     */
    public function multipleDestroy(Request $request): JsonResponse
    {
        try {
            $comments = Comment::whereIn('id', $request->input('ids', []))->get();

            foreach ($comments as $comment) {
                CommentRepository::delete($comment);
            }

            return response()->json([
                'status' => true,
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
     * destroy comment
     *
     * @param Comment $comment
     * @return JsonResponse|ResponseFactory
     */
    public function verify(Comment $comment): JsonResponse
    {
        try {
            $comment->active = true;

            $comment->save();

            return response()->json([
                'status' => true,
                'message' => 'ویرایش با موفقیت انجام شد'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => false,
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function editStatus(Comment $comment): JsonResponse
    {
        try {
            $comment->read = true;

            $comment->save();

            return response()->json([
                'status' => true,
                'message' => 'ویرایش با موفقیت انجام شد'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => false,
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
