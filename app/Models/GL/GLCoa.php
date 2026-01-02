<?php


namespace App\Models\GL;


use Illuminate\Database\Eloquent\Model;

class GLCoa extends Model
{
    protected $table = "gl_coa";
    protected $primaryKey = "id";

    public function glType()
    {
        return $this->belongsTo(LGlType::class, 'gl_type_id');
    }
}
