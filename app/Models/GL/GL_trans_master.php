<?php





namespace App\Models\GL;





use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class GL_trans_master extends Model

{
    protected $table = "gl_trans_master";
    protected $primaryKey = "id";

    public function trans_detail()
    {
        return $this->hasMany(GL_trans_details::class,'trans_master_id','id');
    }
    public function fiscal_year()
    {
        return $this->belongsTo(LCalenderMaster::class, 'fiscal_year_id');
    }
    public function posting_period()
    {
        return $this->belongsTo(LCalenderDetails::class, 'trans_period_id');
    }
    public function createuser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

