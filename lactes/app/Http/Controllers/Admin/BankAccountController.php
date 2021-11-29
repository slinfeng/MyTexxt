<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BankAccountController extends Controller
{
    /**
     * 銀行情報取得
     * @param $id
     * @return false|string
     */
    public function show($id)
    {
        return json_encode(BankAccount::with('BankAccountType')->find($id));
    }
}
