<?php


namespace App\Models\GL;


use Illuminate\Database\Eloquent\Model;

class LCalenderDetails extends Model
{
    protected $table = "l_calender_details";
    protected $primaryKey = "id";

    public function calender_master()
    {
        return $this->belongsTo(LCalenderMaster::class, 'calender_master_id');
    }
}
