<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class AccountantController extends Controller
{
    public function invoice()
    {
        $item = Item::all();

        return view('roles.accountant.invoice', [
            'items' => $item
        ]);
    }

    public function show(Request $req)
    {
        dd($req);
    }
}
