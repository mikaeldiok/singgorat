<div class="row">
    <?php
    $field_name = 'reporter';
    $field_lable = __("reporting::$module_name.$field_name");
    $field_placeholder = $field_lable;
    $required = "";
    ?>
    <div class="col-lg-2">
        {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
    </div>
    <div class="col-lg-10">
        : <span class="font-weight-bold">{{$report->$field_name}}</span>
    </div>
</div>
<div class="row">
    <?php
    $field_name = 'kelas';
    $field_lable = __("reporting::$module_name.$field_name");
    $field_placeholder = $field_lable;
    $required = "";
    ?>
    <div class="col-lg-2">
        {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
    </div>
    <div class="col-lg-10">
        : <span class="font-weight-bold">{{$report->$field_name}}</span>
    </div>
</div>
<hr>
<div class="row">
    <?php
    $field_name = 'title';
    $field_lable = __("reporting::$module_name.$field_name");
    $field_placeholder = $field_lable;
    $required = "required";
    ?>
    <div class="col-lg-2">
        <h5>{{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}<h5>
    </div>
    <div class="col-lg-10">
    <h5> : <span class="font-weight-bold">{{$report->$field_name}}</span><h5>
    </div>
</div>
<div class="row">
    <?php
        $field_name = 'status';
        $field_lable = __("reporting::$module_name.$field_name");
        $field_placeholder = $field_lable;
        $required = "required";
        $select_options = $options['status'];
        ?>
        @can('edit_'.$module_name)
        <div class="col-lg-6">
            <div class="form-group">
                {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
                {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control select2')->attributes(["$required"]) }}
            </div>    
        </div>
        @else
            <div class="col-lg-2">
                <h5>{{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}<h5>
            </div>
            <div class="col-lg-10">
            <h5> : <span class="font-weight-bold">{{$report->$field_name}}</span><h5>
            </div>
        @endcan
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <?php
            $field_name = 'content';
            $field_lable = __("reporting::$module_name.$field_name")." (Preview Only)";
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->textarea($field_name)->placeholder($field_placeholder)->style('background-color:#FFF !important;')->class('form-control')->attributes(["disabled", 'aria-label'=>'Image', 'rows'=>10]) }}
        </div>
    </div>
</div>
<!-- Select2 Library -->
<x-library.select2 />
<x-library.datetime-picker />

@push('after-styles')
<!-- File Manager -->
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
@endpush

@push ('after-scripts')

<script type="text/javascript" src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>

<script type="text/javascript">

CKEDITOR.replace('content', {filebrowserImageBrowseUrl: '/file-manager/ckeditor', language:'{{App::getLocale()}}', defaultLanguage: 'en'});

document.addEventListener("DOMContentLoaded", function() {

  document.getElementById('button-image').addEventListener('click', (event) => {
    event.preventDefault();

    window.open('/file-manager/fm-button', 'fm', 'width=800,height=600');
  });
});

</script>
<!-- Date Time Picker & Moment Js-->
<script type="text/javascript">
$(function() {
    var date = moment("{{$$module_name_singular->birth_date ?? ''}}", 'YYYY-MM-DD').toDate();
    $('.datetime').datetimepicker({
        format: 'DD/MM/YYYY',
        date: date,
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar-alt',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'far fa-calendar-check',
            clear: 'far fa-trash-alt',
            close: 'fas fa-times'
        }
    });
});

$(document).ready(function() {
        $('#skills').multiselect({
                enableFiltering: true,
            });

        $('#certificate').multiselect({
                enableFiltering: true,
            });
    });

</script>

@endpush
