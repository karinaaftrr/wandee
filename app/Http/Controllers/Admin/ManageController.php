<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;

class ManageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $destinations = Destination::orderBy('id')->get();

        return view('admin.manage', compact('user', 'destinations'));
    }

    public function edit($id)
    {
        $user = auth()->user();
        $data = Destination::findOrFail($id);

        return view('admin.edit_destination', compact('user', 'data'));
    }

    public function update(Request $request, $id)
    {
        $destination = Destination::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'price' => ['required', 'string'],
            'quota' => ['required', 'integer', 'min:0'],
            'trip_date' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'itinerary' => ['nullable', 'string'],
            'facilities' => ['nullable', 'string'],
        ]);

        $destination->update($validated);

        return redirect()->route('admin.manage')->with('success', 'Destinasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $destination = Destination::findOrFail($id);
        $destination->delete();

        return redirect()->route('admin.manage')->with('success', 'Destinasi berhasil dihapus.');
    }
}
