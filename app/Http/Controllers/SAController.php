<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SAController extends Controller
{
    public function organizer()
    {
        return view('roles.sa.organizer');
    }
}
