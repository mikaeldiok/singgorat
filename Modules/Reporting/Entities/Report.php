<?php

namespace Modules\Reporting\Entities;

use Auth;
use DB;
use Carbon\Carbon;
use App\Models\BaseModel;
use App\Models\Traits\HasHashedMediaTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as UserModel;
use Spatie\Permission\Traits\HasRoles;

use Modules\Recruiter\Entities\Booking;
use Modules\Reporting\Database\factories\ReportFactory;

class Report extends UserModel implements HasMedia
{
    use HasHashedMediaTrait;
    use HasRoles;

    use HasFactory;
    use SoftDeletes;

    protected $table = "reports";

    protected static $logName = 'reports';
    protected static $logOnlyDirty = true;
    protected static $logAttributes = ['name', 'id'];
    
    protected static function newFactory()
    {
        return ReportFactory::new();
    }

    protected $guarded = [
        'id',
        'updated_at',
        '_token',
        '_method',
    ];

    protected $hidden = [
     'password', 'remember_token',
    ];
    
    protected static function boot()
    {
        parent::boot();

        // create a event to happen on creating
        static::creating(function ($table) {
            $table->created_by = Auth::id();
            $table->created_at = Carbon::now();
        });

        // create a event to happen on updating
        static::updating(function ($table) {
            $table->updated_by = Auth::id();
        });

        // create a event to happen on saving
        static::saving(function ($table) {
            $table->updated_by = Auth::id();
        });

        // create a event to happen on deleting
        static::deleting(function ($table) {
            $table->deleted_by = Auth::id();
            $table->save();
        });
    }
    
    /**
     * Create Converted copies of uploaded images.
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(250)
              ->height(250)
              ->quality(70);

        $this->addMediaConversion('thumb300')
              ->width(300)
              ->height(300)
              ->quality(70);
    }


    /**
     * Get the list of all the Columns of the table.
     *
     * @return array Column names array
     */
    public function getTableColumns()
    {
        //determine connections
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
        
        switch($driver){
            case 'mysql':
                    $table_info_columns = DB::select(DB::raw('SHOW COLUMNS FROM '.$this->getTable()));
                break;
            case 'pgsql':       
                    $table_info_columns = DB::select(DB::raw(
                        "SELECT data_type as Type, column_name as Field
                            FROM information_schema.columns
                        Where table_schema = 'public'    
                        AND table_name   = '".$this->getTable()."'"
                    ));
                break;
        }   

        return $table_info_columns;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function type()
    {
        return $this->belongsTo('Modules\Reporting\Entities\Type','category');
    }

    public function checkBookedBy($corporationId)
    {
        $checkBooking = Booking::where('report_id',$this->id)
                                ->where('corporation_id',$corporationId)
                                ->first();
        if($checkBooking){
            return true;
        }else{
            return false;
        }
    }

    public function scopeAvailable($query){
        return $query->where('status','Published');
    }

    public function isAvailable(){
        return $this->available;
    }
    
    public function remarks()
    {
        return $this->hasMany('Modules\Feedback\Entities\Remark');
    }
    
    public function selectedRemarks()
    {
        if(auth()->user() == null){
            return $this->hasMany('Modules\Feedback\Entities\Remark');
        }else{
            if(auth()->user()->isSuperAdmin()){
                return $this->hasMany('Modules\Feedback\Entities\Remark');
            }else{
                return $this->hasMany('Modules\Feedback\Entities\Remark')->where('user_id',auth()->user()->id);
            }
        }
    }

}

