<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Utility\Table;
use App\Models\Message;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Utility\Export\Export;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\MessageRequest;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
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
        $this->pageInfo->title = 'لیست پیام ها';

        $this->pageInfo->icon = 'fas fa-comments';

        $params = [
            'url' => route('admin.messages.ajax'),
            'model' => Str::replace('_', '.', (new Message())->getTable()),
            'table' => $this->table->toArray(),
        ];

        return view('admin.pages.messages.index', $params);
    }

    /**
     *
     * get comments
     *
     * @param Request $request
     */
    public function messages(Request $request)
    {
        try {
            $messages = $this->allMessages();
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
                'html'=> view('admin.pages.messages.data', compact('messages'))->render(),
                'links' => view('admin.theme.elements.paginate-link', [
                    'models' => $messages,
                    'table' => $this->table->toArray(),
                    ])->render(),
            ], Response::HTTP_OK);
        }
    }

    public function allMessages($fields = '*')
    {
        $read = request()->input('read', null);

        $softDelete = request()->input('soft_delete');

        $array = [];

        if ($this->table->isFilter) {
            if ($read) {
                $boolean = $read === 'read' ? true : false;
                $array[] = ['key' => 'read', 'op' => '=', 'value' => $boolean];
            }

            if ($softDelete) {
                if ($softDelete === 'withTrashed') {
                    return $this->table->get(Message::withTrashed()->filters($array), $fields);
                } else {
                    return $this->table->get(Message::onlyTrashed()->filters($array), $fields);
                }
            }
        }

        return $this->table->get(Message::filters($array), $fields);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function create()
    {
        $this->pageInfo->title = 'ارسال پیام جدید';

        return view('admin.pages.messages.create');
    }

    /**
     * @param MessageRequest $request
     * @return RedirectResponse
     */
    public function store(MessageRequest $request): RedirectResponse
    {
        try {
            $message = Message::create($request->all());

            session()->flash('success', 'پیام شما با موفقیت ارسال شد');
        } catch (Exception) {
            session()->flash('error', 'خطا در ارسال پیام، لطفا مجدد تلاش نمایید');
        }

        return redirectAfterSave($request->get('save_type'), $message);
    }

    /**
     * @param Message $message
     * @return JsonResponse
     */
    public function destroy(Message $message): JsonResponse
    {
        try {
            $message->delete();

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
     * @param MessageRequest $request
     * @return JsonResponse
     */
    public function multipleDestroy(MessageRequest $request): JsonResponse
    {
        try {
            Message::whereIn('id', $request->input('ids'))->delete();

            return response()->json([
                'status' => true,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    public function export()
    {
        $cols = __('columns.messages');

        $messages = $this->allMessages(array_keys($cols));

        $list = [];
        foreach ($messages as $message) {
            $list[] = [
                'name' => $message->name,
                'email' => $message->email,
                'mobile' => $message->mobile,
                'content' => $message->content,
                'ip' => $message->ip,
                'country' => $message->country,
                'read' => $message->read ? 'خوانده شده' : 'خوانده نشده',
                'created_at' => $message->created_at,
            ];
        }

        $export = new Export();

        if (request()->input('type') === 'excel' ) {
            return $export->excel($list, [$cols]);
        } elseif (request()->input('type') === 'csv') {
            return $export->csv($list, array_values($cols),);
        } else {
            $fileName = 'پیام ها';

            $pdf = PDF::loadView('admin.theme.elements.pdf', [
                'data' => $list,
                'thead' => array_values($cols),
            ], [], [
                'format' => 'A4-L'
            ]);

            return $pdf->download("$fileName.pdf");
        }
    }

    public function show(Message $message)
    {
        try {
            $message->read = true;

            $message->save();

            return response()->json([
                'status' => true,
                'html'=> view('admin.pages.messages.show', compact('message'))->render(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function editStatus(Message $message)
    {
        try {
            $message->read = true;

            $message->save();

            return response()->json([
                'status' => true,
                'message' => 'ویرایش با موفیت انجام شد'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param Message $message
     * @return JsonResponse
     */
    public function restore($id): JsonResponse
    {
        try {
            $message = Message::withTrashed()->find($id);
            $message->restore();

            return response()->json([
                'status' => true,
                'message' => 'بازیابی انجام شد'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
