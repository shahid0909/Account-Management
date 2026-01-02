<?php


namespace App\Models\GL;


use Illuminate\Database\Eloquent\Model;

class LCalenderMaster extends Model
{
    protected $table = "l_calender_master";
    protected $primaryKey = "id";

    public function fiscalPeriod()
    {
        return $this->belongsTo(L_fiscal_period::class, 'fiscal_period_id');
    }
}
