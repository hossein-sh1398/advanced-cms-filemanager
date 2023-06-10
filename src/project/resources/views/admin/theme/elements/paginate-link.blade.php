@if ($models->count() && $table['isPaginate'])
    <div class="col-md-6 pt-2">
        <span>نمایش {{ $models->firstItem() }} تا {{ $models->lastItem() }} از مجموع {{ $models->total() - ($countSuperAdmin ?? 0) }} مورد</span>
    </div>
    <div class="col-md-6 ">
        {{ $models->links() }}
    </div>
@endif
