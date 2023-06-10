@forelse($list as $key => $file)
    <tr>
        <td>
            <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input class="form-check-input form-check-input-single widget-9-check w-15px h-15px" type="checkbox" value="{{ $file['path'] }}"/>
            </div>
        </td>
        <td>
           {{ $key + 1 }}
        </td>
        <td class="align-left text-start">
            @if ($file['type'] === 'file')
                <span class="svg-icon svg-icon-2x svg-icon-primary me-4">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z" fill="currentColor"></path>
                        <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor"></path>
                    </svg>
                </span>
            @else
                <span class="svg-icon svg-icon-2x svg-icon-primary me-4">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z" fill="currentColor"></path>
                        <path d="M9.2 3H3C2.4 3 2 3.4 2 4V19C2 19.6 2.4 20 3 20H21C21.6 20 22 19.6 22 19V7C22 6.4 21.6 6 21 6H12L10.4 3.60001C10.2 3.20001 9.7 3 9.2 3Z" fill="currentColor"></path>
                    </svg>
                </span>
            @endif
            @if ($file['type'] === 'folder')
                <a style="margin-right: 10px" href="#" onclick="getContentDirectory(event, '{{ $file['path']  }}')">{{ $file['name'] }}</a>
            @elseif (str_contains($file['mimeType'], 'video/') || str_contains($file['mimeType'], 'image/') || str_contains($file['mimeType'], 'audio/'))
                <a href="#" style="margin-right: 10px" title="مشاهده فایل" class="btn btn-icon btn-active-color-danger btn-sm" onclick="getImageFromFtp('{{ route('admin.file.manager.image.ftp', ['path' => $file['path']]) }}')" data-bs-toggle="modal" data-bs-target="#show-file-modal">
                    {{ $file['name'] }}
                </a>
            @else
                {{ $file['name'] }}
            @endif
        </td>
        <td>
            @if ($file['type'] === 'file')
                @include('admin.theme.elements.icons.check', ['status' => $file['hasMedia']])
            @else
                -
            @endif
        </td>
        <td class="text-center">
            <span class="badge badge-light">
                <i class="fa fa-{{ $file['helper']['icon'] }} me-1"></i>
                 <span>{{ $file['helper']['name'] }}</span>
            </span>
        </td>
        <td class="text-center ltr">
            <span class="badge badge-light-{{ $file['size']['class'] }}">
                {{ $file['size']['show'] }}
            </span>
        </td>
        <td class="text-center">
            <span class="badge badge-secondary ucap">
                @if ($file['type'] === 'file')
                    {{ $file['extension'] }}
                @else
                    -
                @endif
            </span>
        </td>
        <td>
            @if ($file['type'] === 'file')
                {{ $file['createdAtFa'] }}</td>
            @else
                -
            @endif
        <td>
            <div class="d-flex justify-content-end flex-shrink-0">
                @if ($file['type'] == 'file')
                    @can('admin.file.manager.download')
                        <a href="#" class="btn btn-icon btn-active-color-danger btn-sm" onclick="downloadFile(event, '{{ route('admin.file.manager.download', ['path' => $file['path']]) }}', '{{$file['name']}}')">
                            <i class="fas fa-download"></i>
                        </a>
                    @endcan
                @else
                    @can('admin.file.manager.download')
                        <a href="#" class="btn btn-icon btn-active-color-danger btn-sm" onclick="downloadFile(event, '{{ route('admin.file.manager.download', ['path' => $file['path']]) }}', '{{$file['zipName']}}')">
                            <i class="fas fa-download"></i>
                        </a>
                    @endcan
                @endif
                @can('admin.file.manager.rename.directory')
                    <a href="#" class="btn btn-icon btn-active-color-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rename-directory-modal-{{ $key }}">
                        <i class="fas fa-edit"></i>
                    </a>
                    <div class="modal fade" tabindex="-1" id="rename-directory-modal-{{ $key }}">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title">تغییر نام </h3>
                                    <!--begin::Close-->
                                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                        <span class="svg-icon svg-icon-1"></span>
                                    </div>
                                    <!--end::Close-->
                                </div>
                                <div class="modal-body">
                                    <p>
                                        <input id="form-input-rename-directory-{{ $key }}" value="{{ $file['basename'] }}" dir="ltr" placeholder="نام جدید" type="text" class="form-control">
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light btn-block btn-hover-rise p-2 fs-8" data-bs-dismiss="modal">بستن</button>
                                    <button type="button" onclick="rename('{{ route('admin.file.manager.rename.directory') }}', '{{ $file['path'] }}', {{ $key }})" class="btn btn-sm btn-secondary btn-block btn-hover-rise p-2 fs-8">
                                        <span class="align-text-bottom mx-1">ذخیره</span>
                                        <i class="fas fa-save fs-7"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
                @include('admin.theme.elements.buttons.destroy', [
                    'route' => route('admin.file.manager.destroy',  ['path' => $file['path']]),
                    'access' => 'admin.file.manager.destroy',
                ])
                @if ($file['type'] == 'folder')
                    <a href="#" class="btn btn-icon btn-active-color-danger btn-sm" onclick="showDetails('{{ route('admin.file.manager.show.details', ['path' => $file['path']]) }}')">
                        <i class="fas fa-eye"></i>
                    </a>
                @elseif (str_contains($file['mimeType'], 'video/') || str_contains($file['mimeType'], 'image/') || str_contains($file['mimeType'], 'audio/'))
                    <a href="#" title="مشاهده فایل" class="btn btn-icon btn-active-color-danger btn-sm" onclick="getImageFromFtp('{{ route('admin.file.manager.image.ftp', ['path' => $file['path']]) }}')" data-bs-toggle="modal" data-bs-target="#show-file-modal">
                        <i class="fas fa-eye"></i>
                    </a>
                @else
                    <a href="#" class="btn btn-icon btn-active-color-danger btn-sm cursor-default"></a>
                @endif
            </div>
        </td>
    </tr>
@empty

@endforelse





