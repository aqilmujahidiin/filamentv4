# Dokumentasi Command Pembuatan Payment Otomatis

## Tentang Command

Command `app:create-payment` dibuat untuk secara otomatis membuat payment ketika terdapat 8 schedule dengan status "Completed" yang memiliki `course_id`, `student_id`, dan `teacher_id` yang sama. Command ini hanya akan memproses schedule yang berada pada bulan dan tahun saat ini.

## Cara Kerja

Command ini bekerja dengan cara:

1. Mencari grup schedule yang memiliki status "Completed" dengan `course_id`, `student_id`, dan `teacher_id` yang sama
2. Memastikan bahwa schedule tersebut belum memiliki payment terkait
3. Memfilter schedule berdasarkan bulan dan tahun saat ini
4. Mengelompokkan schedule dan menghitung jumlahnya
5. Jika ditemukan grup dengan jumlah schedule >= 8, maka akan dibuat payment baru
6. Payment dibuat dengan status "Unpaid"
7. Setiap schedule akan terhubung dengan payment melalui tabel `schedule_payments`
8. Total amount payment dihitung dari jumlah fee masing-masing schedule

## Cara Menjalankan Command

Command dapat dijalankan secara manual dengan perintah:

```bash
php artisan app:create-payment
```

Untuk melihat output yang lebih detail, tambahkan flag `--verbose`:

```bash
php artisan app:create-payment --verbose
```

## Penjadwalan Otomatis

Command ini telah dijadwalkan untuk berjalan secara otomatis setiap hari pada pukul 01:00 pagi. Konfigurasi penjadwalan dapat ditemukan di file `app/Console/Kernel.php`.

Untuk mengubah jadwal, edit baris berikut di file tersebut:

```php
$schedule->command('app:create-payment')->dailyAt('01:00');
```

## Persyaratan

Agar command ini berfungsi dengan baik, pastikan:

1. Terdapat minimal 8 schedule dengan status "Completed"
2. Schedule tersebut memiliki `course_id`, `student_id`, dan `teacher_id` yang sama
3. Schedule tersebut berada pada bulan dan tahun saat ini
4. Schedule tersebut belum terhubung dengan payment manapun
5. Setiap schedule memiliki `start_time` dan `end_time` yang valid untuk perhitungan fee
6. Course terkait memiliki nilai `fee_per_hour` yang valid

## Troubleshooting

Jika command tidak membuat payment meskipun Anda yakin ada schedule yang memenuhi syarat, periksa:

1. Status schedule (harus "Completed", bukan "completed" atau status lain)
2. Pastikan schedule belum terhubung dengan payment manapun
3. Pastikan `course_id`, `student_id`, dan `teacher_id` memiliki nilai yang valid dan sama
4. Periksa log aplikasi untuk informasi error yang lebih detail