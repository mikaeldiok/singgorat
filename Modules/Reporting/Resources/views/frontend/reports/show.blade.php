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
                            <h1>{{$$module_name_singular->title}}</h1>
                            
                            <div class="post-meta">
                                <span class="font-weight-bold mr-3">
                                   {{$$module_name_singular->reporter}}                           
                                </span>
                                <span class="post-date mr-3">  
                                    {{$$module_name_singular->kelas  }}
                                </span>
                            </div>
                            
                            <div class="post-meta">
                                <span class="font-weight-bold mr-3">
                                   {{$$module_name_singular->created_at->format('d M Y')}}                           
                                </span>
                            </div>
                            @include('frontend.includes.messages')
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 mb-5 text-left text-dark">
                            <div class="card">
                                <div class="card-body">
                                    <p>
                                       {!!$$module_name_singular->content!!}
                                    </p>                                
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-left">
                        <h2 class="my-3">Tanggapan</h2>   
                        @if(count($$module_name_singular->remarks) > 0)  
                            @foreach($$module_name_singular->remarks as $remark)
                                @include("feedback::components.remark-block")
                            @endforeach
                        @else
                            <p>
                                Belum ada tanggapan
                            </p>
                        @endif
                        
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
