@forelse($reports as $key => $report)
    <tr>
        <td>
            <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input class="form-check-input form-check-input-single widget-9-check w-15px h-15px" type="checkbox" value="{{ $report->id }}"/>
            </div>
        </td>
        <td>
            {{ ($loop->index + 1) }}
        </td>
        <td>{{ basename($report->reportable_type) }}</td>
        <td>{!! $report->moreData['content'] !!}</td>
        <td>{{ $report->created_at }}</td>
        <td>
            <button onclick="showReportDetails('{{ route('admin.reports.show', ['report' => $report->id]) }}')" class="btn btn-sm btn-light-facebook btn-block p-2 fs-8">نمایش جزئیات</button>
        </td>
        <td>
            <div class="d-flex justify-content-end flex-shrink-0">
                @include('admin.theme.elements.buttons.destroy', [
                    'route' => route('admin.reports.destroy', ['report' => $report->id]),
                    'access' => 'admin.reports.destroy',
                ])
            </div>
        </td>
    </tr>
@empty

@endforelse
