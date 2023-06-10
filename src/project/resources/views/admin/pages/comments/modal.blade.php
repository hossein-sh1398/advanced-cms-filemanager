<div class="modal w-650-px fade" tabindex="-1" id="modal-detail-comments">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">مشاهده جزئیات دیدگاه</h6>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <label class="form-label">کاربر ارسال کننده : </label>
                        <span>{{ $comment->user->name }}</span>
                    </div>
                    <div>
                        <label class="form-label">تایید شده :</label>
                        <span @can('admin.comments.verify') @if(! $comment->active)  onclick="verifyComment('{{ route('admin.comments.verify', ['comment' => $comment->id]) }}')" @endif @endcan>
                            @include('admin.theme.elements.icons.check', ['status' => $comment->active])
                        </span>
                    </div>
                    <div>
                        <label class="form-label">برسی شده :</label>
                        @include('admin.theme.elements.icons.check', ['status' => $comment->read])
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <label class="form-label">تاریخ ارسال :</label>
                        <span>{{ $comment->created_at }}</span>
                    </div>
                    <div>
                        <label class="form-label">آی پی : </label>
                        <span>{{ $comment->ip }}</span>
                    </div>
                    <div>
                        <label class="form-label"> کشور : </label>
                        <span>{{ $comment->country }}</span>
                    </div>
                </div>
                <div class="mb-5">
                    <label class="form-label">دیدگاه :</label>
                    <p>{{  $comment->content }}</p>
                </div>
                @if ($comment->active)
                    <div class="mb-7">
                        <button class="btn btn-sm btn-success btn-block btn-hover-rise p-2 fs-8 btn-answer-comment mb-3">ارسال پاسخ</button>
                        <form style="display: none;" action="{{ route('admin.comments.store')}}" method="post" id="form-comment-answer">
                            <input type="hidden"  name="commentable_id"  value="{{ $comment->commentable_id }}">
                            <input type="hidden" name="commentable_type"  value="{{ $comment->commentable_type }}">
                            <input type="hidden" name="parent_id"  value="{{ $comment->id }}">
                            <div class="mb-4">
                                <label class="form-label">توضیحات :</label>
                                <textarea id="comment-answer-input" name="content" class="form-control mb-2"  placeholder="متن پاسخ را اینجا وارد نمایید" rows="4" style="resize: none"></textarea>
                            </div>
                            <div style="text-align: left">
                                <button type="submit" class="btn btn-sm btn-primary btn-block btn-hover-rise p-2 fs-8 btn-answer-comment-store mb-3">ذخیره</button>
                            </div>
                        </form>
                    </div>
                @endif
                <div class="replies">
                    @foreach ($comment->replies as $item)
                        <div class="comment">
                            <div class="d-flex justify-content-start align-items-center mb-4">
                                <div class="comment-user-avatar cursor-default"><img src="{{ url($item->user->avatar()) }}"></div>
                                <div class="details">
                                    <p class="cursor-default">{{ $item->user->name }}</p>
                                    <span class="cursor-default">{{ $item->created_at }}</span>
                                </div>
                            </div>
                            <div>
                                <p>{!! $item->content !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>
