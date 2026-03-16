<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsWebController extends Controller
{
    /**
     * Display the settings dashboard.
     */
    public function index()
    {
        return view('settings.index');
    }
}