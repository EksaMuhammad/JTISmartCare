<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diagnosis;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $data = Diagnosis::latest()->get();

        return view('admin.monitoring', compact('data'));
    }
}