<?php

namespace Modules\Feedback\Http\Controllers\Frontend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Auth;
use Flash;
use Modules\Feedback\Services\RemarkService;
use Modules\Feedback\Http\Requests\Frontend\RemarksRequest;
use Spatie\Activitylog\Models\Activity;

class RemarksController extends Controller
{
    protected $remarkService;

    public function __construct(RemarkService $remarkService)
    {
        // Page Title
        $this->module_title = trans('menu.feedback.remarks');

        // module name
        $this->module_name = 'remarks';

        // directory path of the module
        $this->module_path = 'remarks';

        // module icon
        $this->module_icon = 'fas fa-user-tie';

        // module model name, path
        $this->module_model = "Modules\Remark\Entities\Remark";

        $this->remarkService = $remarkService;
    }

    /**
     * Go to remark homepage
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function index()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $remarks = $this->remarkService->getAllRemarks()->data;

        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "feedback::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "remarks",'driver')
        );
    }

    public function artikel()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $remarks = $this->remarkService->getAllRemarks()->data;

        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "feedback::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "remarks",'driver')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Create';

        $options = $this->remarkService->create()->data;

        return view(
            "feedback::frontend.$module_name.create",
            compact('module_title', 'module_name', 'module_icon', 'module_action', 'module_name_singular','options')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(RemarksRequest $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Store';

        $remarks = $this->remarkService->store($request);

        $$module_name_singular = $remarks->data;

        if(!$remarks->error){
            Flash::success('<i class="fas fa-check"></i> Bagus! Seratan anda sudah Terkirim.')->important();
        }else{
            Flash::error("<i class='fas fa-times-circle'></i> Terjadi kesalahan. silakan coba beberapa saat lagi'")->important();
        }

        return redirect("$module_name/create");
    }


    /**
     * Go to remark catalog
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function indexPaginated(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $remarks = $this->remarkService->getPaginatedRemarks(20,$request)->data;
        
        if ($request->ajax()) {
            return view("feedback::frontend.$module_name.remarks-card-loader", ['remarks' => $remarks])->render();  
        }
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "feedback::frontend.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "remarks",'driver')
        );
    }

    /**
     * Go to remark catalog
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function filterRemarks(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $remarks = $this->remarkService->filterRemarks(20,$request)->data;
        
        if ($request->ajax()) {
            return view("feedback::frontend.$module_name.remarks-card-loader", ['remarks' => $remarks])->render();  
        }
        
    }


    /**
     * Show remark details
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function show($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Index';

        $remark = $this->remarkService->show($id)->data;
        
        
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
       
        return view(
            "feedback::frontend.$module_name.show",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "remark",'driver')
        );
    }
}
