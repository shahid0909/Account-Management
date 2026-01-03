<?php
namespace App\Models\GL;
use Illuminate\Database\Eloquent\Model;


class GL_trans_details extends Model

{

    protected $table = "gl_trans_details";
    protected $primaryKey = "id";

    public function gl_coa()
    {
        return $this->belongsTo(GLCoa::class, 'gl_acc_id', 'gl_acc_id');
    }

}

