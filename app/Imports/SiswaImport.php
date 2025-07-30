<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;

class SiswaImport implements ToModel
{
    public function model(array $row)
    {
        return new Siswa([
            'nama'    => $row[0],
            'email'   => $row[1],
            'notelp'  => $row[2],
            'alamat'  => $row[3],
        ]);
    }
}
