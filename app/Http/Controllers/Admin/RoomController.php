<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller {
    public function index() {
        $rooms = Room::with('roomtype')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create() {
        $types = RoomType::all();
        return view('admin.rooms.create', compact('types'));
    }

    public function store(Request $request) {
        $request->validate([
            'room_type_id' => ['required', 'exists:room_types,id'],
            'total_room' => ['required', 'numeric'],
            'no_beds' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'desc' => ['required', 'string'],
            'image' => ['required', 'image', 'max:2048'],
        ]);

        $imagePath = $request->file('image')->store('images/rooms', 'public');

        Room::create([
            'room_type_id' => $request->room_type_id,
            'total_room' => $request->total_room,
            'no_beds' => $request->no_beds,
            'price' => $request->price,
            'desc' => $request->desc,
            'image' => $imagePath,
            'status' => $request->has('status') ? 1 : 0
        ]);

        return redirect()->route('admin.rooms.index')->with('message', 'Kamar telah dibuat!');
    }

    public function edit(Room $room) {
        $types = RoomType::all();
        return view('admin.rooms.edit', compact('room', 'types'));
    }

    public function update(Request $request, Room $room) {
        $request->validate([
            'room_type_id' => ['required', 'exists:room_types,id'],
            'total_room' => ['required', 'numeric'],
            'no_beds' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'desc' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $room->update([
            'room_type_id' => $request->room_type_id,
            'total_room' => $request->total_room,
            'no_beds' => $request->no_beds,
            'price' => $request->price,
            'desc' => $request->desc,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        if ($request->hasFile('image')) {
            if ($room->image && Storage::disk('public')->exists($room->image)) {
                Storage::disk('public')->delete($room->image);
            }
            $room->image = $request->file('image')->store('images/rooms', 'public');
            $room->save();
        }

        return redirect()->route('admin.rooms.index')->with('message', 'Kamar telah diperbarui!');
    }

    public function destroy(Room $room) {
        if ($room->image && Storage::disk('public')->exists($room->image)) {
            Storage::disk('public')->delete($room->image);
        }

        $room->delete();
        return redirect()->route('admin.rooms.index')->with('message', 'Kamar telah dihapus!');
    }
}
