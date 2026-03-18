<?php

namespace App\Http\Controllers;

use App\Models\RoomLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomLayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layouts = RoomLayout::latest()->paginate(10);
        return view('settings.room-layouts.index', compact('layouts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:45',
            'door_position' => 'required|in:left,right,center',
            'wall_thickness' => 'required|numeric|min:0',
            'r-d-width' => 'required|numeric|min:0',
            'r-d-length' => 'required|numeric|min:0',
            'r-d-height' => 'required|numeric|min:0',
            'd-d-width' => 'required|numeric|min:0',
            'd-d-height' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        // Combine dimension fields into array format for JSON
        $layoutDimensions = [
            'width' => $request->input('r-d-width'),
            'length' => $request->input('r-d-length'),
            'height' => $request->input('r-d-height')
        ];
        $doorDimensions = [
            'width' => $request->input('d-d-width'),
            'height' => $request->input('d-d-height')
        ];

        // Handle image upload
        $imageName = 'default.png';
        if ($request->hasFile('image')) {
            $imageName = $request->file('image')->store('layouts', 'public');
            $imageName = basename($imageName);
        }

        try {
            RoomLayout::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'layout_dimensions' => $layoutDimensions,
                'door_dimensions' => $doorDimensions,
                'door_position' => $request->door_position,
                'wall_thickness' => $request->wall_thickness,
                'is_active' => $request->has('is_active'),
                'image' => $imageName,
                'created_by' => auth()->id() ?? 1,
                'updated_by' => auth()->id() ?? 1,
            ]);
            return back()->with('success', 'Room layout created successfully.');
        }
        catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create layout: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomLayout $roomLayout)
    {
    //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomLayout $roomLayout)
    {
    //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomLayout $roomLayout)
    {
        $request->validate([
            'name' => 'required|string|max:45',
            'door_position' => 'required|in:left,right,center',
            'wall_thickness' => 'required|numeric|min:0',
            'r-d-width' => 'required|numeric|min:0',
            'r-d-length' => 'required|numeric|min:0',
            'r-d-height' => 'required|numeric|min:0',
            'd-d-width' => 'required|numeric|min:0',
            'd-d-height' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        // Combine dimension fields into array format for JSON
        $layoutDimensions = [
            'width' => $request->input('r-d-width'),
            'length' => $request->input('r-d-length'),
            'height' => $request->input('r-d-height')
        ];

        $doorDimensions = [
            'width' => $request->input('d-d-width'),
            'height' => $request->input('d-d-height')
        ];

        // Handle image upload
        $imageName = $roomLayout->image;
        if ($request->hasFile('image')) {
            $imageName = basename($request->file('image')->store('layouts', 'public'));
        }

        try {
            $roomLayout->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'layout_dimensions' => $layoutDimensions,
                'door_dimensions' => $doorDimensions,
                'door_position' => $request->door_position,
                'wall_thickness' => $request->wall_thickness,
                'is_active' => $request->has('is_active'),
                'image' => $imageName,
                'updated_by' => auth()->id() ?? 1,
            ]);
            return back()->with('success', 'Room layout updated successfully.');
        }
        catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update layout: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomLayout $roomLayout)
    {
        $roomLayout->delete();
        return back()->with('success', 'Room layout deleted successfully.');
    }
}
