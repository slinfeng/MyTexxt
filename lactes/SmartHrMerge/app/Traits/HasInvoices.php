<?php

namespace App\Traits;

use App\Models\AccountsInvoice;

trait HasInvoices{
    public function accounts_invoices()
    {
        return $this->hasMany(AccountsInvoice::class);
    }
}
