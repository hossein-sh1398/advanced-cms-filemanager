@forelse($articles as $key => $article)
    <tr>
        <td>
            <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input class="form-check-input form-check-input-single widget-9-check w-15px h-15px" type="checkbox" value="{{ $article->id }}"/>
            </div>
        </td>
        <td>{{ ($loop->index + 1) }}</td>
        <td class="align-left text-start">{{ $article->title }}</td>
        <td>
            <span @can('admin.articles.status') onclick="statusArticle('{{ route('admin.articles.status', ['article' => $article->id]) }}')" @endcan >
                @include('admin.theme.elements.icons.check', ['status' => $article->active ])
            </span>
        </td>
        <td>
            <span @can ('admin.articles.special') onclick="editSpecialArticle('{{ route('admin.articles.special', ['article' => $article->id]) }}')" @endcan  class="btn btn-icon {{ $article->special ? 'btn-color-warning' : 'btn-color-gray' }} btn-sm">
                <span class="svg-icon svg-icon-1">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.1359 4.48359C11.5216 3.82132 12.4784 3.82132 12.8641 4.48359L15.011 8.16962C15.1523 8.41222 15.3891 8.58425 15.6635 8.64367L19.8326 9.54646C20.5816 9.70867 20.8773 10.6186 20.3666 11.1901L17.5244 14.371C17.3374 14.5803 17.2469 14.8587 17.2752 15.138L17.7049 19.382C17.7821 20.1445 17.0081 20.7069 16.3067 20.3978L12.4032 18.6777C12.1463 18.5645 11.8537 18.5645 11.5968 18.6777L7.69326 20.3978C6.99192 20.7069 6.21789 20.1445 6.2951 19.382L6.7248 15.138C6.75308 14.8587 6.66264 14.5803 6.47558 14.371L3.63339 11.1901C3.12273 10.6186 3.41838 9.70867 4.16744 9.54646L8.3365 8.64367C8.61089 8.58425 8.84767 8.41222 8.98897 8.16962L11.1359 4.48359Z" fill="currentColor"/>
                    </svg>
                </span>
            </span>
        </td>
        <td>{{ $article->category->title }}</td>
        <td>{{ $article->count ?? '0' }}</td>
        <td>{{ $article->created_at }}</td>
        <td>
            <button onclick="$('#modal-details-{{ $article->id }}').modal('show')" class="btn btn-sm btn-light-facebook btn-block p-2 fs-8">نمایش جزئیات</button>
            <div class="modal w-800-px fade" tabindex="-1" id="modal-details-{{ $article->id }}">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">مشاهده جزئیات مقاله</h5>

                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                <span class="svg-icon svg-icon-2x"></span>
                            </div>
                            <!--end::Close-->
                        </div>

                        <div class="modal-body">
                            <div class="mb-10">
                                <label>عنوان : </label><span>{{ $article->title }}</span>
                            </div>
                            <div class="mb-10">
                                <label>تعداد بازدید : </label><span>{{ $article->count }}</span>
                            </div>
                            <div class="mb-10">
                                <label>دسته بندی : </label><span>{{ $article->category->title }}</span>
                            </div>
                            <div class="mb-10">
                                <label>تصویر شاخص : </label>
                                @if ($article->specificImage->count())
                                    <div class="article-specific-image">
                                        <a href="{{url($article->specificImage->first()->getUrl()) }}" target="_blank">
                                            <img class="w-200-px h-200-px" src="{{url($article->specificImage->first()->getUrl()) }}" alt="{{ $article->specificImage->first()->name }}">
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-10">
                                <label class="mb-5">توضیحات کوتاه : </label><div class="mb-10">{{  $article->meta_description  }}</div>
                            </div>

                            <div class="mb-10">
                                <label>برچسب ها : </label>
                                <div class="mb-10">
                                    @foreach($article->tags as $tag)
                                        <span class="badge badge-warning ml-2 mb-2">{{ $tag->title }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="mb-10">
                                <label>گالری : </label>
                                <div class="row mt-7">
                                    @foreach($article->images as $image)
                                        <div class="col-md-3 mb-3">
                                            <div class="article-media">
                                                <a href="{{ url($image->getUrl()) }}" target="_blank">
                                                    <img src="{{ url($image->getUrl()) }}" alt="{{ $image->name }}">
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="mb-10">
                                <label>ویدیو : </label>
                                <div class="row mt-7">
                                    @foreach($article->videos as $video)
                                        <div class="col-4 mb-3">
                                            <div class="article-modal">
                                                <video controls class="w-100-percent h-100-percent">
                                                    <source src="{{ url($video->getUrl()) }}" type="video/{{ $video->mime_type }}">
                                                </video>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="mb-10">
                                <label>ویژه : </label>
                                <span class="btn btn-icon {{ $article->special ? 'btn-color-warning' : 'btn-color-gray' }} btn-sm">
                                    <span class="svg-icon svg-icon-1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11.1359 4.48359C11.5216 3.82132 12.4784 3.82132 12.8641 4.48359L15.011 8.16962C15.1523 8.41222 15.3891 8.58425 15.6635 8.64367L19.8326 9.54646C20.5816 9.70867 20.8773 10.6186 20.3666 11.1901L17.5244 14.371C17.3374 14.5803 17.2469 14.8587 17.2752 15.138L17.7049 19.382C17.7821 20.1445 17.0081 20.7069 16.3067 20.3978L12.4032 18.6777C12.1463 18.5645 11.8537 18.5645 11.5968 18.6777L7.69326 20.3978C6.99192 20.7069 6.21789 20.1445 6.2951 19.382L6.7248 15.138C6.75308 14.8587 6.66264 14.5803 6.47558 14.371L3.63339 11.1901C3.12273 10.6186 3.41838 9.70867 4.16744 9.54646L8.3365 8.64367C8.61089 8.58425 8.84767 8.41222 8.98897 8.16962L11.1359 4.48359Z" fill="currentColor"/>
                                        </svg>
                                    </span>
                                </span>
                            </div>
                            <div class="mb-10">
                                <label>وضعیت : </label>
                                @include('admin.theme.elements.icons.check', ['status' => $article->active ])
                            </div>
                            <div class="mb-10">
                                <label>تاریخ انتشار : </label><span>{{ $article->published_at }}</span>
                            </div>
                            <div class="mb-10">
                                <label>تاریخ ایجاد : </label><span>{{ $article->created_at }}</span>
                            </div>
                            <div class="mb-10">
                                <label>کاربر ایجاد کننده : </label><span>{{ $article->user->name ?? '' }}</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                        </div>
                    </div>
                </div>
            </div>
        </td>
        <td>
            <div class="d-flex justify-content-end flex-shrink-0">
                @include('admin.theme.elements.buttons.edit', [
                    'route' => route('admin.articles.edit', ['article' => $article->id]),
                    'access' => 'admin.articles.edit'
                ])
                @include('admin.theme.elements.buttons.destroy', [
                    'route' => route('admin.articles.destroy', ['article' => $article->id]),
                    'access' => 'admin.articles.destroy',
                ])
            </div>
        </td>
    </tr>
@empty

@endforelse
