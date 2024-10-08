<?php

namespace Database\Seeders;

use App\Models\CertaintyFactor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Geolokasi;
use App\Models\Obat;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::create([
            'nama_user' => 'Rani',
            'email' => 'rani@gmail.com',
            'password' => bcrypt('123'),
            'tgl_lahir_user' => '1997-02-20',
            'bb_user' => '49 kg',
            'tinggi_user' => '159 cm',
            'no_telp_user' => '0123456789123',
            'gender_user' => 'Wanita',
            'alamat_user' => 'Jl. Perumnas',
            'kota_user' => 'KAB. SLEMAN',
            'provinsi_user' => 'DI YOGYAKARTA',
            'email_verified_at' => Carbon::now(),
            'role_user' => 'user',
        ]);
        $tgl_lahir = $user1->tgl_lahir_user;
        $formattedBirthdate = Carbon::parse($tgl_lahir);

        $now = Carbon::now();
        $years = $formattedBirthdate->diffInYears($now);
        $months = $formattedBirthdate->copy()->addYears($years)->diffInMonths($now);

        $umur = $years . ' tahun ' . $months . ' bulan';

        $user1->umur_user = $umur;
        $user1->save();
        // $rand1 = Str::random(7);
        // $tgl_lahir = $user1->tgl_lahir_user;
        // $format_tgl_lahir = date('ymd', strtotime($tgl_lahir));
        // $rand2 = Str::random(5);

        // $currentId = User::max('id') + 1;
        // $padId = str_pad($currentId, 3, '0', STR_PAD_LEFT); //id user saat ini

        // $unique_id = $rand1 . $format_tgl_lahir . $rand2 . $padId;
        // $user1->id_unique_user = $unique_id;
        // $user1->save();

        $user2 = User::create([
            'nama_user' => 'Remi',
            'email' => 'remi@gmail.com',
            'password' => bcrypt('123'),
            'tgl_lahir_user' => '2000-06-11',
            'bb_user' => '62 kg',
            'tinggi_user' => '162 cm',
            'no_telp_user' => '098765321098',
            'gender_user' => 'Pria',
            'alamat_user' => 'Jl. Seturan',
            'kota_user' => 'Kab. Bantul',
            'provinsi_user' => 'Yogyakarta',
            'email_verified_at' => Carbon::now(),
            'role_user' => 'admin',
        ]);
        $tgl_lahir = $user2->tgl_lahir_user;
        $formattedBirthdate = Carbon::parse($tgl_lahir);

        $now = Carbon::now();
        $years = $formattedBirthdate->diffInYears($now);
        $months = $formattedBirthdate->copy()->addYears($years)->diffInMonths($now);

        $umur = $years . ' tahun ' . $months . ' bulan';

        $user2->umur_user = $umur;
        $user2->save();
        // $rand1 = Str::random(7);
        // $tgl_lahir = $user2->tgl_lahir_user;
        // $format_tgl_lahir = date('ymd', strtotime($tgl_lahir));
        // $rand2 = Str::random(5);

        // $currentId = User::max('id') + 1;
        // $padId = str_pad($currentId, 3, '0', STR_PAD_LEFT); //id user saat ini

        // $unique_id = $rand1 . $format_tgl_lahir . $rand2 . $padId;
        // $user2->id_unique_user = $unique_id;
        // $user2->save();

        $user3 = User::create([
            'nama_user' => 'Shiba',
            'email' => 'shiba@gmail.com',
            'password' => bcrypt('123'),
            'tgl_lahir_user' => '1992-11-01',
            'bb_user' => '72 kg',
            'tinggi_user' => '167 cm',
            'no_telp_user' => '456123789357',
            'gender_user' => 'Pria',
            'alamat_user' => 'Jl. Affandi',
            'kota_user' => 'KAB. SLEMAN',
            'provinsi_user' => 'DI YOGYAKARTA',
            'email_verified_at' => Carbon::now(),
            'role_user' => 'dokter',
        ]);
        $tgl_lahir = $user3->tgl_lahir_user;
        $formattedBirthdate = Carbon::parse($tgl_lahir);

        $now = Carbon::now();
        $years = $formattedBirthdate->diffInYears($now);
        $months = $formattedBirthdate->copy()->addYears($years)->diffInMonths($now);

        $umur = $years . ' tahun ' . $months . ' bulan';

        $user3->umur_user = $umur;
        $user3->save();

        $user4 = User::create([
            'nama_user' => 'Shiro',
            'email' => 'shiro@gmail.com',
            'password' => bcrypt('123'),
            'tgl_lahir_user' => '1987-01-20',
            'bb_user' => '82 kg',
            'tinggi_user' => '184 cm',
            'no_telp_user' => '08118923534',
            'gender_user' => 'Pria',
            'alamat_user' => 'Jl. Seturan',
            'kota_user' => 'KAB. SLEMAN',
            'provinsi_user' => 'DI YOGYAKARTA',
            'email_verified_at' => Carbon::now(),
            'role_user' => 'dokter',
        ]);
        $tgl_lahir = $user4->tgl_lahir_user;
        $formattedBirthdate = Carbon::parse($tgl_lahir);

        $now = Carbon::now();
        $years = $formattedBirthdate->diffInYears($now);
        $months = $formattedBirthdate->copy()->addYears($years)->diffInMonths($now);

        $umur = $years . ' tahun ' . $months . ' bulan';

        $user4->umur_user = $umur;
        $user4->save();

        $user5 = User::create([
            'nama_user' => 'Jett',
            'email' => 'jett@gmail.com',
            'password' => bcrypt('123'),
            'tgl_lahir_user' => '1989-08-31',
            'bb_user' => '50 kg',
            'tinggi_user' => '169 cm',
            'no_telp_user' => '089812874532',
            'gender_user' => 'Wanita',
            'alamat_user' => 'Jl. Selokan Mataram',
            'kota_user' => 'KAB. SLEMAN',
            'provinsi_user' => 'DI YOGYAKARTA',
            'email_verified_at' => Carbon::now(),
            'role_user' => 'dokter',
        ]);
        $tgl_lahir = $user5->tgl_lahir_user;
        $formattedBirthdate = Carbon::parse($tgl_lahir);

        $now = Carbon::now();
        $years = $formattedBirthdate->diffInYears($now);
        $months = $formattedBirthdate->copy()->addYears($years)->diffInMonths($now);

        $umur = $years . ' tahun ' . $months . ' bulan';

        $user5->umur_user = $umur;
        $user5->save();
        // $rand1 = Str::random(7);
        // $tgl_lahir = $user3->tgl_lahir_user;
        // $format_tgl_lahir = date('ymd', strtotime($tgl_lahir));
        // $rand2 = Str::random(5);

        // $currentId = User::max('id') + 1;
        // $padId = str_pad($currentId, 3, '0', STR_PAD_LEFT); //id user saat ini

        // $unique_id = $rand1 . $format_tgl_lahir . $rand2 . $padId;
        // $user3->id_unique_user = $unique_id;
        // $user3->save();

        Geolokasi::create([
            'nama_lokasi' => 'Dokter Atma Jaya ',
            'jenis_lokasi' => 'Dokter Umum',
            'alamat_lokasi' => 'JL. Babarsari No.43, Janti, Caturtunggal, Kec.Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281',
            'lat' => '-7.779433',
            'lng' => '110.415782'
        ]);

        Geolokasi::create([
            'nama_lokasi' => 'Apotek K 24 Babarsari',
            'jenis_lokasi' => 'Apotek',
            'alamat_lokasi' => 'Jl. Babarsari No.13 B, Janti, Caturtunggal, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281',
            'lat' => '-7.7818777',
            'lng' => '110.4141352'
        ]);

        Obat::create([
            'nama_obat' => 'Mylanta Cair',
            'jenis_obat' => 'Antasida',
            'kegunaan_obat' => 'Mengobati sakit maag',
            'harga_obat' => '17000',
        ]);

        Obat::create([
            'nama_obat' => 'Ranitidin',
            'jenis_obat' => 'Antagonis reseptor histamin h2',
            'kegunaan_obat' => 'Mengobati penaykit yang disebabkan kelebihan asam lambung',
            'harga_obat' => '28700',
        ]);

        Obat::create([
            'nama_obat' => 'Lansoprazol',
            'jenis_obat' => 'Proton pump inhibitor',
            'kegunaan_obat' => 'Meredakan gejala akibat peningkatan asam lambung',
            'harga_obat' => '21000',
        ]);

        Gejala::create([
            'nama_gejala' => 'Mual Muntah',
            ],
        );

        Gejala::create([
            'nama_gejala' => 'Perut Kembung',
            ],
        );
        
        Gejala::create([
            'nama_gejala' => 'Kesulitan Menelan',
            ],
        );
        
        Gejala::create([
            'nama_gejala' => 'Nyeri Ulu Hati',
            ],
        );
        
        Gejala::create([
            'nama_gejala' => 'Sensasi Terbakar di Dada',
            ],
        );
        
        Gejala::create([
            'nama_gejala' => 'Cepat Merasa Kenyang',
            ],
        );
        
        Gejala::create([
            'nama_gejala' => 'Penurunan Nafsu Makan',
            ],
        );
        
        Gejala::create([
            'nama_gejala' => 'Nyeri Saat Makan',
            ],
        );
        
        Gejala::create([
            'nama_gejala' => 'Rasa Sakit Semakin Parah Ketika Baring',
            ],
        );
        
        Gejala::create([
            'nama_gejala' => 'Keringat Dingin',
            ],
        );

        Gejala::create([
            'nama_gejala' => 'Nyeri Perut',
            ],
        );

        Gejala::create([
            'nama_gejala' => 'Perasaan Kenyang Dalam Waktu Lama',
            ],
        );

        Gejala::create([
            'nama_gejala' => 'Diare',
            ],
        );

        Gejala::create([
            'nama_gejala' => 'Kadar Gula Tidak Terkontrol',
            ],
        );

        Gejala::create([
            'nama_gejala' => 'Demam',
            ],
        );

        Gejala::create([
            'nama_gejala' => 'Anemia',
            ],
        );

        Gejala::create([
            'nama_gejala' => 'Tinja Berwarna Hitam Atau Merah',
            ],
        );

        Gejala::create([
            'nama_gejala' => 'Muntah Darah',
            ],
        );

        Penyakit::create([
            'nama_penyakit' => 'GERD',
            'deskripsi_penyakit' => 'Naiknya asam lambung ke kerongkongan',
            'gejala_penyakit' => 'Mual muntah, rasa terbakar di rongga dada, mulut terasa pahit',
            'penyebab_penyakit' => 'Asam lambung naik',
            'penyebaran_penyakit' => 'Tersebar di seluruh dunia',
            'cara_pencegahan' => 'Tidak boleh telat makan, jangan terlalu sering makan dan minum pedas',
            'cara_penanganan' => 'Tidak ada',
        ]);

        Penyakit::create([
            'nama_penyakit' => 'Tukak Lambung',
            'deskripsi_penyakit' => 'Naiknya asam lambung ke kerongkongan',
            'gejala_penyakit' => 'Mual muntah, rasa terbakar di rongga dada, mulut terasa pahit',
            'penyebab_penyakit' => 'Asam lambung naik',
            'penyebaran_penyakit' => 'Tersebar di seluruh dunia',
            'cara_pencegahan' => 'Tidak boleh telat makan, jangan terlalu sering makan dan minum pedas',
            'cara_penanganan' => 'Tidak ada',
        ]);

        Penyakit::create([
            'nama_penyakit' => 'Gastroenteritis',
            'deskripsi_penyakit' => 'Naiknya asam lambung ke kerongkongan',
            'gejala_penyakit' => 'Mual muntah, rasa terbakar di rongga dada, mulut terasa pahit',
            'penyebab_penyakit' => 'Asam lambung naik',
            'penyebaran_penyakit' => 'Tersebar di seluruh dunia',
            'cara_pencegahan' => 'Tidak boleh telat makan, jangan terlalu sering makan dan minum pedas',
            'cara_penanganan' => 'Tidak ada',
        ]);

        Penyakit::create([
            'nama_penyakit' => 'Gastroparesis',
            'deskripsi_penyakit' => 'Naiknya asam lambung ke kerongkongan',
            'gejala_penyakit' => 'Mual muntah, rasa terbakar di rongga dada, mulut terasa pahit',
            'penyebab_penyakit' => 'Asam lambung naik',
            'penyebaran_penyakit' => 'Tersebar di seluruh dunia',
            'cara_pencegahan' => 'Tidak boleh telat makan, jangan terlalu sering makan dan minum pedas',
            'cara_penanganan' => 'Tidak ada',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '1',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '2',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '3',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '4',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '5',
            'certainty_factor' => '1',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '6',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '7',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '8',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '9',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '10',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '11',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '12',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '13',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '14',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '15',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '16',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '17',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '18',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '1',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '2',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '3',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '4',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '5',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '6',
            'certainty_factor' => '1',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '7',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '8',
            'certainty_factor' => '1',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '9',
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '10',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '11',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '12',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '13',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '14',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '15',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '16',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '17',
            'certainty_factor' => '1',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '18',
            'certainty_factor' => '1',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '1',
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '2',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '3',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '4',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '5',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '6',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '7',
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '8',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '9',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '10',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '11',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '12',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '13',
            'certainty_factor' => '1',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '14',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '15',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '16',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '17',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '18',
            'certainty_factor' => '0',
        ]);

        
        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '1',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '2',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '3',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '4',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '5',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '6',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '7',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '8',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '9',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '10',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '11',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '12',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '13',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '14',
            'certainty_factor' => '0.8',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '15',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '16',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '17',
            'certainty_factor' => '0',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '18',
            'certainty_factor' => '0',
        ]);
    }
}
