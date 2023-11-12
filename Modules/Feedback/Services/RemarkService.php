<?php

namespace Modules\Feedback\Services;

use Modules\Feedback\Entities\Remark;
use Modules\Feedback\Entities\Type;


use Exception;
use Carbon\Carbon;
use Auth;

use ConsoleTVs\Charts\Classes\Echarts\Chart;
use App\Charts\RemarkPerStatus;
use App\Exceptions\GeneralException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


use Modules\Feedback\Imports\RemarksImport;
use Modules\Feedback\Events\RemarkRegistered;

use App\Events\Backend\UserCreated;
use App\Events\Backend\UserUpdated;

use App\Models\User;
use App\Models\Userprofile;

class RemarkService{

    public function __construct()
        {        
        $this->module_title = Str::plural(class_basename(Remark::class));
        $this->module_name = Str::lower($this->module_title);
        
        }

    public function list(){

        Log::info(label_case($this->module_title.' '.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'noname').'(ID:'.(Auth::user()->id ?? "0").')');

        $remark =Remark::query()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $remark,
        );
    }
    
    public function getAllRemarks(){

        $remark =Remark::query()->available()->orderBy('id','desc')->get();
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $remark,
        );
    }

    public function filterRemarks($pagination,$request){

        $remark =Remark::query()->available();

        if(count($request->all()) > 0){
            if($request->has('major')){
                $remark->whereIn('major', $request->input('major'));
            }

            if($request->has('year_class')){
                $remark->whereIn('year_class', $request->input('year_class'));
            }

            if($request->has('height')){
                $remark->where('height', ">=", (float)$request->input('height'));
            }

            if($request->has('weight')){
                $remark->where('weight', ">=", (float)$request->input('weight'));
            }

            if($request->has('skills')){
                $remark->where(function ($query) use ($request){
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
                $remark->where(function ($query) use ($request){
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

        $remark = $remark->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $remark,
        );
    }

    public function getPaginatedRemarks($pagination,$request){

        $remark =Remark::query();

        if(count($request->all()) > 0){

        }

        $remark = $remark->paginate($pagination);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $remark,
        );
    }
    
    public function get_remark($request){

        $id = $request["id"];

        $remark =Remark::findOrFail($id);
        
        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $remark,
        );
    }

    public function getList(){

        $remark =Remark::query()->orderBy('order','asc')->get();

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $remark,
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
            
            $remarkObject = new Remark;
            $remarkObject->fill($data);

            if(is_null($remarkObject->status)){
                $status_raw = explode(",",setting('remark_status'));
                $remarkObject->status = $status_raw[0];
            }

            if(is_null($remarkObject->remarker)){
                $remarkObject->remarker = "noname";
            }

            $remarkObject->ip_address = request()->ip();
            $remarkObject->user_agent = request()->header('User-Agent');
            
            $remarkObjectArray = $remarkObject->toArray();

            $remark = Remark::create($remarkObjectArray);

            if ($request->hasFile('photo')) {
                if ($remark->getMedia($this->module_name)->first()) {
                    $remark->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $remark->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $remark->photo = $media->getUrl();

                $remark->save();
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

        Log::info(label_case($this->module_title.' '.__function__)." | '".$remark->name.'(ID:'.$remark->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $remark,
        );
    }

    public function show($id){

        Log::info(label_case($this->module_title.' '.__function__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Remark::findOrFail($id),
        );
    }

    public function edit($id){

        $remark = Remark::findOrFail($id);

        if($remark->skills){
            $remark->skills = explode(',', $remark->skills); 
        }

        if($remark->certificate){
            $remark->certificate = explode(',', $remark->certificate); 
        }
        
        Log::info(label_case($this->module_title.' '.__function__)." | '".$remark->name.'(ID:'.$remark->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $remark,
        );
    }

    public function update(Request $request,$id){

        $data = $request->all();

        DB::beginTransaction();

        try{

            $remark = new Remark;
            $remark->fill($data);
            
            $updating = Remark::findOrFail($id)->update($remark->toArray());

            $updated_remark = Remark::findOrFail($id);

            if ($request->hasFile('photo')) {
                if ($updated_remark->getMedia($this->module_name)->first()) {
                    $updated_remark->getMedia($this->module_name)->first()->delete();
                }
    
                $media = $updated_remark->addMedia($request->file('photo'))->toMediaCollection($this->module_name);

                $updated_remark->photo = $media->getUrl();

                $updated_remark->save();
            }


        }catch (Exception $e){
            DB::rollBack();
            remark($e);
            Log::critical(label_case($this->module_title.' AT '.Carbon::now().' | Function:'.__FUNCTION__).' | Msg: '.$e->getMessage());
            return (object) array(
                'error'=> true,
                'message'=> $e->getMessage(),
                'data'=> null,
            );
        }

        DB::commit();

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$updated_remark->name.'(ID:'.$updated_remark->id.") ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $updated_remark,
        );
    }

    public function destroy($id){

        DB::beginTransaction();

        try{
            $remarks = Remark::findOrFail($id);
    
            $deleted = $remarks->delete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$remarks->name.', ID:'.$remarks->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $remarks,
        );
    }

    public function trashed(){

        Log::info(label_case($this->module_title.' View'.__FUNCTION__).' | User:'.(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> Remark::onlyTrashed()->get(),
        );
    }

    public function restore($id){

        DB::beginTransaction();

        try{
            $restoring =  Remark::withTrashed()->where('id',$id)->restore();
            $remarks = Remark::findOrFail($id);
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

        Log::info(label_case(__FUNCTION__)." ".$this->module_title.": ".$remarks->name.", ID:".$remarks->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $remarks,
        );
    }

    public function purge($id){
        DB::beginTransaction();

        try{
            $remarks = Remark::withTrashed()->findOrFail($id);
    
            $deleted = $remarks->forceDelete();
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

        Log::info(label_case($this->module_title.' '.__FUNCTION__)." | '".$remarks->name.', ID:'.$remarks->id." ' by User:".(Auth::user()->name ?? 'unknown').'(ID:'.(Auth::user()->id ?? "0").')');

        return (object) array(
            'error'=> false,            
            'message'=> '',
            'data'=> $remarks,
        );
    }

    public function import(Request $request){
        $import = Excel::import(new RemarksImport($request), $request->file('data_file'));
    
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

        $options = array(
            'category'         => [],
        );

        return $options;
    }

    public static function prepareFilter(){
        
        $options = self::prepareOptions();

        $year_class_raw = DB::table('remarks')
                        ->select('kelas', DB::raw('count(*) as total'))
                        ->groupBy('kelas')
                        ->orderBy('kelas','desc')
                        ->get();
        $year_class = [];
            foreach($year_class_raw as $item){
                $year_class += [$item->kelas => $item->kelas];
                // $year_class += [$item->year_class => $item->year_class." (".$item->total.")"];
            }


        $filterOp = array(
            'year_class'          => $year_class,
        );

        return array_merge($options,$filterOp);
    }

    public function getRemarkPerStatusChart(){

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
                        ->join('remarks', 'bookings.remark_id', '=', 'remarks.id')
                        ->where('remarks.major',$major)
                        ->where('remarks.available',1)
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

    public function getDoneRemarksChart(){

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

        $year_class_list_raw = DB::table('remarks')
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
                        ->select('remarks.year_class', DB::raw('count(*) as total'))
                        ->join('remarks', 'bookings.remark_id', '=', 'remarks.id')
                        ->distinct()
                        ->where('remarks.major',$major)
                        ->where('status',"=",$last_key)
                        ->groupBy('remarks.year_class')
                        ->orderBy('remarks.year_class','asc')
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

    public function getRemarkPerYearClassChart(){

        $chart = new Chart;

        $remarks_active = DB::table('remarks')
                            ->select('year_class', DB::raw('count(*) as total'))
                            ->where('available',1)
                            ->groupBy('year_class')
                            ->orderBy('year_class','asc')
                            ->get();

        $remarks=[];
        foreach($remarks_active as $item){
            $remarks += [$item->year_class => $item->total];
        }

        [$keys, $values] = Arr::divide($remarks);

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

        $countAllRemarks = Remark::all()->count();

        $raw_status= Core::getRawData('recruitment_status');
        $status = [];

        foreach($raw_status as $key => $value){
            $status[] = $value;
        }

        $countDoneRemarks = Booking::where('status',end($status))->get()->count();
        
        $stats = (object) array(
            'status'                    => $status,
            'countAllRemarks'          => $countAllRemarks,
            'countDoneRemarks'         => $countDoneRemarks,
        );

        return $stats;
    }

}