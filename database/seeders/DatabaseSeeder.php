<?php

namespace Database\Seeders;

use App\Models\CertaintyFactor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Gejala;
use App\Models\Penyakit;
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
            'no_telp_user' => '0123456789123',
            'gender_user' => 'Wanita',
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
            'no_telp_user' => '098765321098',
            'gender_user' => 'Pria',
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
            'no_telp_user' => '456123789357',
            'gender_user' => 'Pria',
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
        // $rand1 = Str::random(7);
        // $tgl_lahir = $user3->tgl_lahir_user;
        // $format_tgl_lahir = date('ymd', strtotime($tgl_lahir));
        // $rand2 = Str::random(5);

        // $currentId = User::max('id') + 1;
        // $padId = str_pad($currentId, 3, '0', STR_PAD_LEFT); //id user saat ini

        // $unique_id = $rand1 . $format_tgl_lahir . $rand2 . $padId;
        // $user3->id_unique_user = $unique_id;
        // $user3->save();

        Gejala::create([
            'nama_gejala' => 'Mual Muntah',
            ],
        );

        Gejala::create([
            'nama_gejala' => 'Perut Kembung',
            ],
        );
        
        Gejala::create([
            'nama_gejala' => 'Rasa Pahit di Lidah',
            ],
        );
        
        Gejala::create([
            'nama_gejala' => 'Kesulitan Menelan',
            ],
        );
        
        Gejala::create([
            'nama_gejala' => 'Nyeri Perut',
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
            'nama_gejala' => 'Nyeri Punggung Bagian Atas',
            ],
        );
        
        Gejala::create([
            'nama_gejala' => 'Rasa Sakit Semakin Parah Ketika Baring',
            ],
        );
        
        Gejala::create([
            'nama_gejala' => 'Batuk Kering',
            ],
        );
        
        Gejala::create([
            'nama_gejala' => 'Diare',
            ],
        );
        
        Gejala::create([
            'nama_gejala' => 'Keringat Dingin',
            ],
        );
        
        Gejala::create([
            'nama_gejala' => 'Nyeri Otot',
            ],
        );
        
        Gejala::create([
            'nama_gejala' => 'Kadar Gula Tidak Terkontrol',
            ],
        );

        Gejala::create([
            'nama_gejala' => 'Perasaan Kenyang Dalam Waktu Yang Lama',
            ],
        );

        Gejala::create([
            'nama_gejala' => 'Perubahan Berat Badan',
            ],
        );

        Gejala::create([
            'nama_gejala' => 'Sakit Kepala',
            ],
        );

        Gejala::create([
            'nama_gejala' => 'Demam',
            ],
        );

        Gejala::create([
            'nama_gejala' => 'Memiliki Maag',
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
            'nama_penyakit' => 'Gastritis',
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

        Penyakit::create([
            'nama_penyakit' => 'Tukak Lambung',
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
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '2',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '3',
            'certainty_factor' => '0.8',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '4',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '5',
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '6',
            'certainty_factor' => '0.8',
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
            'certainty_factor' => '0.8',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '10',
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '11',
            'certainty_factor' => '0.4',
        ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '1',
        //     'id_gejala' => '12',
        //     'certainty_factor' => '-0.4',
        // ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '13',
            'certainty_factor' => '0.8',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '14',
            'certainty_factor' => '0.4',
        ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '1',
        //     'id_gejala' => '15',
        //     'certainty_factor' => '-0.4',
        // ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '16',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '17',
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '18',
            'certainty_factor' => '0.2',
        ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '1',
        //     'id_gejala' => '19',
        //     'certainty_factor' => '-0.6',
        // ]);

        CertaintyFactor::create([
            'id_penyakit' => '1',
            'id_gejala' => '20',
            'certainty_factor' => '0.4',
        ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '1',
        //     'id_gejala' => '21',
        //     'certainty_factor' => '-0.4',
        // ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '1',
        //     'id_gejala' => '22',
        //     'certainty_factor' => '-0.8',
        // ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '1',
        //     'id_gejala' => '23',
        //     'certainty_factor' => '-0.8',
        // ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '1',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '2',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '3',
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '4',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '5',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '6',
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '7',
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '8',
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '9',
            'certainty_factor' => '0.2',
        ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '2',
        //     'id_gejala' => '10',
        //     'certainty_factor' => '-0.2',
        // ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '2',
        //     'id_gejala' => '11',
        //     'certainty_factor' => '-0.2',
        // ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '2',
        //     'id_gejala' => '12',
        //     'certainty_factor' => '-0.4',
        // ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '13',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '14',
            'certainty_factor' => '0.4',
        ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '2',
        //     'id_gejala' => '15',
        //     'certainty_factor' => '-0.2',
        // ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '16',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '17',
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '18',
            'certainty_factor' => '0.2',
        ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '2',
        //     'id_gejala' => '19',
        //     'certainty_factor' => '-0.4',
        // ]);

        CertaintyFactor::create([
            'id_penyakit' => '2',
            'id_gejala' => '20',
            'certainty_factor' => '0.6',
        ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '2',
        //     'id_gejala' => '21',
        //     'certainty_factor' => '-0.4',
        // ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '2',
        //     'id_gejala' => '22',
        //     'certainty_factor' => '-0.4',
        // ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '2',
        //     'id_gejala' => '23',
        //     'certainty_factor' => '-0.4',
        // ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '1',
            'certainty_factor' => '0.8',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '2',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '3',
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '4',
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '5',
            'certainty_factor' => '0.6',
        ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '3',
        //     'id_gejala' => '6',
        //     'certainty_factor' => '-0.2',
        // ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '3',
        //     'id_gejala' => '7',
        //     'certainty_factor' => '-0.2',
        // ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '8',
            'certainty_factor' => '0.4',
        ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '3',
        //     'id_gejala' => '9',
        //     'certainty_factor' => '-0.2',
        // ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '3',
        //     'id_gejala' => '10',
        //     'certainty_factor' => '-0.2',
        // ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '3',
        //     'id_gejala' => '11',
        //     'certainty_factor' => '-0.4',
        // ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '12',
            'certainty_factor' => '0.8',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '13',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '14',
            'certainty_factor' => '0.4',
        ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '3',
        //     'id_gejala' => '15',
        //     'certainty_factor' => '-0.2',
        // ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '3',
        //     'id_gejala' => '16',
        //     'certainty_factor' => '-0.2',
        // ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '17',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '18',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '19',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '20',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '21',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '22',
            'certainty_factor' => '0.8',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '3',
            'id_gejala' => '23',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '1',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '2',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '3',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '4',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '5',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '6',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '7',
            'certainty_factor' => '0.8',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '8',
            'certainty_factor' => '0.8',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '9',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '10',
            'certainty_factor' => '0.8',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '11',
            'certainty_factor' => '0.2',
        ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '4',
        //     'id_gejala' => '12',
        //     'certainty_factor' => '-0.2',
        // ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '13',
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '14',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '15',
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '16',
            'certainty_factor' => '0.8',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '17',
            'certainty_factor' => '0.2',
        ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '4',
        //     'id_gejala' => '18',
        //     'certainty_factor' => '-0.2',
        // ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '4',
        //     'id_gejala' => '19',
        //     'certainty_factor' => '-0.2',
        // ]);

        CertaintyFactor::create([
            'id_penyakit' => '4',
            'id_gejala' => '20',
            'certainty_factor' => '0.2',
        ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '4',
        //     'id_gejala' => '21',
        //     'certainty_factor' => '-0.2',
        // ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '4',
        //     'id_gejala' => '22',
        //     'certainty_factor' => '-0.4',
        // ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '4',
        //     'id_gejala' => '23',
        //     'certainty_factor' => '-0.4',
        // ]);

        CertaintyFactor::create([
            'id_penyakit' => '5',
            'id_gejala' => '1',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '5',
            'id_gejala' => '2',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '5',
            'id_gejala' => '3',
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '5',
            'id_gejala' => '4',
            'certainty_factor' => '0.2',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '5',
            'id_gejala' => '5',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '5',
            'id_gejala' => '6',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '5',
            'id_gejala' => '7',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '5',
            'id_gejala' => '8',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '5',
            'id_gejala' => '9',
            'certainty_factor' => '0.4',
        ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '5',
        //     'id_gejala' => '10',
        //     'certainty_factor' => '-0.2',
        // ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '5',
        //     'id_gejala' => '11',
        //     'certainty_factor' => '-0.2',
        // ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '5',
        //     'id_gejala' => '12',
        //     'certainty_factor' => '-0.2',
        // ]);

        CertaintyFactor::create([
            'id_penyakit' => '5',
            'id_gejala' => '13',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '5',
            'id_gejala' => '14',
            'certainty_factor' => '0.4',
        ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '5',
        //     'id_gejala' => '15',
        //     'certainty_factor' => '-0.2',
        // ]);

        CertaintyFactor::create([
            'id_penyakit' => '5',
            'id_gejala' => '16',
            'certainty_factor' => '0.4',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '5',
            'id_gejala' => '17',
            'certainty_factor' => '0.4',
        ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '5',
        //     'id_gejala' => '18',
        //     'certainty_factor' => '-0.2',
        // ]);

        // CertaintyFactor::create([
        //     'id_penyakit' => '5',
        //     'id_gejala' => '19',
        //     'certainty_factor' => '-0.2',
        // ]);

        CertaintyFactor::create([
            'id_penyakit' => '5',
            'id_gejala' => '20',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '5',
            'id_gejala' => '21',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '5',
            'id_gejala' => '22',
            'certainty_factor' => '0.6',
        ]);

        CertaintyFactor::create([
            'id_penyakit' => '5',
            'id_gejala' => '23',
            'certainty_factor' => '0.6',
        ]);
    }
}
