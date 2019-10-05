<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $connection = 'pgsql2';

    protected $table = 'users';  
}