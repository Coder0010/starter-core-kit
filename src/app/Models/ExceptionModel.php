<?php

namespace Mkamel\StarterCoreKit\App\Models;

use Illuminate\Database\Eloquent\Model;

class ExceptionModel extends Model
{

    protected $table = 'exceptions';

    protected $fillable = [
        'exception_class',  // with full namespace
        'message',
    ];

    protected $casts = [
        'message'  => 'json',
    ];

}