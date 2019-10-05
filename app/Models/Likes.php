<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    protected $table = 'likes';

    protected $primaryKey = 'id';

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'id');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
    
}