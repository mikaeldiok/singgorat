@extends('frontend.layouts.app')

@section('title') {{$$module_name_singular->name}} @endsection

@section('content')

<div class="block-31" style="position: relative;">
  <div class="bg-primary header-bg"></div>
</div>

<section class="py-3 bg-dark-red-shade">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mb-5  text-center">
                <div class="card bg-white border-light shadow-soft no-gutters p-4 justify-content-center">
                    
                    <div class="row">
                        <div class="col-lg-12 mb-5  text-center">
                        <h1 class="display-3 mb-4 px-lg-5">
                            {{$$module_name_singular->name}}
                        </h1>
                        <div class="post-meta">
                            <span class="font-weight-bold mr-3">
                                {{isset($$module_name_singular->created_by_alias)? $$module_name_singular->created_by_alias : $$module_name_singular->created_by_name}}
                            </span>
                            <span class="post-date mr-3">
                                {{$$module_name_singular->published_at_formatted}}
                            </span>
                        </div>
                            @include('frontend.includes.messages')
                        </div>
                    </div>     
                    @php
                    $post_details_url = route('frontend.posts.show',[encode_id($$module_name_singular->id), $$module_name_singular->slug]);
                    @endphp
                    <div class="section section-sm bg-white pt-5 text-black">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col">
                                    <?php
                                    $video_string = $$module_name_singular->ytvid;

                                        if (strpos($video_string, 'youtu.be/') !== false) {
                                           $video_string = str_replace("youtu.be/","www.youtube.com/embed/",$video_string);
                                        } 

                                        if (strpos($video_string, 'youtube.com/watch?v=') !== false) {
                                           $video_string = str_replace("youtube.com/watch?v=","youtube.com/embed/",$video_string);
                                        } 
                                    ?>
                                    @if($$module_name_singular->ytvid)
                                        <iframe width="560" height="315" src="{{$video_string}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                    @endif
                                    @if($$module_name_singular->featured_image)
                                        <img class="img-fluid" src="{{$$module_name_singular->featured_image}}" alt="{{$$module_name_singular->name}}">
                                    @endif
                                    <div class="text-left">
                                        <p>
                                            {!!$$module_name_singular->content!!}
                                        </p>
                                        <p>
                                            <span class="font-weight-bold">
                                                Category:
                                            </span>

                                            <a href="{{route('frontend.categories.show', [encode_id($$module_name_singular->category_id), $$module_name_singular->category->slug])}}" class="badge badge-sm badge-warning text-uppercase px-3">{{$$module_name_singular->category_name}}</a>
                                        </p>

                                        <p>
                                            @foreach ($$module_name_singular->tags as $tag)
                                            <a href="{{route('frontend.tags.show', [encode_id($tag->id), $tag->slug])}}" class="badge badge-sm badge-info text-uppercase px-3">{{$tag->name}}</a>
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row justify-content-sm-center align-items-center py-3 mt-3">
                            <div class="col-12 col-lg-8">
                                <div class="row">
                                    <div class="col-9 col-md-6">
                                        <h6 class="font-weight-bolder d-inline mb-0 mr-3">Share:</h6>

                                        @php $title_text = $$module_name_singular->name; @endphp

                                        <button class="btn btn-sm mr-3 btn-icon-only btn-pill btn-twitter d-inline" data-sharer="twitter" data-via="LaravelStarter" data-title="{{$title_text}}" data-hashtags="LaravelStarter" data-url="{{url()->full()}}" data-toggle="tooltip" title="Share on Twitter" data-original-title="Share on Twitter">
                                            <span class="btn-inner-icon"><i class="fab fa-twitter"></i></span>
                                        </button>

                                        <button class="btn btn-sm mr-3 btn-icon-only btn-pill btn-facebook d-inline" data-sharer="facebook" data-hashtag="LaravelStarter" data-url="{{url()->full()}}" data-toggle="tooltip" title="Share on Facebook" data-original-title="Share on Facebook">
                                            <span class="btn-inner-icon"><i class="fab fa-facebook-f"></i></span>
                                        </button>
                                    </div>

                                    <div class="col-3 col-md-6 text-right"><i class="far fa-bookmark text-primary" data-toggle="tooltip" data-placement="top" title="" data-original-title="Bookmark story"></i></div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>       
</section>
@endsection


@push ("after-scripts")
<script src="https://cdn.jsdelivr.net/npm/sharer.js@latest/sharer.min.js"></script>
@endpush
