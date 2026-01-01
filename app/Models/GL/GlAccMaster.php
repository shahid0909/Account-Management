<?php


namespace App\Models\GL;


use Illuminate\Database\Eloquent\Model;

class GlAccMaster extends Model
{
    protected $table = "gl_acc_master";
    protected $primaryKey = "id";

    public function glCoa()
    {
        return $this->belongsTo(GLCoa::class, 'gl_coa_id');
    }
}
