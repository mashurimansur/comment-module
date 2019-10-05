<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $table = 'comments';

    protected $primaryKey = 'id';

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'code_unit_id');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
    
}