<div class="row mb-2">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4>{{$remark->user->first_name}} {{$remark->user->last_name}}</h4> 
                    <small>                                   
                        {{$remark->created_at->format('d M Y , H:i')}}                           
                    </small>
                </div>
                <p class="mt-2">
                    {{$remark->comment}}
                </p>
            </div>
        </div>
    </div>
</div>