<?php

namespace App\Imports;

use App\Models\RekomendasiBahanMakanan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class RBahanMakananImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $expectedHeaders = ['id_bahan_makanan', 'id_penyakit'];
        foreach ($expectedHeaders as $header) {
            if (!isset($row[$header])) {
                Log::error("Missing key: $header", $row);
                return null; // Skip this row or handle it as needed
            }
        }
        Log::info('Row data', $row);
        return new RekomendasiBahanMakanan([
            'id_bahan_makanan' => $row['id_bahan_makanan'], 
            'id_penyakit' => $row['id_penyakit'], 
        ]);
    }
}
