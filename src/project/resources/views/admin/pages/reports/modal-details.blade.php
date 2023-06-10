<div class="modal fade" tabindex="-1" id="modal-report-details">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">مشاهده جزئیات</h5>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="mb-6">
                    <label>مدل : </label><span>{{ basename($report->reportable_type) }}</span>
                </div>
                <div class="mb-6">
                    <label>گیرنده : </label><span>{{ $report->reportable->name }}</span>
                </div>
                @if ($report->type == \App\Enums\ReportType::Mobile)
                    <div class="mb-6">
                        <label>کد تحویل: </label><span>{{ $report->delivery }}</span>
                    </div>
                    <div class="mb-6">
                        <label>کد پیگیری :  </label><span>{{ $report->ricID }}</span>
                    </div>
                @endif
                <div class="mb-6">
                    <label>محتوا : </label><span>{{ $report->moreData['content'] }}</span>
                </div>
                <div class="mb-6">
                    <label> ارسال شده با: </label>
                    <span>{{ $report->type == \App\Enums\ReportType::Mobile ? $report->moreData['mobile'] : $report->moreData['email'] }}</span>
                </div>
                <div class="mb-6">
                    <label>تاریخ ارسال: </label><span>{{ $report->created_at }}</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>
