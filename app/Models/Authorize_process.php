<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Authorize_process extends Model

{

    protected $table = "auth_process";
    protected $primaryKey = "id";

    public function authuser()
    {
        return $this->belongsTo(User::class, 'auth_user_id');
    }
}

