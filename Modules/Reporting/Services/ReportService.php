<?php

namespace Modules\Reporting\Services;

use Modules\Reporting\Entities\Report;
use Modules\Reporting\Entities\Type;


use Exception;
use Carbon\Carbon;
use Auth;

use ConsoleTVs\Charts\Classes\Echarts\Chart;
use App\Charts\ReportPerStatus;
use App\Exceptions\GeneralException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


use Modules\Reporting\Imports\ReportsImport;
use Modules\Reporting\Events\ReportRegistered;

use App\Events\Backend\UserCreated;
use App\Events\Backend\UserUpdated;

use App\Models\User;
use App\Models\Userprofile;

class ReportService{

    public function __construct()
        {        
        $this->module_title = Str::plural(class_basename(Report::class));
        $this->module_name = Str::lower($this->module_title);
        
        }

    public function list(){

        Log::info(label_case($this->module_title.' '.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'noname').'(ID:'.(Auth::user()->id ?? "0").')');

        $report =Report::query()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $report,
        );
    }
    
    public function getAllReports(){

        $report =Report::query()->available()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $report,
        );
    }

    public function filterReports($pagination,$request){

        $report =Report::query()->available();

        if(count($request->all()) > 0){
            if($request->has('major')){
                $report->whereIn('major', $request->input('major'));
            }

            if($request->has('year_class')){
                $report->whereIn('year_class', $request->input('year_class'));
            }

            if($request->has('height')){
                $report->where('height', ">=", (float)$request->input('height'));
            }

            if($request->has('weight')){
                $report->where('weight', ">=", (float)$request->input('weight'));
            }

            if($request->has('skills')){
                $report->where(function ($query) use ($request){
                    $checkSkills = $request->input('skills');
                    foreach($checkSkills as $skill){
                        if($request->input('must_have_all_skills')){
                            $query->where('skills', 'like','%'.$skill.'%');
                        }else{
                            $query->orWhere('skills', 'like','%'.$skill.'%');
                        }
                    }
                });
            }

            if($request->has('certificate')){
                $report->where(function ($query) use ($request){
                    $checkCerts = $request->input('certificate');
                    foreach($checkCerts as $cert){
                        if($request->input('must_have_all_certificate')){
                            $query->where('certificate', 'like','%'.$cert.'%');
                        }else{
                            $query->orWhere('certificate', 'like','%'.$cert.'%');
                        }
                    }
                });
            }
        }

        $report = $report->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $report,
        );
    }

    public function getPaginatedReports($pagination,$request){

        $report =Report::query()->available();

        if(count($request->all()) > 0){

        }

        $report = $report->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $report,
        );
    }
    
    public function get_report($request){

        $id = $request["id"];

        $report =Report::findOrFail($id);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $report,
        );
    }

    public function getList(){

        $report =Report::query()->orderBy('order','asc')->get();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $report,
        );
    }


    public function create(){

       Log::info(label_case($this->module_title.' '.__function__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? '0').')');
        
        $createOptions = $this->prepareOptions();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $createOptions,
        );
    }

    public function store(Request $request){

        $data = $request->all();
        DB::beginTransaction();

        try {
            
            $reportObject = new Report;
            $reportObject->fill($data);

            if(is_null($reportObject->status)){
                $status_raw = explode(",",setting('report_status'));
                $reportObject->status = $status_raw[0];
            }

            if(is_null($reportObject->reporter)){
                $reportObject->reporter = "noname";
            }

            $reportObject->ip_address = request()->ip();
            $reportObject->user_agent = request()->header('User-Agent');
            
            $reportObjectArray = $reportObject->toArray();

            $report = Report::create($reportObjectArray);

            if ($request->hasFile('photo')) {
                if ($report->getMedia($this->module_name)->first()) {
                    $report->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $report->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $report->photo = $media->getUrl();

                $report->save();
            }
            
        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' ON LINE '.__LINE__.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case($this->module_title.' '.__function__)." | '".$report->name.'(ID:'.$report->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $report,
        );
    }

    public function show($id, $reportId = null){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Report::findOrFail($id),
        );
    }

    public function edit($id){

        $report = Report::findOrFail($id);

        if($report->skills){
            $report->skills = explode(',', $report->skills); 
        }

        if($report->certificate){
            $report->certificate = explode(',', $report->certificate); 
        }
        
        Log::info(label_case($this->module_title.' '.__function__)." | '".$report->name.'(ID:'.$report->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $report,
        );
    }

    public function update(Request $request,$id){

        $data = $request->all();

        DB::beginTransaction();

        try{

            $report = new Report;
            $report->fill($data);
            
            $updating = Report::findOrFail($id)->update($report->toArray());

            $updated_report = Report::findOrFail($id);

            if ($request->hasFile('photo')) {
                if ($updated_report->getMedia($this->module_name)->first()) {
                    $updated_report->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $updated_report->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $updated_report->photo = $media->getUrl();

                $updated_report->save();
            }


        }catch (Exception $e){
            DB::rollBack();
            report($e);
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$updated_report->name.'(ID:'.$updated_report->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $updated_report,
        );
    }

    public function destroy($id){

        DB::beginTransaction();

        try{
            $reports = Report::findOrFail($id);
    
            $deleted = $reports->delete();
        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$reports->name.', ID:'.$reports->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $reports,
        );
    }

    public function trashed(){

        Log::info(label_case($this->module_title.' View'.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Report::onlyTrashed()->get(),
        );
    }

    public function restore($id){

        DB::beginTransaction();

        try{
            $restoring =  Report::withTrashed()->where('id',$id)->restore();
            $reports = Report::findOrFail($id);
        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case(__FUNCTION__)." ".$this->module_title.": ".$reports->name.", ID:".$reports->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $reports,
        );
    }

    public function purge($id){
        DB::beginTransaction();

        try{
            $reports = Report::withTrashed()->findOrFail($id);
    
            $deleted = $reports->forceDelete();
        }catch (Exception $e){
            DB::rollBack();
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$reports->name.', ID:'.$reports->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $reports,
        );
    }

    public function import(Request $request){
        $import = Excel::import(new ReportsImport($request), $request->file('data_file'));
    
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $import,
        );
    }

    public static function prepareStatusFilter(){
        
        $raw_status = Core::getRawData('recruitment_status');
        $status = [];
        foreach($raw_status as $key => $value){
            $status += [$value => $value];
        }

        return $status;
    }

    public static function prepareOptions(){
        $options=[];
        $category = Type::pluck('name','id');

        $status_raw = explode(",",setting('report_status'));

        $status = [];
        foreach($status_raw as $value){
            $status += [$value => $value];
        }

        $options = array(
            'category'         => $category,
            'status'           => $status,
        );

        return $options;
    }

    public static function prepareFilter(){
        
        $options = self::prepareOptions();

        $year_class_raw = DB::table('reports')
                        ->select('year_class', DB::raw('count(*) as total'))
                        ->groupBy('year_class')
                        ->orderBy('year_class','desc')
                        ->get();
        $year_class = [];
            foreach($year_class_raw as $item){
                $year_class += [$item->year_class => $item->year_class];
                // $year_class += [$item->year_class => $item->year_class." (".$item->total.")"];
            }


        $filterOp = array(
            'year_class'          => $year_class,
        );

        return array_merge($options,$filterOp);
    }

    public function getReportPerStatusChart(){

        $chart = new Chart;

        $raw_status_order = Core::getRawData('recruitment_status');
        $status_order = [];
        foreach($raw_status_order as $key => $value){
            $status_order += [$value => 0];
        }

        $last_key = array_key_last($status_order);
        $remove_last_status = array_pop($status_order);

        $raw_majors = Core::getRawData('major');
        $majors = [];

        foreach($raw_majors as $key => $value){
            $majors[] = $value;
        }

        foreach($majors as $major){

            $status_raw = DB::table('bookings')
                        ->select('status', DB::raw('count(*) as total'))
                        ->join('reports', 'bookings.report_id', '=', 'reports.id')
                        ->where('reports.major',$major)
                        ->where('reports.available',1)
                        ->where('status',"<>",$last_key)
                        ->groupBy('status')
                        ->orderBy('status','desc')
                        ->get();
            $status = [];

            foreach($status_raw as $item){
                $status += [$item->status => $item->total];
            }

            $status = array_merge($status_order, $status);

            [$keys, $values] = Arr::divide($status);

            $chart->labels($keys);

            $chart->dataset($major, 'bar',$values);
        }

        $chart->options([
            "xAxis" => [
                "axisLabel" => [
                    "interval" => 0,
                    "overflow" => "truncate",
                ],
            ],
            "yAxis" => [
                "minInterval" => 1
            ],
        ]);

        return $chart;
    }

    public function getDoneReportsChart(){

        $chart = new Chart;

        $raw_status_order = Core::getRawData('recruitment_status');
        $status_order = [];
        foreach($raw_status_order as $key => $value){
            $status_order += [$value => 0];
        }

        $last_key = array_key_last($status_order);
        $remove_last_status = array_pop($status_order);

        $raw_majors = Core::getRawData('major');
        $majors = [];

        foreach($raw_majors as $key => $value){
            $majors[] = $value;
        }

        $year_class_list_raw = DB::table('reports')
                                ->select('year_class')
                                ->groupBy('year_class')
                                ->orderBy('year_class','asc')
                                ->limit(8)
                                ->get();
        
        $year_class_list= [];


        foreach($year_class_list_raw as $item){
            $year_class_list += [$item->year_class => 0];
        }                    

        foreach($majors as $major){

            $year_class_raw = DB::table('bookings')
                        ->select('reports.year_class', DB::raw('count(*) as total'))
                        ->join('reports', 'bookings.report_id', '=', 'reports.id')
                        ->distinct()
                        ->where('reports.major',$major)
                        ->where('status',"=",$last_key)
                        ->groupBy('reports.year_class')
                        ->orderBy('reports.year_class','asc')
                        ->get();

            $year_class = [];

            foreach($year_class_raw as $item){
                $year_class += [$item->year_class => $item->total];
            }

            $year_class =  $year_class + $year_class_list;

            ksort($year_class);

            [$keys, $values] = Arr::divide($year_class);

            $chart->labels($keys);

            $chart->dataset($major, 'bar',$values);
        }

        $chart->options([
            "xAxis" => [
                "axisLabel" => [
                    "interval" => 0,
                    "overflow" => "truncate",
                ],
            ],
            "yAxis" => [
                "minInterval" => 1
            ],
        ]);

        return $chart;
    }

    public function getReportPerYearClassChart(){

        $chart = new Chart;

        $reports_active = DB::table('reports')
                            ->select('year_class', DB::raw('count(*) as total'))
                            ->where('available',1)
                            ->groupBy('year_class')
                            ->orderBy('year_class','asc')
                            ->get();

        $reports=[];
        foreach($reports_active as $item){
            $reports += [$item->year_class => $item->total];
        }

        [$keys, $values] = Arr::divide($reports);

        $chart->labels($keys);

        $chart->dataset("Jumlah Siswa", 'bar',$values);
        
        $chart->options([
            "xAxis" => [
                "axisLabel" => [
                    "interval" => 0,
                    "overflow" => "truncate",
                ],
            ],
            "yAxis" => [
                "minInterval" => 1
            ],
        ]);

        return $chart;
    }

    public static function prepareInsight(){

        $countAllReports = Report::all()->count();

        $raw_status= Core::getRawData('recruitment_status');
        $status = [];

        foreach($raw_status as $key => $value){
            $status[] = $value;
        }

        $countDoneReports = Booking::where('status',end($status))->get()->count();
        
        $stats = (object) array(
            'status'                    => $status,
            'countAllReports'          => $countAllReports,
            'countDoneReports'         => $countDoneReports,
        );

        return $stats;
    }

}