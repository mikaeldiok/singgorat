@extends('frontend.layouts.app')

@section('title') {{ __("Donatur") }} @endsection

@section('content')

<div class="block-31" style="position: relative;">
  <div class="bg-primary header-bg"></div>
</div>

<section class="py-3 bg-dark-red-shade">
    <div class="container">
    <div class="row">
            @php
            $$module_name_singular = $$module_name->shift();

            $details_url = route("frontend.$module_name.show",[encode_id($$module_name_singular->id), $$module_name_singular->slug]);
            @endphp

            <div class="col-lg-12 mb-5  text-center">
                <div class="card bg-white border-light shadow-soft flex-md-row no-gutters p-4 justify-content-center">
                    <div class="text-center">
                        <h1>Seratan Siswa</h1>
                        <p>
                            Kumpulan seratan siswa
                        </p>
                    </div>
                    <div class="d-flex align-items-center">

                        <a href="'/'"><h6 class="text-muted small ml-2 mb-0"></h6></a>

                        <h6 class="text-muted small font-weight-normal mb-0 ml-auto"><time datetime="{{$$module_name_singular->published_at}}">{{$$module_name_singular->published_at_formatted}}</time></h6>
                    </div>
                </div>
            </div>
            @foreach ($$module_name as $$module_name_singular)
            @php
            $details_url = route("frontend.$module_name.show",$$module_name_singular->id);
            @endphp
            <div class="col-12 col-md-4 mb-4">
                <div class="card bg-white border-light shadow-soft p-4 rounded">
                    <div class="card-body p-0 pt-4">
                        <a href="{{$details_url}}" class="h3">{{$$module_name_singular->title}}</a>
                        <p>
                            {{$$module_name_singular->remarker}} - {{$$module_name_singular->kelas}}
                        </p>
                        <p class="mb-3">{{$$module_name_singular->intro}}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>       
</section>
@endsection

@push ('after-styles')
@endpush

@push ('after-scripts')

<script type="text/javascript">
    $(function() {
        $('body').on('click', '.pagination a', function(e) {
            e.preventDefault();

            $('#remarks a').css('color', '#dfecf6');
            $('#remarks').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

            var url = $(this).attr('href');  
            getArticles(url);
            window.history.pushState("", "", url);
        });

        function getArticles(url) {
            $.ajax({
                url : url  
            }).done(function (data) {
                $('#remarks').html(data);  
            }).fail(function () {
                alert('Remarks could not be loaded.');
            });
        }
    });

    $(function() {
        $('body').on('click', '#clearFilter', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{route('frontend.remarks.filterRemarks')}}",
                type: "GET",
                data: null,
                success: function(response) {
                    $("#filterForm").trigger("reset");
                    
                    $('#skills').multiselect('refresh');

                    $('#certificate').multiselect('refresh');

                    $('#major').multiselect('refresh');

                    $('#year_class').multiselect('refresh');

                    $('#remarks').html(response);  
                }
            });
        });
    });
</script>
<script >
    if ($("#filterForm").length > 0) {
        $("#filterForm").validate({
            rules: {
                // name: {
                //     required: true,
                //     maxlength: 50
                // },
                // email: {
                //     required: true,
                //     maxlength: 50,
                //     email: true,
                // },
                // message: {
                //     required: true,
                //     maxlength: 300
                // },
            },
            messages: {
                // name: {
                //     required: "Please enter name",
                //     maxlength: "Your name maxlength should be 50 characters long."
                // },
                // email: {
                //     required: "Please enter valid email",
                //     email: "Please enter valid email",
                //     maxlength: "The email name should less than or equal to 50 characters",
                // },
                // message: {
                //     required: "Please enter message",
                //     maxlength: "Your message name maxlength should be 300 characters long."
                // },
            },
            submitHandler: function(form) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                // $('#submit').html('Please Wait...');
                // $("#submit").attr("disabled", true);
                $.ajax({
                    url: "{{route('frontend.remarks.filterRemarks')}}",
                    type: "GET",
                    data: $('#filterForm').serialize(),
                    success: function(response) {
                        // $('#submit').html('Submit');
                        // $("#submit").attr("disabled", false);
                        // document.getElementById("filterForm").reset();

                        $('#remarks').html(response);  

                    }
                });
            }
        })
    } 
</script>
@endpush