<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Report;
use App\Utility\Table;
use App\Enums\ReportType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Utility\Export\Export;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Admin\ReportRequest;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Routing\ResponseFactory;

class ReportController extends Controller
{
    public function __construct($enableAuthorize = true)
    {
        parent::__construct($enableAuthorize);

        $this->table = new Table(['isSearch' => false]);
    }

    /**
     *
     * @return View
     */
    public function index(): View
    {
        $this->pageInfo->title = 'لیست گزارش ها';

        $params = [
            'url' => route('admin.reports.ajax'),
            'model' => Str::replace('_', '.', (new Report())->getTable()),
            'table' => $this->table->toArray(),
        ];

        return view('admin.pages.reports.index', $params);
    }

    /**
     *
     * get reports
     *
     * @param Request $request
     */
    public function reports(Request $request)
    {
        try {
            $reports = $this->allReport();
        } catch (Exception $e) {
            return response()->json(['success' => false], Response::HTTP_BAD_REQUEST);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html'=> view('admin.pages.reports.rows', compact('reports'))->render(),
                'links' => view('admin.theme.elements.paginate-link', [
                    'table' => $this->table->toArray(),
                    'models' => $reports
                    ])->render(),
            ], Response::HTTP_OK);
        }
    }

    public function allReport($fields = '*')
    {
        $array = [];

        if ($type = request()->query('report_type')) {
            if ($type == 'email') {
                $type = ReportType::Email;
            } elseif ($type == 'sms') {
                $type = ReportType::Mobile;
            }

            $array = [['key' => 'type', 'op' => '=', 'value' => $type]];
        }

        return $this->table->get(Report::filters($array), $fields);
    }


    /**
     *
     * destroy report
     *
     * @param Report $report
     * @return JsonResponse|ResponseFactory
     */
    public function destroy(Report $report): JsonResponse
    {
        try {
            $report->delete();

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
     * delete multiple selected report
     *
     * @param ReportRequest $request
     * @return JsonResponse|ResponseFactory
     */
    public function multipleDestroy(ReportRequest $request): JsonResponse
    {
        try {
            Report::whereIn('id', $request->input('ids'))->delete();

        } catch (Exception $e) {
            return response()->json(['status' => false,], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['status' => true]);
    }


    public function export()
    {
        $cols = __('columns.reports');

        $reports = $this->allReport(array_keys($cols));

        $cols['content'] = 'محتوا';
        $cols['moreData'] = 'ارسال شده با';
        unset($cols['reportable_type']);

        $list = [];
        foreach ($reports as $report) {
            $list[] = [
                'type' => ReportType::getTitle($report->type),
                'contact' => $report->type == ReportType::Email ? $report->moreData['email'] :  $report->moreData['mobile'] ,
                'delivery' => $report->delivery,
                'ricId' => $report->ricId,
                'created_at' => $report->created_at,
                'reportable_id' =>  $report->reportable->name ,
                'content' =>  $report->moreData['content'] ,
            ];
        }

        $export = new Export();

        if (request()->input('type') === 'excel' ) {
            return $export->excel($list, [$cols]);
        } elseif (request()->input('type') === 'csv') {
            return $export->csv($list, array_values($cols),);
        } else {
            $fileName = 'مدیریت گزارشات';

            $pdf = PDF::loadView('admin.theme.elements.pdf', [
                'data' => $list,
                'thead' => array_values($cols),
            ], [], [
                'format' => 'A4-L'
            ]);

            return $pdf->download("$fileName.pdf");
        }
    }

    public function show(Report $report)
    {
        return response()->json([
            'html'=> view('admin.pages.reports.modal-details', compact('report'))->render(),
        ]);
    }

    public function logViewer($type = false)
    {
        if (!isLogs()) {
            session()->flash('error', 'هیچ خطایی برای نمایش وجود ندارد');
            return redirect(route('admin.index'));
        }

        if ($type === 'clear'){
            $files = glob(storage_path('logs/*'));
            foreach ($files as $file){
                File::delete($file);
            }
            session()->flash('success', 'تمامی خطاها حذف گردید');
            return redirect(route('admin.index'));
        }
        return view('admin.pages.logs.index');
    }
}
