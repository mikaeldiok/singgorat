<?php

namespace Modules\Feedback\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Remark extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "remarks";

    protected static $logName = 'remarks';
    protected static $logOnlyDirty = true;
    
    public function report()
    {
        return $this->belongsTo('Modules\Reporting\Entities\Report');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}