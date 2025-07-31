<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $fillable = [
        'url',
        'method',
        'headers',
        'body',
        'ip',
        'response'
    ];
}
