<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\DataBencana;
use App\Models\JenisBencana;
use App\Models\LaporanMasyarakat;
use App\Models\UpdateBencana;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SipdaSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. USERS ──────────────────────────────────────────────
        $admin = User::firstOrCreate(['email' => 'admin@sipda.id'], [
            'full_name' => 'Administrator SIPDA',
            'password'  => Hash::make('Admin123'),
            'role'      => 'admin',
            'is_active' => true,
            'no_telp'   => '081234567890',
        ]);

        $petugas1 = User::firstOrCreate(['email' => 'petugas1@sipda.id'], [
            'full_name' => 'Budi Santoso',
            'password'  => Hash::make('Petugas123'),
            'role'      => 'petugas',
            'is_active' => true,
            'no_telp'   => '081298765432',
        ]);

        $petugas2 = User::firstOrCreate(['email' => 'petugas2@sipda.id'], [
            'full_name' => 'Sari Wulandari',
            'password'  => Hash::make('Petugas123'),
            'role'      => 'petugas',
            'is_active' => true,
            'no_telp'   => '081312345678',
        ]);

        $users = [];
        $usersData = [
            ['full_name' => 'Ahmad Fauzi',       'email' => 'ahmad@gmail.com',   'no_telp' => '08119876543'],
            ['full_name' => 'Dewi Rahayu',        'email' => 'dewi@gmail.com',    'no_telp' => '08221234567'],
            ['full_name' => 'Rudi Hermawan',      'email' => 'rudi@gmail.com',    'no_telp' => '08579876543'],
            ['full_name' => 'Siti Aminah',        'email' => 'siti@gmail.com',    'no_telp' => '08981234567'],
            ['full_name' => 'Eko Prasetyo',       'email' => 'eko@gmail.com',     'no_telp' => '08561234567'],
        ];
        foreach ($usersData as $u) {
            $users[] = User::firstOrCreate(['email' => $u['email']], array_merge($u, [
                'password'  => Hash::make('User1234a'),
                'role'      => 'masyarakat',
                'is_active' => true,
            ]));
        }

        // ── 2. JENIS BENCANA ──────────────────────────────────────
        $jenisList = [
            'Banjir', 'Gempa Bumi', 'Longsor', 'Kebakaran',
            'Angin Kencang', 'Kekeringan', 'Banjir Bandang', 'Tanah Longsor',
        ];
        $jenis = [];
        foreach ($jenisList as $nama) {
            $jenis[$nama] = JenisBencana::firstOrCreate(['nama_bencana' => $nama]);
        }

        // ── 3. DATA BENCANA AKTIF ─────────────────────────────────
        // Koordinat riil di Kota Bandung & sekitarnya
        $bencanaData = [
            // === DARURAT ===
            [
                'jenis'            => 'Banjir',
                'lokasi'           => 'Kelurahan Dayeuhkolot, Kec. Dayeuhkolot',
                'tingkat_status'   => 'Darurat',
                'tanggal_kejadian' => now()->subDays(1),
                'deskripsi'        => 'Banjir bandang melanda kawasan Dayeuhkolot akibat meluapnya Sungai Citarum. Ketinggian air mencapai 1,5 meter. Ratusan warga mengungsi ke tempat yang lebih tinggi. Tim evakuasi BPBD sudah diturunkan.',
                'latitude'         => -6.9975,
                'longitude'        => 107.6314,
            ],
            [
                'jenis'            => 'Longsor',
                'lokasi'           => 'Jl. Pagarsih, Kec. Bojongloa Kaler',
                'tingkat_status'   => 'Darurat',
                'tanggal_kejadian' => now()->subHours(18),
                'deskripsi'        => 'Longsor menimpa pemukiman padat penduduk di lereng bukit Pagarsih. Dua rumah tertimbun material longsoran. Proses evakuasi sedang berlangsung.',
                'latitude'         => -6.9333,
                'longitude'        => 107.5811,
            ],

            // === SIAGA ===
            [
                'jenis'            => 'Kebakaran',
                'lokasi'           => 'Kec. Cicendo, Komp. Pasar Cicendo',
                'tingkat_status'   => 'Siaga',
                'tanggal_kejadian' => now()->subDays(2),
                'deskripsi'        => 'Kebakaran terjadi di kompleks pasar dan pemukiman padat Cicendo. Petugas Damkar dan BPBD sedang berupaya memadamkan api dan mencegah perluasan ke area sekitarnya.',
                'latitude'         => -6.9042,
                'longitude'        => 107.5970,
            ],
            [
                'jenis'            => 'Banjir',
                'lokasi'           => 'Kec. Antapani, Kel. Antapani Tengah',
                'tingkat_status'   => 'Siaga',
                'tanggal_kejadian' => now()->subDays(1),
                'deskripsi'        => 'Banjir menggenangi kawasan Antapani akibat drainase yang tidak mampu menampung curah hujan tinggi. Genangan setinggi 40–80 cm menghambat aktivitas warga.',
                'latitude'         => -6.9108,
                'longitude'        => 107.6648,
            ],
            [
                'jenis'            => 'Tanah Longsor',
                'lokasi'           => 'Kec. Ujungberung, Kel. Pasanggrahan',
                'tingkat_status'   => 'Siaga',
                'tanggal_kejadian' => now()->subDays(3),
                'deskripsi'        => 'Pergerakan tanah terdeteksi di lereng Pasanggrahan. Beberapa retakan muncul di dinding rumah warga. BPBD mengeluarkan imbauan kewaspadaan untuk warga di sekitar lereng.',
                'latitude'         => -6.8922,
                'longitude'        => 107.7028,
            ],

            // === WASPADA ===
            [
                'jenis'            => 'Angin Kencang',
                'lokasi'           => 'Kec. Arcamanik, Kel. Cisaranten Kulon',
                'tingkat_status'   => 'Waspada',
                'tanggal_kejadian' => now()->subDays(2),
                'deskripsi'        => 'Angin kencang dengan kecepatan 60–80 km/jam merobohkan sejumlah pohon dan tiang listrik. BMKG mengeluarkan peringatan dini cuaca ekstrem untuk wilayah Bandung timur.',
                'latitude'         => -6.9141,
                'longitude'        => 107.6833,
            ],
            [
                'jenis'            => 'Banjir Bandang',
                'lokasi'           => 'Kec. Cibiru, Kel. Palasari',
                'tingkat_status'   => 'Waspada',
                'tanggal_kejadian' => now()->subDays(4),
                'deskripsi'        => 'Potensi banjir bandang di kawasan Cibiru meningkat akibat curah hujan tinggi di hulu. Warga dihimbau untuk tidak beraktivitas di sekitar aliran sungai.',
                'latitude'         => -6.9111,
                'longitude'        => 107.7222,
            ],
            [
                'jenis'            => 'Longsor',
                'lokasi'           => 'Kec. Coblong, Kel. Lebak Siliwangi',
                'tingkat_status'   => 'Waspada',
                'tanggal_kejadian' => now()->subDays(5),
                'deskripsi'        => 'Kondisi tanah di kawasan lereng Coblong mulai labil akibat hujan deras yang terus-menerus. Tim geologi BPBD sedang melakukan pemantauan intensif.',
                'latitude'         => -6.8886,
                'longitude'        => 107.6153,
            ],
            [
                'jenis'            => 'Kebakaran',
                'lokasi'           => 'Kec. Sukajadi, Kel. Pasteur',
                'tingkat_status'   => 'Waspada',
                'tanggal_kejadian' => now()->subDays(6),
                'deskripsi'        => 'Kebakaran lahan kering di kawasan Pasteur akibat musim kemarau dan sambaran petir. Api sudah berhasil dikendalikan namun area tetap dalam status waspada.',
                'latitude'         => -6.8961,
                'longitude'        => 107.5869,
            ],

            // === AMAN (tertangani) ===
            [
                'jenis'            => 'Banjir',
                'lokasi'           => 'Kec. Gedebage, Kel. Cisaranten Bina Harapan',
                'tingkat_status'   => 'Aman',
                'tanggal_kejadian' => now()->subDays(10),
                'deskripsi'        => 'Banjir di Gedebage telah berhasil ditangani. Pompa air dipasang permanen dan drainase diperbaiki. Warga sudah kembali ke rumah masing-masing.',
                'latitude'         => -6.9594,
                'longitude'        => 107.6931,
            ],
            [
                'jenis'            => 'Gempa Bumi',
                'lokasi'           => 'Pusat Kota Bandung, Kec. Sumur Bandung',
                'tingkat_status'   => 'Aman',
                'tanggal_kejadian' => now()->subDays(14),
                'deskripsi'        => 'Gempa bumi magnitude 3,2 SR di pusat kota Bandung. Tidak ada kerusakan signifikan. Situasi sudah kembali normal pasca pemantauan selama 72 jam.',
                'latitude'         => -6.9211,
                'longitude'        => 107.6061,
            ],
        ];

        $bencanaRecords = [];
        foreach ($bencanaData as $bd) {
            $jenisId = $jenis[$bd['jenis']]->id ?? null;
            $bencanaRecords[] = DataBencana::create([
                'jenis_id'         => $jenisId,
                'lokasi'           => $bd['lokasi'],
                'tingkat_status'   => $bd['tingkat_status'],
                'tanggal_kejadian' => $bd['tanggal_kejadian'],
                'deskripsi'        => $bd['deskripsi'],
                'latitude'         => $bd['latitude'],
                'longitude'        => $bd['longitude'],
                'admin_id'         => $admin->id,
            ]);
        }

        // ── 4. UPDATE BENCANA (riwayat status) ───────────────────
        UpdateBencana::create([
            'bencana_id'  => $bencanaRecords[0]->id, // Dayeuhkolot banjir
            'status'      => 'Darurat',
            'deskripsi'   => 'Status ditetapkan Darurat. Tim SAR dan BPBD dikerahkan. Warga diminta segera evakuasi mandiri.',
            'petugas_id'  => $petugas1->id,
            'created_at'  => now()->subHours(20),
        ]);
        UpdateBencana::create([
            'bencana_id'  => $bencanaRecords[0]->id,
            'status'      => 'Darurat',
            'deskripsi'   => 'Penambahan 2 unit perahu karet dan 1 unit mobil tangki air bersih dari Dinas Sosial. Total 450 jiwa sudah dievakuasi.',
            'petugas_id'  => $petugas1->id,
            'created_at'  => now()->subHours(10),
        ]);

        UpdateBencana::create([
            'bencana_id'  => $bencanaRecords[2]->id, // Kebakaran Cicendo
            'status'      => 'Siaga',
            'deskripsi'   => 'Api sudah dapat dikuasai 60%. 4 unit Damkar masih bekerja memadamkan titik api yang tersisa.',
            'petugas_id'  => $petugas2->id,
            'created_at'  => now()->subDays(2)->subHours(6),
        ]);

        UpdateBencana::create([
            'bencana_id'  => $bencanaRecords[9]->id, // Gedebage aman
            'status'      => 'Siaga',
            'deskripsi'   => 'Pompa air berhasil dipasang. Genangan mulai surut, dari 120cm turun menjadi 40cm.',
            'petugas_id'  => $petugas1->id,
            'created_at'  => now()->subDays(12),
        ]);
        UpdateBencana::create([
            'bencana_id'  => $bencanaRecords[9]->id,
            'status'      => 'Aman',
            'deskripsi'   => 'Banjir sudah surut sepenuhnya. Warga kembali ke rumah. Perbaikan drainase permanen akan dilaksanakan bulan depan.',
            'petugas_id'  => $petugas2->id,
            'created_at'  => now()->subDays(10),
        ]);

        // ── 5. BERITA ─────────────────────────────────────────────
        $beritaData = [
            [
                'bencana_id' => $bencanaRecords[0]->id,
                'judul'      => 'Banjir Dayeuhkolot: 450 Warga Mengungsi, BPBD Kerahkan Tim Evakuasi',
                'isi'        => 'Banjir besar kembali melanda kawasan Dayeuhkolot, Kabupaten Bandung, akibat meluapnya Sungai Citarum pada Rabu dini hari. Ketinggian air mencapai 1,5 meter di beberapa titik, memaksa ratusan warga mengungsi ke tempat yang lebih aman.

Tim Badan Penanggulangan Bencana Daerah (BPBD) langsung menurunkan personel dan peralatan evakuasi termasuk perahu karet dan ambulans. Kepala BPBD Kota Bandung, Ir. Dadang Sulaeman, menyatakan bahwa pihaknya telah membuka 5 titik pengungsian di masjid, sekolah, dan gedung serbaguna terdekat.

"Prioritas kami saat ini adalah keselamatan jiwa. Kami memastikan seluruh warga mendapatkan makanan, air bersih, dan layanan kesehatan di lokasi pengungsian," ujar Dadang.

Penyebab utama banjir ini adalah curah hujan ekstrem di wilayah hulu DAS Citarum selama 3 hari berturut-turut, ditambah dengan kapasitas drainase yang belum memadai. Pemerintah daerah berencana melakukan normalisasi sungai dan pemasangan pompa air permanen sebagai solusi jangka panjang.

Warga diimbau untuk tidak kembali ke rumah sebelum ada pemberitahuan resmi dari BPBD. Hotline darurat BPBD: (022) 7231929.',
                'status'     => 'published',
            ],
            [
                'bencana_id' => $bencanaRecords[1]->id,
                'judul'      => 'Longsor Bojongloa Kaler: Dua Rumah Tertimbun, Tim SAR Bergerak Cepat',
                'isi'        => 'Longsor yang terjadi di kawasan Jalan Pagarsih, Kecamatan Bojongloa Kaler, Rabu malam, menimbun dua rumah warga. Tim SAR gabungan dari BPBD, TNI, dan Polri segera dikerahkan ke lokasi.

Material tanah bercampur bebatuan meluncur dari lereng bukit akibat tanah yang jenuh air setelah hujan deras berlangsung selama 6 jam. Seluruh penghuni rumah berhasil dievakuasi dengan selamat sebelum longsoran terjadi, berkat peringatan dini dari warga sekitar.

Proses pencarian dan pembersihan material longsoran masih berlangsung. BPBD mengeluarkan imbauan agar warga yang tinggal di kawasan lereng tetap waspada dan memantau kondisi cuaca.',
                'status'     => 'published',
            ],
            [
                'bencana_id' => null,
                'judul'      => 'BMKG Keluarkan Peringatan Dini: Cuaca Ekstrem Ancam Bandung Raya Sepekan ke Depan',
                'isi'        => 'Badan Meteorologi, Klimatologi, dan Geofisika (BMKG) mengeluarkan peringatan dini cuaca ekstrem untuk wilayah Bandung Raya dalam sepekan ke depan. Curah hujan tinggi disertai angin kencang dan petir berpotensi melanda pada sore hingga malam hari.

Kepala Stasiun Klimatologi Jawa Barat, Dr. Hendra Kusuma, menjelaskan bahwa fenomena ini dipicu oleh aktifnya Madden-Julian Oscillation (MJO) yang membawa massa udara lembab dari Samudra Hindia.

BPBD Kota Bandung mengimbau masyarakat untuk:
- Memangkas pohon yang berpotensi tumbang
- Membersihkan saluran drainase di sekitar rumah
- Menghindari beraktivitas di lereng dan tepian sungai
- Menyiapkan perlengkapan darurat di rumah

Informasi cuaca terkini dapat diakses melalui aplikasi Info BMKG atau website resmi bmkg.go.id.',
                'status'     => 'published',
            ],
            [
                'bencana_id' => null,
                'judul'      => 'BPBD Luncurkan Program Desa Tangguh Bencana di 10 Kelurahan Rawan',
                'isi'        => 'BPBD Kota Bandung resmi meluncurkan program Desa Tangguh Bencana (Destana) di 10 kelurahan yang masuk dalam kategori rawan bencana tinggi. Program ini bertujuan meningkatkan kapasitas dan kesiapsiagaan masyarakat dalam menghadapi ancaman bencana alam.

Pelatihan yang diberikan mencakup simulasi evakuasi, pertolongan pertama, pembuatan peta risiko bencana lokal, serta pembentukan Tim Siaga Bencana Kelurahan (TSBK). Setiap kelurahan akan mendapatkan perlengkapan darurat senilai Rp 50 juta dari anggaran APBD Kota Bandung.

"Kami percaya bahwa kesiapsiagaan di tingkat masyarakat adalah garis pertahanan pertama yang paling efektif," kata Kepala BPBD Kota Bandung.

Program ini akan berlangsung selama 12 bulan dan mencakup 50.000 warga di wilayah sasaran.',
                'status'     => 'published',
            ],
            [
                'bencana_id' => $bencanaRecords[9]->id,
                'judul'      => 'Banjir Gedebage Surut: Warga Kembali ke Rumah, Normalisasi Drainase Dimulai',
                'isi'        => 'Setelah lebih dari seminggu mengungsi, warga Kecamatan Gedebage kini dapat kembali ke rumah masing-masing. Banjir yang sempat menggenangi kawasan setinggi 120 cm telah sepenuhnya surut berkat kerja keras tim BPBD yang memasang pompa air portabel.

Dinas Pekerjaan Umum (DPUPR) Kota Bandung langsung melakukan perbaikan dan normalisasi saluran drainase untuk mencegah kejadian serupa. Total anggaran perbaikan darurat sebesar Rp 2,3 miliar dialokasikan dari dana tanggap darurat.

BPBD memberikan bantuan logistik dan perlengkapan kebersihan kepada 1.200 kepala keluarga yang terdampak. Masyarakat juga dihimbau untuk segera membersihkan rumah dari sisa lumpur dan melaporkan kerusakan kepada pemerintah kelurahan.',
                'status'     => 'published',
            ],
            [
                'bencana_id' => null,
                'judul'      => 'Tips Persiapan Keluarga Menghadapi Musim Hujan dan Risiko Bencana',
                'isi'        => 'Memasuki musim penghujan, BPBD Kota Bandung mengajak seluruh keluarga untuk mempersiapkan diri menghadapi potensi bencana. Berikut adalah langkah-langkah persiapan yang disarankan:

1. BUAT RENCANA EVAKUASI KELUARGA
Tentukan titik kumpul dan jalur evakuasi yang aman. Pastikan semua anggota keluarga mengetahuinya.

2. SIAPKAN TAS DARURAT (GO BAG)
Isi dengan dokumen penting (KTP, KK, akta kelahiran), uang tunai, obat-obatan, pakaian ganti, senter, dan makanan ringan untuk 3 hari.

3. KENALI POTENSI RISIKO DI LINGKUNGAN ANDA
Pelajari potensi bencana di kelurahan Anda dan kenali tanda-tanda awal bencana seperti retakan tanah, bau sulfur, atau banjir ringan.

4. PANTAU INFORMASI CUACA
Install aplikasi Info BMKG dan aktifkan notifikasi peringatan dini. Ikuti juga media sosial resmi BPBD Kota Bandung.

5. SIMPAN NOMOR DARURAT
BPBD: (022) 7231929, Damkar: 113, SAR: 115, Ambulans: 118.

Dengan persiapan yang matang, kita dapat meminimalkan risiko dan dampak bencana bagi keluarga.',
                'status'     => 'published',
            ],
        ];

        foreach ($beritaData as $bd) {
            Berita::create(array_merge($bd, ['admin_id' => $admin->id]));
        }

        // ── 6. LAPORAN MASYARAKAT ─────────────────────────────────
        $laporanData = [
            [
                'user_id'           => $users[0]->id,
                'nama_pelapor'      => $users[0]->full_name,
                'email_pelapor'     => $users[0]->email,
                'telepon'           => '08119876543',
                'lokasi_kejadian'   => 'Jl. Raya Dayeuhkolot No. 45, Bandung',
                'jenis_bencana'     => 'Banjir',
                'deskripsi'         => 'Air dari sungai mulai masuk ke halaman rumah sejak pukul 02.00 WIB. Ketinggian sekarang sudah 50cm dan terus naik. Beberapa tetangga sudah mengungsi.',
                'tingkat_keparahan' => 'Berat',
                'korban_jiwa'       => 0,
                'korban_luka'       => 0,
                'rumah_rusak'       => 3,
                'latitude'          => -6.9990,
                'longitude'         => 107.6320,
                'status'            => 'diproses',
                'catatan_petugas'   => 'Tim sudah dikirim ke lokasi. Proses evakuasi sedang berjalan.',
                'tanggal_kejadian'  => now()->subDays(1),
            ],
            [
                'user_id'           => $users[1]->id,
                'nama_pelapor'      => $users[1]->full_name,
                'email_pelapor'     => $users[1]->email,
                'telepon'           => '08221234567',
                'lokasi_kejadian'   => 'Perumahan Antapani Indah Blok C, Bandung',
                'jenis_bencana'     => 'Banjir',
                'deskripsi'         => 'Drainase tersumbat sampah menyebabkan air meluap ke jalan dan masuk ke beberapa rumah. Genangan sekitar 30cm.',
                'tingkat_keparahan' => 'Sedang',
                'korban_jiwa'       => 0,
                'korban_luka'       => 0,
                'rumah_rusak'       => 1,
                'latitude'          => -6.9115,
                'longitude'         => 107.6655,
                'status'            => 'selesai',
                'catatan_petugas'   => 'Drainase sudah dibersihkan. Genangan sudah surut.',
                'tanggal_kejadian'  => now()->subDays(2),
            ],
            [
                'user_id'           => $users[2]->id,
                'nama_pelapor'      => $users[2]->full_name,
                'email_pelapor'     => $users[2]->email,
                'telepon'           => '08579876543',
                'lokasi_kejadian'   => 'Jl. Coblong, Dekat kampus ITB, Bandung',
                'jenis_bencana'     => 'Longsor',
                'deskripsi'         => 'Terdapat retakan memanjang di tanah lereng belakang rumah saya sepanjang kurang lebih 10 meter. Takut terjadi longsor saat hujan deras.',
                'tingkat_keparahan' => 'Sedang',
                'korban_jiwa'       => 0,
                'korban_luka'       => 0,
                'rumah_rusak'       => 0,
                'latitude'          => -6.8900,
                'longitude'         => 107.6160,
                'status'            => 'diproses',
                'catatan_petugas'   => 'Tim geologi sedang meninjau lokasi.',
                'tanggal_kejadian'  => now()->subDays(3),
            ],
            [
                'user_id'           => $users[3]->id,
                'nama_pelapor'      => $users[3]->full_name,
                'email_pelapor'     => $users[3]->email,
                'telepon'           => '08981234567',
                'lokasi_kejadian'   => 'Kp. Cijerokaso RT 04/07 Sukajadi Bandung',
                'jenis_bencana'     => 'Angin Kencang',
                'deskripsi'         => 'Angin kencang merobohkan 2 pohon besar yang menimpa kabel listrik. Satu tiang listrik ikut roboh. Warga sekitar sudah tidak ada aliran listrik sejak tadi sore.',
                'tingkat_keparahan' => 'Ringan',
                'korban_jiwa'       => 0,
                'korban_luka'       => 1,
                'rumah_rusak'       => 0,
                'latitude'          => -6.8970,
                'longitude'         => 107.5875,
                'status'            => 'selesai',
                'catatan_petugas'   => 'PLN sudah menangani. Listrik sudah menyala kembali.',
                'tanggal_kejadian'  => now()->subDays(2),
            ],
            [
                'user_id'           => $users[4]->id,
                'nama_pelapor'      => $users[4]->full_name,
                'email_pelapor'     => $users[4]->email,
                'telepon'           => '08561234567',
                'lokasi_kejadian'   => 'Jl. Arcamanik Endah No. 12, Bandung Timur',
                'jenis_bencana'     => 'Angin Kencang',
                'deskripsi'         => 'Atap rumah warga di sekitar saya berterbangan akibat angin sangat kencang. Beberapa kaca jendela pecah. Mohon segera ditindaklanjuti.',
                'tingkat_keparahan' => 'Sedang',
                'korban_jiwa'       => 0,
                'korban_luka'       => 0,
                'rumah_rusak'       => 5,
                'latitude'          => -6.9150,
                'longitude'         => 107.6840,
                'status'            => 'pending',
                'catatan_petugas'   => null,
                'tanggal_kejadian'  => now()->subHours(6),
            ],
            [
                'user_id'           => $users[0]->id,
                'nama_pelapor'      => $users[0]->full_name,
                'email_pelapor'     => $users[0]->email,
                'telepon'           => '08119876543',
                'lokasi_kejadian'   => 'Cibiru Wetan, Kec. Cileunyi, Bandung',
                'jenis_bencana'     => 'Banjir Bandang',
                'deskripsi'         => 'Air dari hulu tiba-tiba datang dengan deras dan membawa lumpur. Saya takut ini adalah tanda banjir bandang. Warga panik.',
                'tingkat_keparahan' => 'Berat',
                'korban_jiwa'       => 0,
                'korban_luka'       => 2,
                'rumah_rusak'       => 2,
                'latitude'          => -6.9120,
                'longitude'         => 107.7230,
                'status'            => 'ditinjau',
                'catatan_petugas'   => 'Memerlukan verifikasi lapangan lebih lanjut.',
                'tanggal_kejadian'  => now()->subDays(4),
            ],
            [
                'user_id'           => $users[1]->id,
                'nama_pelapor'      => $users[1]->full_name,
                'email_pelapor'     => $users[1]->email,
                'telepon'           => '08221234567',
                'lokasi_kejadian'   => 'Jl. Ujungberung Indah, Bandung',
                'jenis_bencana'     => 'Longsor',
                'deskripsi'         => 'Material tanah menutup sebagian jalan dan ada retakan di dinding rumah saya. Kondisi sangat mengkhawatirkan.',
                'tingkat_keparahan' => 'Sangat Berat',
                'korban_jiwa'       => 0,
                'korban_luka'       => 0,
                'rumah_rusak'       => 1,
                'latitude'          => -6.8930,
                'longitude'         => 107.7035,
                'status'            => 'pending',
                'catatan_petugas'   => null,
                'tanggal_kejadian'  => now()->subHours(12),
            ],
            [
                'user_id'           => $users[2]->id,
                'nama_pelapor'      => $users[2]->full_name,
                'email_pelapor'     => $users[2]->email,
                'telepon'           => '08579876543',
                'lokasi_kejadian'   => 'Kp. Cicadas, Kec. Cibeunying Kidul',
                'jenis_bencana'     => 'Banjir',
                'deskripsi'         => 'Ini foto yang saya unggah itu hoaks, banjir tidak separah itu. Mohon dicek kebenarannya.',
                'tingkat_keparahan' => 'Ringan',
                'korban_jiwa'       => 0,
                'korban_luka'       => 0,
                'rumah_rusak'       => 0,
                'latitude'          => -6.9050,
                'longitude'         => 107.6380,
                'status'            => 'hoaks',
                'catatan_petugas'   => 'Setelah verifikasi lapangan, kondisi tidak seperti yang dilaporkan.',
                'tanggal_kejadian'  => now()->subDays(5),
            ],
        ];

        foreach ($laporanData as $ld) {
            LaporanMasyarakat::create($ld);
        }

        $this->command->info('✅ Seeder SIPDA berhasil! Data yang dibuat:');
        $this->command->info('   👤 Users    : ' . User::count() . ' akun (1 admin, 2 petugas, 5 masyarakat)');
        $this->command->info('   🌋 Jenis    : ' . JenisBencana::count() . ' jenis bencana');
        $this->command->info('   ⚠️  Bencana  : ' . DataBencana::count() . ' kejadian (2 Darurat, 3 Siaga, 4 Waspada, 2 Aman)');
        $this->command->info('   📰 Berita   : ' . Berita::count() . ' artikel published');
        $this->command->info('   📋 Laporan  : ' . LaporanMasyarakat::count() . ' laporan masyarakat');
        $this->command->info('');
        $this->command->info('   🔑 Akun login:');
        $this->command->info('      admin@sipda.id     → Admin123   (Admin)');
        $this->command->info('      petugas1@sipda.id  → Petugas123 (Petugas)');
        $this->command->info('      petugas2@sipda.id  → Petugas123 (Petugas)');
        $this->command->info('      ahmad@gmail.com    → User1234a  (Masyarakat)');
    }
}
