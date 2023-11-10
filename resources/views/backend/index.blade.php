@extends('backend.layouts.app')

@section('title') @lang("Dashboard") @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs/>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-8">
                <h4 class="card-title mb-0">@lang("Welcome to", ['name'=>config('app.name')])</h4>
                <div class="small text-muted">{{ date_today() }}</div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6 col-md-6">
                <div class="card">
                <div class="card-body">
                    <h3 class="card-title">{{$open_reports_count}}</h3>
                    <p class="card-text">Total Laporan Belum Selesai</p>
                </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="card">
                <div class="card-body">
                    <h3 class="card-title">{{$today_reports_count}}</h3>
                    <p class="card-text">Laporan Baru Hari ini</p>
                </div>
                </div>
            </div>
        </div>
          
        <!-- Dashboard Content Area -->
        <div class="col-md-12 p-4 welcome-text text-right">
          <p class="mb-0"><a href="{{route('backend.reports.index')}}" class="btn btn-lg btn-success px-3 py-2">Lihat Laporan <i class="fas fa-arrow-right"></i></a></p>
        </div>
        <!-- / Dashboard Content Area -->

    </div>
</div>
@endsection
