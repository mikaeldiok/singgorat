
<div class="d-flex justify-content-between mb-1">
    <div id="reports-count">
        Menampilkan {{$reports->count()}} dari {{ $reports->total() > 100 ? "100+" : $reports->total()}} Siswa
    </div>
    <div id="reports-loader">
        {{$reports->links()}}
    </div>
</div>
<div class="row">
@foreach($reports as $report)
    <div class="col-3 pb-3 card-padding" style="margin-right: 0px;">
        @include('reporting::frontend.reports.report-card-big')
    </div>

@endforeach
</div>
<div class="d-flex justify-content-end">
    {{$reports->links()}}
</div>

@push('after-scripts')
    @include("reporting::frontend.reports.dynamic-scripts")
@endpush
