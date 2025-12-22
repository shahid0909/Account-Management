<?php


namespace App\Models\GL;


use Illuminate\Database\Eloquent\Model;

class LCalenderDetailsActive extends Model
{
    protected $table = "l_calender_details_active";
    protected $primaryKey = "id";

    public function calender_master()
    {
        return $this->belongsTo(GlAccMaster::class, 'calender_master_id');
    }
}
