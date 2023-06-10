@extends('admin.theme.master')
@section('admin-content')
    @if(isSuperAdmin())
        <iframe style="border-width: 0" width="100%" height="600px" src="{{ route('log-viewer::dashboard') }}"></iframe>
    @endif
@endsection