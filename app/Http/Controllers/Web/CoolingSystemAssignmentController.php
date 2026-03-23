<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;

class CoolingSystemAssignmentController extends Controller
{ //
    public function store(Request $request, Room $room)
    {
        return $room;
    }

}
