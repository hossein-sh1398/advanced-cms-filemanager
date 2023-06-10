<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Utility\Table;
use App\Models\History;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Utility\Export\Export;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Routing\ResponseFactory;

class HistoryController extends Controller
{
    public function __construct($enableAuthorize = true)
    {
        parent::__construct($enableAuthorize);

        $this->pageInfo->title = 'لیست رفتارهای کاربر با سایت';

        $this->table = new Table(['isPaginate' => true, 'isCsv' => true,]);
    }

    /**
     *
     * @return View
     */
    public function index(): View
    {
        $params = [
            'url' => route('admin.histories.ajax'),
            'model' => Str::replace('_', '.', (new History())->getTable()),
            'table' => $this->table->toArray(),
        ];

        return view('admin.pages.histories.index', $params);
    }

    /**
     *
     * get histories
     *
     * @param Request $request
     */
    public function histories(Request $request)
    {
        try {
            $histories = $this->allHistory();
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false], Response::HTTP_BAD_REQUEST);
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html'=> view('admin.pages.histories.rows', compact('histories'))->render(),
                'links' => view('admin.theme.elements.paginate-link', [
                    'models' => $histories,
                    'table' => $this->table->toArray(),
                    ])->render(),
            ], Response::HTTP_OK);
        }
    }

    public function allHistory($fields = '*')
    {
        return $this->table->get(History::query(), $fields);
    }

    /**
     *
     * destroy report
     *
     * @param History $history
     * @return JsonResponse|ResponseFactory
     */
    public function destroy(History $history): JsonResponse
    {
        try {
            $history->delete();

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
     *
     * delete multiple selected history
     *
     * @param Request $request
     * @return JsonResponse|ResponseFactory
     */
    public function multipleDestroy(Request $request): JsonResponse
    {
        $this->validate($request, $this->rules());

        try {
            History::whereIn('id', $request->input('ids'))->delete();

        } catch (Exception $e) {
            return response()->json(['status' => false,], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['status' => true]);
    }

    private function rules()
    {
        return [
            'ids' => ['array', 'required',],
            'ids.*' => ['exists:histories,id'],
        ];
    }

    public function export()
    {
        $cols = __('columns.histories');

        $histories = $this->allHistory(array_keys($cols));

        $list = [];
        foreach ($histories as $history) {
            $list[] = [
                'user' => $history->user->name,
                'action' => $history->action,
                'created_at' => $history->created_at,
            ];
        }

        $export = new Export();

        if (request()->input('type') === 'excel' ) {
            return $export->excel($list, [$cols]);
        } elseif (request()->input('type') === 'csv') {
            return $export->csv($list, array_values($cols),);
        } else {
            $fileName = 'histories';

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
