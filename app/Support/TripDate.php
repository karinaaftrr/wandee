<?php

namespace App\Support;

use Carbon\Carbon;

/**
 * Helper untuk kolom destinations.trip_date.
 *
 * Admin memilih tanggal lewat kalender (tanggal mulai & selesai), lalu
 * helper ini menyusunnya menjadi teks seragam yang dibaca halaman user,
 * misalnya "01 Mei - 03 Mei 2026" atau "12 Juni 2026".
 */
class TripDate
{
    private const MONTH_NAMES = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    /** Tiga huruf awal nama bulan (Indonesia/Inggris) => nomor bulan. */
    private const MONTH_PREFIX = [
        'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4, 'mei' => 5, 'may' => 5,
        'jun' => 6, 'jul' => 7, 'agu' => 8, 'agt' => 8, 'aug' => 8, 'sep' => 9,
        'okt' => 10, 'oct' => 10, 'nov' => 11, 'des' => 12, 'dec' => 12,
    ];

    /**
     * Susun teks tanggal dari dua tanggal berformat Y-m-d.
     * Tanggal selesai boleh kosong (trip satu hari).
     */
    public static function format(?string $start, ?string $end = null): ?string
    {
        if (empty($start)) {
            return null;
        }

        $from = Carbon::parse($start)->startOfDay();
        $to = empty($end) ? $from->copy() : Carbon::parse($end)->startOfDay();

        if ($to->lt($from)) {
            [$from, $to] = [$to, $from];
        }

        if ($from->equalTo($to)) {
            return self::label($from, true);
        }

        if ($from->year === $to->year) {
            return self::label($from, false) . ' - ' . self::label($to, true);
        }

        return self::label($from, true) . ' - ' . self::label($to, true);
    }

    /**
     * Baca teks tanggal tersimpan menjadi dua tanggal (Y-m-d) untuk diisikan
     * kembali ke form edit. Mengembalikan null kalau teks tidak terbaca.
     *
     * @return array{start: ?string, end: ?string}
     */
    public static function parse(?string $text): array
    {
        $empty = ['start' => null, 'end' => null];

        $text = trim((string) $text);
        if ($text === '') {
            return $empty;
        }

        $parts = preg_split('/\s+(?:s\/d|sd|sampai)\s+|\s*[-–—]\s*/iu', $text);
        if ($parts === false || count($parts) < 1 || count($parts) > 2) {
            return $empty;
        }

        $tokens = [];
        foreach ($parts as $part) {
            if (!preg_match('/^(\d{1,2})(?:\s+([A-Za-z]+))?(?:\s+(\d{4}))?$/', trim($part), $m)) {
                return $empty;
            }

            $month = null;
            if (isset($m[2]) && $m[2] !== '') {
                $month = self::monthNumber($m[2]);
                if ($month === null) {
                    return $empty;
                }
            }

            $tokens[] = [
                'day'   => (int) $m[1],
                'month' => $month,
                'year'  => (isset($m[3]) && $m[3] !== '') ? (int) $m[3] : null,
            ];
        }

        $startToken = $tokens[0];
        $endToken = $tokens[1] ?? $tokens[0];
        $startInferred = false;

        $endMonth = $endToken['month'];
        $endYear = $endToken['year'];

        if (count($tokens) === 2) {
            // "30 Des 2025 - 02 Jan" tanpa tahun akhir: tahun akhir = tahun awal + 1
            if ($endYear === null && $startToken['year'] !== null) {
                $endYear = $startToken['year'];
                if ($endMonth !== null && $startToken['month'] !== null && $endMonth < $startToken['month']) {
                    $endYear++;
                }
            }

            $startMonth = $startToken['month'] ?? $endMonth;
            $startYear = $startToken['year'];
            if ($startYear === null) {
                $startYear = $endYear;
                $startInferred = true;
            }
        } else {
            $startMonth = $startToken['month'];
            $startYear = $startToken['year'];
        }

        if ($startMonth === null || $startYear === null || $endMonth === null || $endYear === null) {
            return $empty;
        }

        if (!checkdate($startMonth, $startToken['day'], $startYear)
            || !checkdate($endMonth, $endToken['day'], $endYear)) {
            return $empty;
        }

        $start = sprintf('%04d-%02d-%02d', $startYear, $startMonth, $startToken['day']);
        $end = sprintf('%04d-%02d-%02d', $endYear, $endMonth, $endToken['day']);

        // "28 Des - 02 Jan 2026": tahun awal ikut tahun akhir, jadi mundur satu tahun
        if ($startInferred && $start > $end) {
            $start = sprintf('%04d-%02d-%02d', $startYear - 1, $startMonth, $startToken['day']);
        }

        return ['start' => $start, 'end' => $end];
    }

    /**
     * Jumlah hari trip. Dipakai halaman detail untuk menentukan jumlah hari itinerary.
     */
    public static function days(?string $text): int
    {
        $range = self::parse($text);

        if ($range['start'] && $range['end']) {
            $diff = Carbon::parse($range['start'])->diffInDays(Carbon::parse($range['end']));

            return max(1, (int) round(abs($diff)) + 1);
        }

        // Teks lama yang tidak terbaca: pakai cara lama (selisih angka hari)
        if (!empty($text) && preg_match('/(\d+)\s+\w+\s*-\s*(\d+)/', $text, $m)) {
            return max(1, abs((int) $m[2] - (int) $m[1]) + 1);
        }

        return 1;
    }

    private static function label(Carbon $date, bool $withYear): string
    {
        $label = sprintf('%02d %s', $date->day, self::MONTH_NAMES[$date->month]);

        return $withYear ? $label . ' ' . $date->year : $label;
    }

    private static function monthNumber(string $name): ?int
    {
        return self::MONTH_PREFIX[strtolower(substr($name, 0, 3))] ?? null;
    }
}