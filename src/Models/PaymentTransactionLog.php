<?php

namespace Eboseogbidi\Smartpaymentrouter\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransactionLog extends Model
{
    //
    protected $fillable = ['processor_name','success'];
}
