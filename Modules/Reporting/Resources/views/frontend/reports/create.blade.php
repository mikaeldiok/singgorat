@extends('frontend.layouts.app')

@section('title') {{ __($module_action) }} {{ $module_title }} @endsection

@section('content')

<div class="block-31" style="position: relative;">
  <div class="bg-primary header-bg"></div>
</div>

<section class="py-3 bg-dark-red-shade">
    <div class="container">
        <div class="row justify-content-left">
            <div class="col-12 col-md-10 pt-8">
                <div class="card">
                    <div class="card-body">

                        @include('flash::message')

                        <!-- Errors block -->
                        @include('backend.includes.errors')

                        <h2>Seratanku Hari ini</h2>
                        <small>Tuangkan kreatifitas mu disini!</small>
                        <hr>

                        <div class="row mt-4">
                            <div class="col">
                                {{ html()->form('POST', route("frontend.$module_name.store"))->class('form')->attributes(['enctype'=>"multipart/form-data"])->open() }}

                                @include ("reporting::frontend.$module_name.form")

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-warning" onclick="history.back(-1)"><i class="fas fa-arrow-left"></i> Cancel</button>

                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="float-right">
                                            <div class="form-group">
                                                {{ html()->button($text = "<i class='fas fa-plus-circle'></i> " . ucfirst("Buat Seratan") . "", $type = 'submit')->class('btn btn-success') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{ html()->form()->close() }}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>       
</section>


@stop
