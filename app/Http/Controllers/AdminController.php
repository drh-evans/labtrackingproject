<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Module;

class AdminController extends Controller
{
    public function index() {
        return view('admin.index');
    }
}
