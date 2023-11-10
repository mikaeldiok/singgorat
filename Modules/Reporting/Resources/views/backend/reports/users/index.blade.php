@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ $module_title }} @stop

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item type="active" icon='{{ $module_icon }}'>{{ $module_title }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-8">
                <h4 class="card-title mb-0">
                    <i class="{{ $module_icon }}"></i> {{ $module_title }} Anda <small class="text-muted">{{ __($module_action) }}</small>
                </h4>
            </div>
            <!--/.col-->
            <div class="col-4">
                <div class="float-right">
                    @can('add_'.$module_name)

                            <a href='{{route("backend.$module_name.create") }}'
                                class='btn btn-success'
                                data-toggle="tooltip"
                                title="Buat Laporan">
                                <i class="fas fa-plus"></i>
                                Tambah Laporan Baru
                            </a>
                    @endcan
                </div>
            </div>
            <!--/.col-->
        </div>
        <!--/.row-->

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        {{ $dataTable->table() }}
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-7">
                <div class="float-left">

                </div>
            </div>
            <div class="col-5">
                <div class="float-right">

                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push ('after-styles')
<!-- DataTables Reporting and Extensions -->
<link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">

@endpush

@push ('after-scripts')
<!-- DataTables Reporting and Extensions -->
{!! $dataTable->scripts()  !!}
@endpush
