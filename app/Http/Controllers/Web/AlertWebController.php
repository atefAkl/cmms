<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\Request;

class AlertWebController extends Controller
{
    public function index()
    {
        $alerts = Alert::with('room')->latest()->paginate(10);
        return view('alerts.index', compact('alerts'));
    }
}
