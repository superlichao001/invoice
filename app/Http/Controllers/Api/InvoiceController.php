<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Api\RepositoryInterfaces\InvoiceInterface;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    private $invoice;

    public function __construct(InvoiceInterface $invoice)
    {
        $this->invoice = $invoice;
    }

    public function invoiceCheck(Request $request)
    {
        return $this->invoice->invoiceCheck($request->all());
    }

}
