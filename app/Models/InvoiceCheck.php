<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceCheck extends Model
{
    protected $table = 'invoice_check';

    protected $guarded = [];

    const CHECK_SUCCESS = 1;
    const CHECK_FAILED = 2;
}
