@extends('frontend.layouts.app')

@section('title') {{ __("Posts") }} @endsection

@section('content')

<div class="block-31" style="position: relative;">
  <div class="bg-primary header-bg"></div>
</div>

<section class="py-3 bg-dark-red-shade">
    <div class="container">
    <div class="row">
            <div class="col-lg-12 mb-5  text-center">
                <div class="card bg-white border-light shadow-soft flex-md-row no-gutters p-4 justify-content-center">
                    <div class="text-center">
                        <h1>Kategori</h1>
                        <p>
                            List Kategori Materi
                        </p>
                    </div>
                    <div class="d-flex align-items-center">

                        <a href="'/'"><h6 class="text-muted small ml-2 mb-0"></h6></a>

                    </div>
                </div>
            </div>
            
@if(count($$module_name))
<section class="section section-lg line-bottom-light">
    <div class="container mt-n7 mt-lg-n12 z-2">
        <div class="row">
            @php
            $$module_name_singular = $$module_name->shift();

            $details_url = route("frontend.$module_name.show",[encode_id($$module_name_singular->id), $$module_name_singular->slug]);
            @endphp

            <div class="col-lg-12 mb-5">
                <div class="card bg-white border-light shadow-soft flex-md-row no-gutters p-4">
                    <!-- <a href="{{$details_url}}" class="col-md-6 col-lg-6">
                        <img src="{{$$module_name_singular->featured_image}}" alt="" class="card-img-top">
                    </a> -->
                    <div class="card-body d-flex flex-column justify-content-between col-auto py-4 p-lg-2">
                        <a href="{{$details_url}}">
                            <h2>{{$$module_name_singular->name}}</h2>
                        </a>
                        <p>
                            {{$$module_name_singular->description}}
                        </p>
                        <p class="mb-3 font-weight-bold">
                            Total {{$$module_name_singular->posts->count()}} posts.
                        </p>
                    </div>
                </div>
            </div>

        <div class="d-flex justify-content-center w-100 mt-3">
            {{$$module_name->links()}}
        </div>
    </div>
</section>
@endif

        </div>

    </div>       
</section>
@endsection

