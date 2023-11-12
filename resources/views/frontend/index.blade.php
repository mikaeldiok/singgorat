@extends('frontend.layouts.app')

@section('title') {{app_name()}} @endsection

@section('content')

<div class="block-31" style="position: relative;">
    <div class="owl-carousel loop-block-31 ">
      <div class="block-30 block-30-compact item" style="background-image: url('images/bg_1.jpg');" data-stellar-background-ratio="0.5">
        <div class="container">
          <div class="row align-items-center justify-content-center text-center">
            <div class="col-md-9">
              <h1 class="heading mb-4" style="font-family:Rockwell;font-weight: bold;">Si NggoRat</h1>
              <h5 class="mb-5 text-light">Sistim Pendukung Pembelajaran Siswa Kompetensi Public Relation dan Public Speaking</h5>
              <p class="mb-0">
                <a href="#get-started" id="start" class="btn btn-lg btn-theme px-3 py-2 mx-2">Mulai <i class="fas fa-arrow-down"></i></a>
                <a href="{{route('frontend.reports.index')}}" id="start" class="btn btn-lg btn-theme px-3 py-2 mx-2">Artikel<i class="fas fa-arrow-right"></i></a>
              </p>
                <!-- <p style="display: inline-block;" class="text-warning"><a href="https://vimeo.com/channels/staffpicks/93951774"  data-fancybox class="ftco-play-video d-flex text-warning"><span class="play-icon-wrap align-self-center mr-4"><span class="ion-ios-play"></span></span> <span class="align-self-center">Watch Video</span></a></p> -->
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
  
  <div class="site-section section-counter">
    <div class="container">
      <div class="row" id="get-started">
       @if(user_registration())
        <div class="col-md-6 border rounded-lg border-primary p-4 welcome-text">
          <h2 class="display-4 mb-3">Sampaikan cerita anda</h2>
          <p class="lead">Mulai menyampaikan cerita anda dan ikuti proses kami dalam menanggapi cerita anda</p>
          <p class="mb-4">Untuk melacak proses dibutuhkan akun. Anda akan diminta untuk membuat akun untuk mulai melihat proses cerita anda</p>
          <p class="mb-0"><a href="{{route('backend.reports.create')}}" class="btn btn-lg btn-theme px-3 py-2">Mulai <i class="fas fa-arrow-right"></i></a></p>
        </div>
      @endif
        <div class="col-md-6 p-4 {{!user_registration() ? 'border rounded-lg border-primary' : ''}} welcome-text">
          <h2 class="display-4 mb-3">Buat Seratan</h2>
          <p class="lead">Mulai membuat seratan dan dapatkan feedback dari para pendamping</p>
          <p class="mb-4"></p>
          <p class="mb-0"><a href="{{route('frontend.reports.create')}}" class="btn btn-lg btn-theme px-3 py-2">Mulai <i class="fas fa-arrow-right"></i></a></p>
        </div>
      </div>
    </div>
  </div>

  <!-- <div class="site-section bg-light">
    <div class="container">
      <div class="row mb-5">
        <div class="col-md-12">
          <h2>Latest News</h2>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-0">
          <div class="post-entry">
            <a href="#" class="mb-3 img-wrap">
              <img src="images/img_4.jpg" alt="Image placeholder" class="img-fluid">
            </a>
            <h3><a href="#">Be A Volunteer Today</a></h3>
            <span class="date mb-4 d-block text-muted">July 26, 2018</span>
            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
            <p><a href="#" class="link-underline">Read More</a></p>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-0">
          <div class="post-entry">
            <a href="#" class="mb-3 img-wrap">
              <img src="images/img_5.jpg" alt="Image placeholder" class="img-fluid">
            </a>
            <h3><a href="#">You May Save The Life of A Child</a></h3>
            <span class="date mb-4 d-block text-muted">July 26, 2018</span>
            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
            <p><a href="#" class="link-underline">Read More</a></p>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-0">
          <div class="post-entry">
            <a href="#" class="mb-3 img-wrap">
              <img src="images/img_6.jpg" alt="Image placeholder" class="img-fluid">
            </a>
            <h3><a href="#">Children That Needs Care</a></h3>
            <span class="date mb-4 d-block text-muted">July 26, 2018</span>
            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
            <p><a href="#" class="link-underline">Read More</a></p>
          </div>
        </div>
      </div>
    </div>
  </div> .section -->


@endsection

@push ('after-styles')
@endpush

@push ('after-scripts')

<script>
    // Function to handle smooth scrolling when a link is clicked
    function smoothScroll(target) {
        document.querySelector(target).scrollIntoView({
            behavior: 'smooth'
        });
    }

    // Add a click event listener to your buttons
    document.querySelector("#start").addEventListener("click", function(event) {
        event.preventDefault(); // Prevent the default behavior of the link
        smoothScroll("#get-started"); // Call the smoothScroll function with the target section's ID
    });
</script>
@endpush
