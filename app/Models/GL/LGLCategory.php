<?php


namespace App\Models\GL;


use Illuminate\Database\Eloquent\Model;

class LGLCategory extends Model
{
    protected $table = "l_gl_category";
    protected $primaryKey = "id";

    public function glType()
    {
        return $this->belongsTo(LGlType::class, 'gl_type_id');
    }
}
