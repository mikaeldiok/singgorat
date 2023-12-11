@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ $module_title }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route='{{route("backend.$module_name.index")}}' icon='{{ $module_icon }}'>
        {{ $module_title }}
    </x-backend-breadcrumb-item>

    <x-backend-breadcrumb-item type="active">{{ __($module_action) }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <h4 class="card-title mb-0">
                    <i class="{{$module_icon}}"></i> {{ __('labels.backend.users.index.title') }} Guru
                    <small class="text-muted">{{ __('labels.backend.users.create.action') }} </small>
                </h4>
                <div class="small text-muted">
                    {{ __('labels.backend.users.index.sub-title') }}
                </div>
            </div>
            <!--/.col-->
            <div class="col-4">
                <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                    <x-buttons.return-back />
                </div>
            </div>
            <!--/.col-->
        </div>
        <!--/.row-->

        <hr>

        <div class="row mt-4">
            <div class="col">

                {{ html()->form('POST', route('backend.users.store'))->class('form-horizontal')->open() }}
                {{ csrf_field() }}

                <div class="form-group row">
                    {{ html()->label(__('labels.backend.users.fields.first_name'))->class('col-sm-2 form-control-label')->for('first_name') }}
                    <div class="col-sm-10">
                        {{ html()->text('first_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.users.fields.first_name'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                    </div>
                </div>

                <div class="form-group row">
                    {{ html()->label(__('labels.backend.users.fields.last_name'))->class('col-sm-2 form-control-label')->for('last_name') }}
                    <div class="col-sm-10">
                        {{ html()->text('last_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.users.fields.last_name'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                    </div>
                </div>

                <div class="form-group row">
                    {{ html()->label(__('labels.backend.users.fields.email'))->class('col-sm-2 form-control-label')->for('email') }}

                    <div class="col-sm-10">
                        {{ html()->email('email')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.users.fields.email'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                    </div>
                </div>

                <div class="form-group row">
                    {{ html()->label(__('labels.backend.users.fields.password'))->class('col-sm-2 form-control-label')->for('password') }}

                    <div class="col-sm-10">
                        {{ html()->password('password')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.users.fields.password'))
                                ->required() }}
                    </div>
                </div>

                <div class="form-group row">
                    {{ html()->label(__('labels.backend.users.fields.password_confirmation'))->class('col-sm-2 form-control-label')->for('password_confirmation') }}

                    <div class="col-sm-10">
                        {{ html()->password('password_confirmation')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.users.fields.password_confirmation'))
                                ->required() }}
                    </div>
                </div>

                <!-- <div class="form-group row">
                    <?php
                    $field_name = 'reporter_type';
                    $field_lable = "Jenis User Siswa";
                    $field_placeholder = $field_lable;
                    $required = "required";
                    $select_options = [
                        'Guru' => 'Guru',
                        'Murid' => 'Murid',
                    ];
                    $required = "required";
                    ?>
                    {{ html()->label($field_lable, $field_name)->class('col-sm-2 form-control-label') }} {!! fielf_required($required) !!}

                    <div class="col-sm-9">
                        {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control select2')->attributes(["$required"]) }}
                    </div>
                </div> -->
                <div class="text-right my-3">
                    
                    <a class="text-right my-3" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                        advanced settings
                    </a>
                </div>
                <div class="collapse" id="collapseExample">
                    <div class="card card-body">
                        <div class="form-group row">
                            {{ html()->label(__('labels.backend.users.fields.status'))->class('col-6 col-sm-2 form-control-label')->for('status') }}

                            <div class="col-6 col-sm-10">
                                {{ html()->checkbox('status', true, '1') }} @lang('Active')
                            </div>
                        </div>

                        <div class="form-group row">
                            {{ html()->label(__('labels.backend.users.fields.confirmed'))->class('col-6 col-sm-2 form-control-label')->for('confirmed') }}

                            <div class="col-6 col-sm-10">
                                {{ html()->checkbox('confirmed', true, '1') }} @lang('Email Confirmed')
                            </div>
                        </div>

                        <div class="form-group row">
                            {{ html()->label(__('Email Detail'))->class('col-6 col-sm-2 form-control-label')->for('confirmed') }}

                            <div class="col-6 col-sm-10">
                                {{ html()->checkbox('email_credentials', true, '1') }} @lang('Email Credentials')
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            {{ html()->label('Abilities')->class('col-sm-2 form-control-label') }}

                            <div class="col">
                                <div class="row">
                                    <div class="col-12 col-sm-7">
                                        @if ($roles->count())
                                        @foreach($roles as $role)
                                        @if($role->name == "guru")
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="checkbox disabled">
                                                    {{ html()->label(html()->checkbox('roles[]', true, $role->name)->id('role-'.$role->id) . "&nbsp;" . ucwords($role->name). "&nbsp;(".$role->name.")")->for('role-'.$role->id) }}
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <!--card-->
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--form-group-->
                    </div>
                </div>


                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <x-buttons.create title="{{__('Create')}} {{ ucwords(Str::singular($module_name)) }}">
                                {{__('Create')}}
                            </x-buttons.create>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="float-right">
                            <div class="form-group">
                                <x-buttons.cancel />
                            </div>
                        </div>
                    </div>
                </div>
                {{ html()->form()->close() }}

            </div>
        </div>

    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col">
                <small class="float-right text-muted">

                </small>
            </div>
        </div>
    </div>
</div>

@endsection
