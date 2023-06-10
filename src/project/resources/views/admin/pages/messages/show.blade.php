<div class="modal fade" tabindex="-1" id="modal-show-message-details">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">مشاهده جزئیات پیام</h5>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="mb-7">
                    <label class="form-label">نام ارسال کننده : </label><span>{{ $message->name }}</span>
                </div>
                <div class="mb-7">
                    <label class="form-label">ایمیل : </label><span>{{ $message->email }}</span>
                </div>
                <div class="mb-7">
                    <label class="form-label">شماره موبایل : </label><span>{{ $message->mobile }}</span>
                </div>
                <div class="mb-7">
                    <label>خوانده شده :</label>
                    @include('admin.theme.elements.icons.check', ['status' => $message->read])
                </span>
                </div>
                <div class="mb-7">
                    <label class="form-label">دیدگاه :</label>
                    <p class="mt-2">
                        {{nl2br($message->content)}}
                    </p>
                </div>
                <div class="mb-7">
                    <label class="form-label">تاریخ ارسال پیام : </label><span>{{ $message->created_at }}</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>
