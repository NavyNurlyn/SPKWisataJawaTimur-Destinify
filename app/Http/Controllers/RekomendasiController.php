<?php
// app/Http/Controllers/RekomendasiController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\{Alternatif, Kriteria, Penilaian, UserInput, HasilPerhitungan};

class RekomendasiController extends Controller
{
    public function index()
    {
        // Ambil nilai dari session (hasil pembobotan AHP)
        $cr = session('cr');
        $ahpKonsisten = session('ahp_konsisten', false);

        return view('rekomendasi.index', compact('cr', 'ahpKonsisten'));
    }

    public function hitung(Request $request)
    {
        $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'jenis_wisata' => 'nullable|array',
            'jenis_wisata.*' => 'in:alam,buatan,budaya',
        ]);

        // Validasi: latitude dan longitude harus diisi bersama
        if (($request->filled('latitude') && !$request->filled('longitude')) ||
            (!$request->filled('latitude') && $request->filled('longitude'))
        ) {
            return back()->withErrors(['koordinat' => 'Latitude dan Longitude harus diisi bersama-sama atau keduanya dikosongkan.']);
        }

        $sessionId = session()->getId();
        $useUserLocation = $request->filled('latitude') && $request->filled('longitude');

        // Hapus hasil perhitungan lama untuk session ini
        HasilPerhitungan::where('session_id', $sessionId)->delete();

        // Simpan input user
        UserInput::create([
            'session_id' => $sessionId,
            'latitude' => $request->latitude ?? 0,
            'longitude' => $request->longitude ?? 0,
            'jenis_wisata' => $request->jenis_wisata ? implode(',', $request->jenis_wisata) : null,
        ]);

        // Ambil semua wisata
        $wisatas = Alternatif::all();
        $kriterias = Kriteria::orderBy('kode_kriteria')->get();

        // Validasi data kriteria
        if ($kriterias->isEmpty()) {
            return back()->withErrors(['error' => 'Data kriteria belum tersedia. Silakan tambahkan kriteria terlebih dahulu.']);
        }

        // Validasi data wisata
        if ($wisatas->isEmpty()) {
            return back()->withErrors(['error' => 'Data wisata belum tersedia. Silakan tambahkan data wisata terlebih dahulu.']);
        }

        // Ambil bobot kriteria dari AHP
        $bobotKriteria = $kriterias->pluck('bobot', 'id')->map(function ($value) {
            return (float) $value;
        })->toArray();

        // Hitung jarak untuk setiap wisata
        // Jika user memberikan lokasi, hitung batch lewat OSRM Table API
        $matriks = [];
        $jarakMap = []; // [alternatif_id => jarak_km]

        if ($useUserLocation) {
            // hitung jarak secara batch (1 origin -> many destinations)
            $jarakMap = $this->hitungJarakBatch($request->latitude, $request->longitude, $wisatas);
        }

        foreach ($wisatas as $wisata) {
            // Gunakan jarak dari user atau jarak default
            if ($useUserLocation) {
                $jarak = $jarakMap[$wisata->id] ?? $this->haversineDistanceKm(
                    $request->latitude,
                    $request->longitude,
                    $wisata->latitude,
                    $wisata->longitude
                );
            } else {
                $jarak = $wisata->jarak_default;
            }

            // Simpan jarak
            $matriks[$wisata->id] = [
                'jarak_km' => $jarak,
                'nilai' => []
            ];

            // Ambil nilai kriteria langsung dari tabel alternatif
            foreach ($kriterias as $kriteria) {
                $nilai = 0;

                // Mapping kriteria ke kolom di tabel alternatif
                switch ($kriteria->kode_kriteria) {
                    case 'C1': // Biaya (sudah dinormalisasi di kolom biaya_normalisasi)
                        $nilai = (float) ($wisata->biaya_normalisasi ?? 0);
                        break;
                    case 'C2': // Aksesibilitas
                        $nilai = (float) ($wisata->aksesibilitas ?? 0);
                        break;
                    case 'C3': // Fasilitas
                        $nilai = (float) ($wisata->fasilitas ?? 0);
                        break;
                    case 'C4': // Jarak
                        $nilai = (float) $jarak;
                        break;
                    case 'C5': // Rating
                        $nilai = (float) ($wisata->rating ?? 0);
                        break;
                    default:
                        // Jika ada kriteria lain yang tidak terdefinisi, set nilai 0
                        $nilai = 0;
                        break;
                }

                $matriks[$wisata->id]['nilai'][$kriteria->id] = $nilai;
            }
        }

        // TOPSIS
        $topsisResult = $this->topsis($matriks, $bobotKriteria, $kriterias, $wisatas);

        // Simpan hasil
        foreach ($topsisResult['hasil'] as $wisataId => $data) {
            HasilPerhitungan::create([
                'session_id' => $sessionId,
                'alternatif_id' => $wisataId,
                'jarak_km' => $matriks[$wisataId]['jarak_km'],
                'nilai_preferensi' => $data['nilai_preferensi'],
                'ranking' => $data['ranking'],
            ]);
        }

        // Ambil hasil sesuai filter jenis wisata
        $query = HasilPerhitungan::with('alternatif')
            ->where('session_id', $sessionId)
            ->orderBy('ranking', 'asc');

        if ($request->jenis_wisata) {
            $query->whereHas('alternatif', function ($q) use ($request) {
                $q->whereIn('jenis_wisata', $request->jenis_wisata);
            });
        }

        $hasilRanking = $query->get();

        // Data untuk tab perhitungan TOPSIS
        $topsisData = [
            'alternatifs' => $wisatas,
            'kriterias' => $kriterias,
            'matriksKeputusan' => $topsisResult['matriksKeputusan'],
            'normalisasi' => $topsisResult['normalisasi'],
            'terbobot' => $topsisResult['terbobot'],
            'idealPos' => $topsisResult['idealPositif'],
            'idealNeg' => $topsisResult['idealNegatif'],
            'distPos' => $topsisResult['dPositif'],
            'distNeg' => $topsisResult['dNegatif'],
            'preferensi' => $topsisResult['preferensi'],
            'hasilRanking' => $topsisResult['hasilRankingDetail'],
            'bobot' => $bobotKriteria
        ];

        // === DETAIL JARAK & NILAI PREFERENSI URUT KODE ALTERNATIF ===
        $detailJarakPreferensi = [];

        foreach ($wisatas->sortBy('id') as $w) {
            $id = $w->id;

            $detailJarakPreferensi[] = [
                'kode_alternatif' => $w->kode_alternatif,
                'd_plus' => $topsisResult['dPositif'][$id] ?? 0,
                'd_minus' => $topsisResult['dNegatif'][$id] ?? 0,
                'nilai_preferensi' => $topsisResult['preferensi'][$id] ?? 0,
            ];
        }

        return view('rekomendasi.hasil', [
            'hasilRanking' => $hasilRanking,
            'useUserLocation' => $useUserLocation,
            'topsisData' => $topsisData,
            'detailJarakPreferensi' => $detailJarakPreferensi,
        ]);
    }

    /**
     * Hitung jarak dari 1 titik (origin) ke banyak destinasi menggunakan OSRM Table API.
     * Mengembalikan associative array: [alternatif_id => jarak_km]
     *
     * Jika ada kegagalan pada chunk, fallback ke Haversine untuk destinasi tersebut.
     */
    private function hitungJarakBatch($latOrigin, $lonOrigin, $wisatas)
    {
        $results = []; // alternatif_id => jarak_km

        // Konfigurasi chunk: berapa destinasi per request.
        // Catatan: OSRM public server memiliki batas URL length; kalau banyak titik, pecah menjadi chunk.
        $CHUNK_SIZE = 80; // aman: 1 origin + 80 destinasi per request (ubah jika perlu)

        // Buat array dari objek wisata supaya mudah chunking
        $wisataArray = $wisatas->values()->all();
        $total = count($wisataArray);

        for ($offset = 0; $offset < $total; $offset += $CHUNK_SIZE) {
            $chunk = array_slice($wisataArray, $offset, $CHUNK_SIZE);

            // Build coords: origin pertama, lalu destinasi chunk
            // OSRM expects lon,lat pairs
            $coordsParts = [];
            $coordsParts[] = "{$lonOrigin},{$latOrigin}"; // index 0 -> source

            $destIds = []; // to map returned distances to alternatif ids
            foreach ($chunk as $i => $w) {
                $coordsParts[] = "{$w->longitude},{$w->latitude}";
                $destIds[] = $w->id;
            }

            $coords = implode(';', $coordsParts);

            // destinations indices are 1..n (because source is index 0)
            $destIndices = [];
            for ($i = 1; $i <= count($chunk); $i++) {
                $destIndices[] = $i;
            }
            $destParam = implode(';', $destIndices);

            try {
                $response = Http::timeout(30)
                    ->retry(2, 200)
                    ->get("https://router.project-osrm.org/table/v1/driving/{$coords}", [
                        'sources' => 0,
                        'destinations' => $destParam,
                        'annotations' => 'distance' // kita ingin jarak
                    ]);

                if ($response->successful()) {
                    $data = $response->json();

                    // OSRM mengembalikan matrix distances, di mana distances[0] adalah jarak dari source ke semua destinasi
                    if (isset($data['distances'][0]) && is_array($data['distances'][0])) {
                        $distancesMeters = $data['distances'][0];

                        // Map distances ke alternatif_id
                        foreach ($distancesMeters as $idx => $distMeters) {
                            // jika null atau tidak tersedia, fallback nanti
                            $altId = $destIds[$idx] ?? null;
                            if ($altId === null) {
                                continue;
                            }
                            if (is_numeric($distMeters)) {
                                $results[$altId] = round($distMeters / 1000, 2);
                            } else {
                                // jika null - gunakan haversine fallback
                                $w = $chunk[$idx];
                                $results[$altId] = $this->haversineDistanceKm($latOrigin, $lonOrigin, $w->latitude, $w->longitude);
                            }
                        }
                        continue; // lanjut ke chunk berikutnya
                    }
                }

                // jika response tidak sukses atau format tak terduga -> fallback untuk semua destinasi di chunk
                foreach ($chunk as $w) {
                    $results[$w->id] = $this->haversineDistanceKm($latOrigin, $lonOrigin, $w->latitude, $w->longitude);
                }
            } catch (\Throwable $e) {
                // fallback per destinasi di chunk
                foreach ($chunk as $w) {
                    $results[$w->id] = $this->haversineDistanceKm($latOrigin, $lonOrigin, $w->latitude, $w->longitude);
                }
            }
        }

        return $results;
    }

    /**
     * Haversine (fallback)
     */
    private function haversineDistanceKm($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round($earthRadius * $c, 2);
    }

    // ----- METHOD TOPSIS (tidak berubah) -----
    private function topsis($matriks, $bobotKriteria, $kriterias, $alternatifs)
    {
        // Buat matriks keputusan
        $matriksKeputusan = [];
        foreach ($matriks as $wisataId => $data) {
            $matriksKeputusan[$wisataId] = $data['nilai'];
        }

        // 1. Normalisasi
        $normalisasi = [];
        foreach ($kriterias as $kriteria) {
            $sumKuadrat = 0;
            foreach ($matriks as $nilai) {
                $sumKuadrat += pow($nilai['nilai'][$kriteria->id], 2);
            }
            $pembagi = sqrt($sumKuadrat);

            foreach ($matriks as $wisataId => $nilai) {
                $normalisasi[$wisataId][$kriteria->id] =
                    $pembagi > 0 ? $nilai['nilai'][$kriteria->id] / $pembagi : 0;
            }
        }

        // 2. Matriks Terbobot
        $terbobot = [];
        foreach ($normalisasi as $wisataId => $nilai) {
            foreach ($kriterias as $kriteria) {
                $terbobot[$wisataId][$kriteria->id] =
                    $nilai[$kriteria->id] * ($bobotKriteria[$kriteria->id] ?? 0);
            }
        }

        // 3. Solusi Ideal
        $idealPositif = [];
        $idealNegatif = [];

        foreach ($kriterias as $kriteria) {
            $values = array_map(fn($x) => $x[$kriteria->id], $terbobot);

            if (empty($values)) {
                $idealPositif[$kriteria->id] = 0;
                $idealNegatif[$kriteria->id] = 0;
                continue;
            }

            if ($kriteria->atribut === 'Benefit') {
                $idealPositif[$kriteria->id] = max($values);
                $idealNegatif[$kriteria->id] = min($values);
            } else {
                $idealPositif[$kriteria->id] = min($values);
                $idealNegatif[$kriteria->id] = max($values);
            }
        }

        // 4. Jarak ke Solusi Ideal
        $dPositif = [];
        $dNegatif = [];
        foreach ($terbobot as $wisataId => $nilai) {
            $sumPos = $sumNeg = 0;
            foreach ($kriterias as $kriteria) {
                $sumPos += pow($nilai[$kriteria->id] - $idealPositif[$kriteria->id], 2);
                $sumNeg += pow($nilai[$kriteria->id] - $idealNegatif[$kriteria->id], 2);
            }
            $dPositif[$wisataId] = sqrt($sumPos);
            $dNegatif[$wisataId] = sqrt($sumNeg);
        }

        // 5. Nilai Preferensi
        $preferensi = [];
        foreach ($dPositif as $wisataId => $dPos) {
            $denom = $dPos + $dNegatif[$wisataId];
            $preferensi[$wisataId] = $denom > 0 ? $dNegatif[$wisataId] / $denom : 0;
        }

        // 6. Ranking
        arsort($preferensi);
        $ranking = 1;
        $hasil = [];
        $hasilRankingDetail = [];

        foreach ($preferensi as $wisataId => $nilai) {
            $alt = $alternatifs->firstWhere('id', $wisataId);

            $hasil[$wisataId] = [
                'nilai_preferensi' => $nilai,
                'ranking' => $ranking
            ];

            $hasilRankingDetail[] = [
                'ranking' => $ranking,
                'kode_alternatif' => $alt->kode_alternatif ?? '-',
                'nama_alternatif' => $alt->nama_wisata ?? '-',
                'jenis_wisata' => $alt->jenis_wisata ?? '-',
                'nilai_preferensi' => $nilai,
            ];

            $ranking++;
        }

        return [
            'matriksKeputusan' => $matriksKeputusan,
            'normalisasi' => $normalisasi,
            'terbobot' => $terbobot,
            'idealPositif' => $idealPositif,
            'idealNegatif' => $idealNegatif,
            'dPositif' => $dPositif,
            'dNegatif' => $dNegatif,
            'preferensi' => $preferensi,
            'hasil' => $hasil,
            'hasilRankingDetail' => $hasilRankingDetail
        ];
    }
}
