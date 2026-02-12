<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoginLog;

class LogLoginController extends Controller
{
      public function index()
    {
        $logs = LoginLog::latest()->get();
        return view('admin.LogLogin.index', compact('logs'));
    }
}
