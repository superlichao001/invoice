<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoice';

    protected $guarded = [];

    const EXPENSES_YES = 1;
    const EXPENSES_NO = 2;
}
