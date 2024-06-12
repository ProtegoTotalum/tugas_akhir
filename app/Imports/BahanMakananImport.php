<?php

namespace App\Imports;

use App\Models\BahanMakanan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BahanMakananImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $expectedHeaders = ['nama_bahan_makanan', 'takaran', 'kalori', 'karbohidrat', 'protein_nabati', 'protein_hewani', 'lemak'];
        foreach ($expectedHeaders as $header) {
            if (!isset($row[$header])) {
                Log::error("Missing key: $header", $row);
                return null; // Skip this row or handle it as needed
            }
        }
        Log::info('Row data', $row);
        return new BahanMakanan([
            'nama_bahan_makanan' => $row['nama_bahan_makanan'], 
            'takaran(g)' => $row['takaran'],
            'kalori' => $row['kalori'],
            'karbohidrat' => $row['karbohidrat'],
            'protein_nabati' => $row['protein_nabati'],
            'protein_hewani' => $row['protein_hewani'],
            'lemak' => $row['lemak'],
        ]);
    }
}
