@extends('frontend.layouts.app')

@section('title') {{ __("Posts") }} @endsection

@section('content')
<div class="block-31" style="position: relative;">
  <div class="bg-primary header-bg"></div>
</div>

<div class="text-center seratan-siswa-section">
    <div class="shadow-soft flex-md-row no-gutters p-4 justify-content-center">
        <div class="text-center text-white">
            <h1 class="font-weight-bold text-white">Artikel dan Materi</h1>
            <p>
                Materi dan referensi Belajar
            </p>
        </div>
        <div class="d-flex align-items-center">

            <a href="'/'"><h6 class="text-muted small ml-2 mb-0"></h6></a>

        </div>
    </div>
</div>

<section class="py-3 bg-dark-red-shade">
    <div class="container">
        <div class="row d-flex">
                
        @if(count($$module_name) > 0)
            <div class="container mt-n7 mt-lg-n12 z-2">
                <div class="row d-flex">
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
                                    <?php
                                        $limitedString = \Str::limit($$module_name_singular->name, 40, '...')
                                    ?>
                                    <h2>{{$limitedString}}</h2>
                                </a>
                                <p>
                                    {{$$module_name_singular->intro}}
                                </p>
                                <div class="d-flex align-items-center">
                                    <img class="avatar avatar-sm rounded-circle" src="{{asset('img/avatars/'.rand(1, 8).'.jpg')}}" alt="">

                                    {!!isset($$module_name_singular->created_by_alias)? $$module_name_singular->created_by_alias : '<a href="'.route('frontend.users.profile', $$module_name_singular->created_by).'"><h6 class="text-muted small ml-2 mb-0">'.$$module_name_singular->created_by_name.'</h6></a>'!!}

                                    <h6 class="text-muted small font-weight-normal mb-0 ml-auto"><time datetime="{{$$module_name_singular->published_at}}">{{$$module_name_singular->published_at_formatted}}</time></h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach ($$module_name as $$module_name_singular)
                    @php
                    $details_url = route("frontend.$module_name.show",[encode_id($$module_name_singular->id), $$module_name_singular->slug]);
                    @endphp
                    <div class="col-12 col-md-4 mb-4">
                        <div class="card bg-white border-light shadow-soft p-4 rounded">
                            <a href="{{$details_url}}"><img src="{{$$module_name_singular->featured_image}}" class="card-img-top" alt=""></a>
                            <div class="card-body p-0 pt-4">
                                
                                <?php
                                    $limitedString = \Str::limit($$module_name_singular->name, 30, '...')
                                ?>
                                <a href="{{$details_url}}" class="h3">{{$limitedString}}</a>
                                <div class="d-flex align-items-center my-4">
                                    <img class="avatar avatar-sm rounded-circle" src="{{asset('img/avatars/'.rand(1, 8).'.jpg')}}" alt="">
                                    {!!isset($$module_name_singular->created_by_alias)? $$module_name_singular->created_by_alias : '<a href="'.route('frontend.users.profile', $$module_name_singular->created_by).'"><h6 class="text-muted small ml-2 mb-0">'.$$module_name_singular->created_by_name.'</h6></a>'!!}
                                </div>
                                <p class="mb-3">{{$$module_name_singular->intro}}</p>
                                <a href="{{route('frontend.categories.show', [encode_id($$module_name_singular->category_id), $$module_name_singular->category->slug])}}" class="badge badge-primary">{{$$module_name_singular->category_name}}</a>
                                <p>
                                    @foreach ($$module_name_singular->tags as $tag)
                                    <a href="{{route('frontend.tags.show', [encode_id($tag->id), $tag->slug])}}" class="badge badge-warning">{{$tag->name}}</a>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center w-100 mt-3">
                    {{$$module_name->links()}}
                </div>
            </div>
        @else
        <div class="container mt-n7 mt-lg-n12 z-2">
                <div class="row d-flex">
                    <div class="col-lg-12 mb-5 text-center p-5">
                        <div class="card bg-white border-light shadow-soft flex-md-row no-gutters p-4">
                            <div class="card-body d-flex flex-column justify-content-between col-auto py-4 p-lg-2">
                                <h3>
                                    Belum ada artikel yang dipublikasi
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        </div>

    </div>       
</section>
@endsection

@push('after-styles')

<style>
        .seratan-siswa-section {
        position: relative;
        background-image: url('https://publicdomainpictures.net/pictures/30000/velka/fundo-azul.jpg');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 50px;
        /* Add a dark overlay */
        &:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Adjust the alpha value for darkness */
        }
        
        .text-center {
            position: relative;
            z-index: 1;
        }
    }
</style>
@endpush

