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

            // 5. Akun Mahasiswa Tambahan
            $mahasiswa2 = User::updateOrCreate(
                ['email' => 'siti@celoe.test'],
                [
                    'name' => 'Siti Aisyah',
                    'password' => Hash::make('mahasiswa123'),
                    'role' => 'mahasiswa',
                    'nomor_induk' => '1301210002',
                ]
            );

            // 6. Mata Kuliah Tambahan
            $course2 = MataKuliah::updateOrCreate(
                ['kode_mk' => 'IF204'],
                [
                    'nama_mk' => 'Pemrograman Berorientasi Objek',
                    'deskripsi' => 'Mempelajari konsep pemrograman berorientasi objek.',
                    'sks' => 3,
                    'semester' => 'Ganjil 2026/2027',
                    'dosen_id' => $dosen->id,
                ]
            );

            $course3 = MataKuliah::updateOrCreate(
                ['kode_mk' => 'IF402'],
                [
                    'nama_mk' => 'Rekayasa Perangkat Lunak',
                    'deskripsi' => 'Mempelajari proses pengembangan perangkat lunak.',
                    'sks' => 2,
                    'semester' => 'Genap 2026/2027',
                    'dosen_id' => $dosen->id,
                ]
            );

            // 7. Pendaftaran Mahasiswa
            foreach ([$course1, $course2, $course3] as $course) {
                $course->students()->syncWithoutDetaching([
                    $mahasiswa1->id => ['status' => 'approved'],
                    $mahasiswa2->id => ['status' => 'approved'],
                ]);
            }

            // 8. Membuat Dua Tugas
            $assignment1 = Assignment::updateOrCreate(
                [
                    'course_id' => $course1->id,
                    'judul' => 'Tugas Praktikum Laravel',
                ],
                [
                    'deskripsi' => 'Buat aplikasi sederhana menggunakan Laravel.',
                    'deadline' => '2027-01-15 23:59:00',
                ]
            );

            $assignment2 = Assignment::updateOrCreate(
                [
                    'course_id' => $course1->id,
                    'judul' => 'Tugas CRUD Laravel',
                ],
                [
                    'deskripsi' => 'Implementasikan fitur CRUD pada aplikasi Laravel.',
                    'deadline' => '2027-01-20 23:59:00',
                ]
            );

            // 9. Contoh Pengumpulan Tugas
            Submission::updateOrCreate(
                [
                    'assignment_id' => $assignment1->id,
                    'user_id' => $mahasiswa1->id,
                ],
                [
                    'jawaban' => 'Saya telah menyelesaikan tugas praktikum Laravel.',
                    'submitted_at' => '2026-11-15 10:00:00',
                    'nilai' => 88,
                    'catatan_dosen' => 'Pengerjaan sudah sangat baik.',
                ]
            );

            Submission::updateOrCreate(
                [
                    'assignment_id' => $assignment2->id,
                    'user_id' => $mahasiswa2->id,
                ],
                [
                    'jawaban' => 'Saya telah menyelesaikan tugas CRUD Laravel.',
                    'submitted_at' => '2026-11-16 11:00:00',
                    'nilai' => null,
                    'catatan_dosen' => null,
                ]
            );
    }
}
