# lokerid 
untuk menjalankan test 
php artisan test 
untuk seed
php artisan migrate 
php artisan db:seed
1. Pengaturan Data Awal
Tabel yang Terlibat:
expenses: Menyimpan informasi tentang pengeluaran, termasuk jumlah dan status.
statuses: Menyimpan status pengeluaran seperti 'menunggu persetujuan' dan 'disetujui'.
approvers: Menyimpan informasi tentang orang-orang yang dapat menyetujui pengeluaran.
approvals: Menyimpan catatan persetujuan untuk pengeluaran.
2. Proses Persetujuan
Buat Pengeluaran Baru:

Pengguna membuat pengeluaran baru dengan mengisi jumlah pengeluaran dan status awalnya, biasanya 'menunggu persetujuan'.
Data disimpan dalam tabel expenses.
Buat Status dan Approver:

Status 'menunggu persetujuan' dan 'disetujui' dibuat di tabel statuses.
Approver (misalnya, 'Approver A') dibuat di tabel approvers.
Kirim Permintaan Persetujuan:

Pengguna mengirim permintaan untuk menyetujui pengeluaran melalui API.
Metode approve di Controller:

Validasi: Controller memvalidasi bahwa approver_id yang dikirim ada dalam tabel approvers.
Update Status: Controller memperbarui status pengeluaran menjadi 'disetujui'.
Catat Persetujuan: Controller menambahkan catatan persetujuan baru dalam tabel approvals dengan expense_id, approver_id, dan status 'disetujui'.
Respons: Controller mengirimkan respons JSON dengan status 200 OK jika persetujuan berhasil.
3. API Endpoint
Endpoint: PATCH /api/expense/{id}/approve
Data yang Dikirim:
approver_id: ID dari approver yang menyetujui pengeluaran.
Respons yang Diharapkan:
Status 200 OK dengan pesan keberhasilan.
4. Pengujian
Test:
Menguji pembuatan pengeluaran baru.
Menguji proses persetujuan dengan mengirim permintaan ke endpoint API dan memverifikasi bahwa data persetujuan tercatat dengan benar di database.
5. Hasil
Respons API:
JSON dengan informasi tentang pengeluaran, status terbaru, dan catatan persetujuan.