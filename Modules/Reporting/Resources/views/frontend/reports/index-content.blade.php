
<div class="row">
    @foreach ($reports as $report)
        @php
        $details_url = route("frontend.$module_name.show",$$module_name_singular->id);
        @endphp
        <div class="col-12 col-md-4 mb-4">
            <div class="card bg-white border-light shadow-soft p-4 rounded">
                <div class="card-body p-0">
                    
                    <?php
                        $limitedString = \Str::limit($$module_name_singular->title, 30, '...')
                    ?>
                    <a href="{{$details_url}}" class="h3">{{$limitedString}}</a>
                    <h6 class="text-muted small font-weight-normal mb-0 ml-auto"><time datetime="{{$$module_name_singular->created_at}}">{{$$module_name_singular->created_at->format('Y-m-d | H:i')}}</time></h6>
                    <br>
                    <p>
                        {{$$module_name_singular->reporter}} - {{$$module_name_singular->kelas}}
                    </p>
                    <p class="mb-3">{{$$module_name_singular->intro}}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="d-flex justify-content-end">
    {{ $reports->links() }}
</div>

@push ('after-styles')
@endpush

@push ('after-scripts')

<script type="text/javascript">
    $(function() {
        $('body').on('click', '.pagination a', function(e) {
            e.preventDefault();

            $('#reports a').css('color', '#dfecf6');
            $('#reports').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

            var url = $(this).attr('href');  
            getArticles(url);
            window.history.pushState("", "", url);
        });

        function getArticles(url) {
            $.ajax({
                url : url  
            }).done(function (data) {
                $('#reports').html(data);  
            }).fail(function () {
                alert('Reports could not be loaded.');
            });
        }
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
                    url: "{{route('frontend.reports.filterReports')}}",
                    type: "GET",
                    data: $('#filterForm').serialize(),
                    success: function(response) {
                        // $('#submit').html('Submit');
                        // $("#submit").attr("disabled", false);
                        // document.getElementById("filterForm").reset();

                        $('#reports').html(response);  

                    }
                });
            }
        })
    } 
</script>
@endpush