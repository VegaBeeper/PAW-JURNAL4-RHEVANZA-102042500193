<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MataKuliah;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Dosen Pengampu Default
        $dosen = User::updateOrCreate(
            ['email' => 'dosen@celoe.test'],
            [
                'name' => 'Dr. Ir. Budi Santoso, M.T.',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
                'nomor_induk' => '198501012015041001',
            ]
        );

        // 2. Akun Mahasiswa Default
        $mahasiswa1 = User::updateOrCreate(
            ['email' => 'mahasiswa@celoe.test'],
            [
                'name' => 'Ahmad Pratama',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210001',
            ]
        );

        // 3. Mata Kuliah Dasar (Modul 3)
        $course1 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF301'],
            [
                'nama_mk' => 'Pengembangan Aplikasi Website',
                'deskripsi' => 'Mata kuliah yang membahas arsitektur aplikasi web modern menggunakan framework Laravel, MVC, Eloquent ORM, dan keamanan web.',
                'sks' => 4,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        // 4. Daftarkan Mahasiswa ke Mata Kuliah Pertama (status approved)
        $course1->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
        ]);

        // 5. Tambahkan Materi Contoh
        Material::updateOrCreate(
            ['course_id' => $course1->id, 'minggu_ke' => 1, 'urutan' => 1],
            [
                'judul' => 'Pengenalan Arsitektur MVC & Routing di Laravel 12',
                'konten' => 'Mempelajari alur HTTP Request, Route Middleware, Controller, dan pembuatan View menggunakan Blade Template.',
            ]
        );

        // TODO 8: Tambahkan minimal 2 mata kuliah lagi dengan semester berbeda, 1 mahasiswa tambahan, 2 tugas, dan contoh data submission
        $mahasiswa2 = User::updateOrCreate(
            ['email' => 'siti@celoe.test'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210002',
            ]
        );

        $course2 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF204'],
            [
                'nama_mk' => 'Basis Data Lanjut',
                'deskripsi' => 'Mata kuliah yang membahas perancangan basis data relasional, normalisasi, SQL lanjutan, dan optimasi query.',
                'sks' => 3,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        $course3 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF402'],
            [
                'nama_mk' => 'Keamanan Aplikasi Web',
                'deskripsi' => 'Mata kuliah yang membahas celah keamanan aplikasi web seperti SQL Injection, XSS, CSRF, serta praktik pengamanan sesi dan autentikasi.',
                'sks' => 2,
                'semester' => 'Genap 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        $course1->students()->syncWithoutDetaching([
            $mahasiswa2->id => ['status' => 'approved'],
        ]);
        $course2->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
            $mahasiswa2->id => ['status' => 'approved'],
        ]);

        $assignment1 = Assignment::updateOrCreate(
            ['course_id' => $course1->id, 'judul' => 'Tugas 1: Analisis Arsitektur MVC Laravel'],
            [
                'deskripsi' => 'Jelaskan alur sebuah HTTP Request pada Laravel mulai dari Route, Middleware, Controller, Model, hingga View beserta contoh kodenya.',
                'deadline' => now()->addDays(14),
            ]
        );

        $assignment2 = Assignment::updateOrCreate(
            ['course_id' => $course2->id, 'judul' => 'Tugas 1: Normalisasi Basis Data'],
            [
                'deskripsi' => 'Lakukan normalisasi sampai bentuk normal ketiga (3NF) pada tabel transaksi penjualan yang diberikan, lalu gambarkan ERD hasilnya.',
                'deadline' => now()->addDays(10),
            ]
        );

        Submission::updateOrCreate(
            ['assignment_id' => $assignment1->id, 'user_id' => $mahasiswa1->id],
            [
                'jawaban' => 'HTTP Request masuk melalui public/index.php, diproses oleh Route di routes/web.php, melewati Middleware, diteruskan ke Controller, Controller berinteraksi dengan Model untuk mengambil data dari database, lalu hasilnya dikirim ke View Blade.',
                'nilai' => 88,
                'catatan_dosen' => 'Penjelasan alur sudah tepat dan runtut. Tambahkan contoh kode untuk bagian Middleware.',
                'submitted_at' => now()->subDay(),
            ]
        );

        Submission::updateOrCreate(
            ['assignment_id' => $assignment1->id, 'user_id' => $mahasiswa2->id],
            [
                'jawaban' => 'Alur request Laravel dimulai dari Route yang menentukan Controller. Controller memanggil Model untuk mengakses database dan mengembalikan View kepada pengguna.',
                'nilai' => null,
                'catatan_dosen' => null,
                'submitted_at' => now()->subHours(5),
            ]
        );
    }
}
