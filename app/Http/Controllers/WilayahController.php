<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TAResource;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class WilayahController extends Controller
{
    public function getProvinsi()
    {
        // Buat instance GuzzleHTTP client
        $client = new Client([
            'verify' => false, // Mengabaikan verifikasi SSL
        ]);

        try {
            // Lakukan permintaan GET ke API
            $response = $client->request('GET', 'http://sipedas.pertanian.go.id/api/wilayah/list_pro');

            // Dapatkan body dari response
            $data = json_decode($response->getBody(), true);

            // Log response untuk debugging
            Log::info('API Response:', $data);

            // Ambil bagian output dari response, karena tidak ada key output, langsung ambil data
            if (!empty($data)) {
                // Format ulang output menjadi array dengan angka dan nama kabupaten/kota
                $formattedOutput = [];
                foreach ($data as $key => $value) {
                    $formattedOutput[] = [
                        'id_provinsi' => $key,
                        'nama_provinsi' => $value,
                    ];
                }

                return response()->json([
                    'success' => true,
                    'message' => 'List Data Provinsi',
                    'data' => $formattedOutput,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found in API response',
                ], 404);
            }
        } catch (RequestException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data from API',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getKabupatenKota($id_provinsi)
    {
        // Buat instance GuzzleHTTP client
        $client = new Client([
            'verify' => false, // Mengabaikan verifikasi SSL
        ]);

        try {
            // Lakukan permintaan GET ke API dengan menyisipkan $id_provinsi ke dalam URL
            $response = $client->request('GET', "http://sipedas.pertanian.go.id/api/wilayah/list_kab?thn=2024&pro={$id_provinsi}");

            // Dapatkan body dari response
            $data = json_decode($response->getBody(), true);

            // Log response untuk debugging
            Log::info('API Response:', $data);

            // Ambil bagian output dari response, karena tidak ada key output, langsung ambil data
            if (!empty($data)) {
                // Format ulang output menjadi array dengan angka dan nama kabupaten/kota
                $formattedOutput = [];
                foreach ($data as $key => $value) {
                    $formattedOutput[] = [
                        'id_kabkot' => $key,
                        'nama_kabkot' => $value,
                    ];
                }

                return response()->json([
                    'success' => true,
                    'message' => 'List Data Kabupaten/Kota',
                    'data' => $formattedOutput,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found in API response',
                ], 404);
            }
        } catch (RequestException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data from API',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
