<div class="text-right">
    <x-buttons.show class="m-1"  route='{!!route("backend.$module_name.show", $data)!!}' title="{{__('Show')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />
    <x-buttons.show-remarks class="m-1"  route='{!!route("backend.$module_name.showRemarks", $data)!!}' title="{{__('Show')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />

</div>
