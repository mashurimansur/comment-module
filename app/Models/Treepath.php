<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treepath extends Model
{
    protected $table = 'treepath';

    protected $primaryKey = 'ancestor';

    public $timestamps = false;
    
}