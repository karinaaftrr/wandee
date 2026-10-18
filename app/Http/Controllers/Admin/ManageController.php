<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Support\TripDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $destinations = Destination::orderBy('id')->get();

        return view('admin.manage', compact('user', 'destinations'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateDestination($request, true);

        $data = $this->buildData($request, $validated);
        $data['image'] = $this->uploadImage($request);
        $data['rating'] = 0;

        Destination::create($data);

        return redirect()->route('admin.manage')->with('success', 'Destinasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = auth()->user();
        $data = Destination::findOrFail($id);

        $itinerary = json_decode($data->itinerary ?? '', true);
        $itinerary = is_array($itinerary) ? $itinerary : [];

        $tripDates = TripDate::parse($data->trip_date);

        return view('admin.edit_destination', compact('user', 'data', 'itinerary', 'tripDates'));
    }

    public function update(Request $request, $id)
    {
        $destination = Destination::findOrFail($id);

        $validated = $this->validateDestination($request, false);

        $data = $this->buildData($request, $validated);
        $data['rating'] = $this->parseRating($request->input('rating', $destination->rating));

        // Kalau tanggal tidak diisi ulang, pertahankan tanggal yang sudah tersimpan
        if (empty($validated['trip_start'])) {
            $data['trip_date'] = $destination->trip_date;
        }

        if ($request->hasFile('image')) {
            $this->deleteUploadedImage($destination->image);
            $data['image'] = $this->uploadImage($request);
        }

        $destination->update($data);

        return redirect()->route('admin.manage')->with('success', 'Destinasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $destination = Destination::findOrFail($id);

        DB::table('log_hapus_destinasi')->insert([
            'dest_id'    => $destination->id,
            'judul'      => $destination->title,
            'lokasi'     => $destination->location,
            'kategori'   => $destination->category,
            'harga'      => $destination->price,
            'rating'     => $destination->rating,
            'dihapus_at' => now(),
        ]);

        $this->deleteUploadedImage($destination->image);
        $destination->delete();

        return redirect()->route('admin.manage')->with('success', 'Destinasi berhasil dihapus.');
    }

    /* ------------------------------------------------------------------ */

    protected function validateDestination(Request $request, bool $imageRequired): array
    {
        return $request->validate([
            'title'                 => ['required', 'string', 'max:255'],
            'location'              => ['required', 'string', 'max:255'],
            'category'              => ['required', 'in:Gunung,Pantai,Air Terjun,Kota'],
            'price'                 => ['required', 'numeric', 'min:0'],
            'trip_start'            => ['nullable', 'date', 'required_with:trip_end'],
            'trip_end'              => ['nullable', 'date', 'after_or_equal:trip_start'],
            'quota'                 => ['required', 'integer', 'min:0'],
            'facilities'            => ['nullable', 'string'],
            'description'           => ['nullable', 'string'],
            'rating'                => ['nullable'],
            'itinerary'             => ['nullable', 'array'],
            'itinerary.*.title'     => ['nullable', 'string', 'max:255'],
            'itinerary.*.time'      => ['nullable', 'string', 'max:100'],
            'itinerary.*.desc'      => ['nullable', 'string'],
            'image'                 => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ], [
            'image.required' => 'Gambar wajib diupload.',
            'image.image'    => 'File harus berupa gambar.',
            'image.mimes'    => 'Format gambar harus JPG, JPEG, atau PNG.',
            'image.max'      => 'Ukuran gambar maksimal 5 MB.',
            'price.numeric'  => 'Harga harus berupa angka, contoh: 1500000.',
            'trip_start.date'            => 'Tanggal mulai trip tidak valid.',
            'trip_start.required_with'   => 'Isi tanggal mulai trip terlebih dahulu.',
            'trip_end.date'              => 'Tanggal selesai trip tidak valid.',
            'trip_end.after_or_equal'    => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);
    }

    protected function buildData(Request $request, array $validated): array
    {
        return [
            'title'       => $validated['title'],
            'location'    => $validated['location'],
            'category'    => $validated['category'],
            'price'       => (string) (int) round((float) $validated['price']),
            'trip_date'   => TripDate::format($validated['trip_start'] ?? null, $validated['trip_end'] ?? null),
            'quota'       => (int) $validated['quota'],
            'facilities'  => $validated['facilities'] ?? null,
            'description' => $validated['description'] ?? null,
            'itinerary'   => $this->buildItinerary($request->input('itinerary', [])),
        ];
    }

    /**
     * Ubah input form "Hari ke-N" menjadi JSON yang dibaca halaman detail user:
     * [{label, title, desc, time}, ...]
     */
    protected function buildItinerary($rows): ?string
    {
        $result = [];

        foreach (array_values((array) $rows) as $row) {
            $title = trim($row['title'] ?? '');
            $time  = trim($row['time'] ?? '');
            $desc  = trim($row['desc'] ?? '');

            if ($title === '' && $time === '' && $desc === '') {
                continue;
            }

            $result[] = [
                'label' => 'Hari ke-' . (count($result) + 1),
                'title' => $title,
                'desc'  => $desc,
                'time'  => $time,
            ];
        }

        return $result ? json_encode($result, JSON_UNESCAPED_UNICODE) : null;
    }

    protected function parseRating($value): float
    {
        $rating = (float) str_replace(',', '.', (string) $value);

        return max(0, min(5, round($rating, 1)));
    }

    protected function uploadImage(Request $request): string
    {
        $file = $request->file('image');
        $name = 'dest_' . time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
        $file->move(public_path('assets/img'), $name);

        return $name;
    }

    /** Hanya hapus gambar hasil upload admin (prefix dest_), gambar bawaan seeder aman. */
    protected function deleteUploadedImage(?string $image): void
    {
        if ($image && str_starts_with($image, 'dest_')) {
            $path = public_path('assets/img/' . $image);
            if (is_file($path)) {
                @unlink($path);
            }
        }
    }
}