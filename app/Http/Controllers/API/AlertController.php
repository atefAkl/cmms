<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Http\Resources\API\AlertResource;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $alerts = Alert::orderBy('created_at', 'desc')->paginate(10);
        return AlertResource::collection($alerts);
    }
}
