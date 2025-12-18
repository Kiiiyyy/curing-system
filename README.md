# 🚀 Industrial Machine Condition Monitoring API

Backend logging sederhana berbasis **Native PHP** untuk memonitor kondisi mesin industri dengan pendekatan **1 Context – 4 Metrics**. Sistem ini dirancang ringan, mudah diintegrasikan dengan perangkat IoT, Excel, maupun sistem SCADA sederhana.

---

## 📋 Ringkasan Sistem

Sistem melakukan pencatatan kondisi mesin secara periodik ke **satu tabel log tunggal**. Dari tabel ini, sistem menghasilkan **4 metrik operasional utama** menggunakan query SQL tanpa tabel tambahan.

### 📊 Konsep 1 Konteks – 4 Metrik

**Konteks:** Log Kondisi Mesin

**Metrik yang dihasilkan:**

1. **Status Mesin** → Kondisi hidup/mati mesin terbaru.
2. **Mode Mesin** → Mode operasi mesin terbaru.
3. **Runtime** → Total waktu mesin dalam kondisi ON selama 8 jam terakhir.
4. **Downtime** → Total waktu mesin dalam kondisi OFF selama 8 jam terakhir.

Pendekatan ini memastikan sistem **sederhana, konsisten, dan scalable** tanpa kompleksitas berlebih.

---

## 🛠 Konfigurasi API

* **Base URL:** `http://localhost/monitoring_api`
* **Format Response:** `JSON`
* **Metode HTTP:** `GET`

> ⚠️ Penggunaan metode GET dipilih untuk kemudahan integrasi dengan perangkat IoT, browser, dan Excel.

---

## 📡 Endpoint API

### 1️⃣ Insert Kondisi Mesin

Mencatat kondisi terbaru mesin ke dalam sistem.

* **Endpoint:** `/api/insert.php`
* **Method:** `GET`

**Parameter:**

| Nama           | Tipe     | Wajib | Keterangan                                           |
| -------------- | -------- | ----- | ---------------------------------------------------- |
| `status_mesin` | String   | Ya    | `ON` atau `OFF`                                      |
| `mode_mesin`   | String   | Ya    | `AUTO` atau `MANUAL`                                 |
| `waktu`        | Datetime | Tidak | Format `YYYY-MM-DD HH:MM:SS` (default: waktu server) |

**Contoh Request:**

```http
GET /api/insert.php?status_mesin=ON&mode_mesin=AUTO
```

---

### 2️⃣ Monitoring Status & Mode Terakhir

Mengambil kondisi mesin paling terakhir yang tercatat.

* **Endpoint:** `/api/latest.php`
* **Method:** `GET`

**Contoh Response:**

```json
{
  "metrik": "Status & Mode Terakhir",
  "status_mesin": "ON",
  "mode_mesin": "AUTO",
  "last_update": "2025-12-18 09:15:00"
}
```

---

### 3️⃣ Monitoring Runtime (8 Jam Terakhir)

Menghitung total durasi mesin dalam kondisi **ON** selama 8 jam terakhir.

* **Endpoint:** `/api/operasi.php`
* **Method:** `GET`

**Contoh Response:**

```json
{
  "metrik": "Total Runtime (8 Jam Terakhir)",
  "total_point_logs": 480,
  "unit": "Log Points",
  "keterangan": "Setiap 1 log mewakili 1 menit jika interval pengiriman stabil."
}
```

---

### 4️⃣ Monitoring Downtime (8 Jam Terakhir)

Menghitung total durasi mesin dalam kondisi **OFF** selama 8 jam terakhir.

* **Endpoint:** `/api/downtime.php`
* **Method:** `GET`

**Contoh Response:**

```json
{
  "metrik": "Total Downtime (8 Jam Terakhir)",
  "total_point_logs": 25,
  "unit": "Log Points"
}
```

---

## 🗄️ Skema Database

Sistem hanya menggunakan **satu tabel utama** untuk menjaga kesederhanaan dan integritas data.

### Tabel: `kondisi_mesin`

| Kolom          | Tipe Data   | Deskripsi                      |
| -------------- | ----------- | ------------------------------ |
| `id`           | BIGINT (PK) | Auto Increment ID              |
| `status_mesin` | VARCHAR(10) | Status mesin (`ON` / `OFF`)    |
| `mode_mesin`   | VARCHAR(10) | Mode mesin (`AUTO` / `MANUAL`) |
| `waktu`        | DATETIME    | Timestamp pencatatan           |

---

## ⚙️ Cara Instalasi

1. Clone atau salin folder project ke direktori `htdocs` (XAMPP).
2. Buat database MySQL dengan nama `db_monitoring`.
3. Jalankan query SQL pembuatan tabel `kondisi_mesin`.
4. Sesuaikan konfigurasi database pada file `config.php`.
5. Jalankan server Apache & MySQL.
6. Akses endpoint API melalui browser atau tools seperti Postman.

---

## 📌 Catatan Teknis

* Sistem ini **event-log based**, bukan time-series database.
* Akurasi runtime/downtime bergantung pada **konsistensi interval pengiriman data**.
* Cocok untuk:

  * Proyek IoT
  * Monitoring mesin industri sederhana
  * Integrasi PLC → Web
  * Logging Excel via Power Query

---

## ✅ Kesimpulan

Industrial Machine Condition Monitoring API adalah solusi backend minimalis namun kuat untuk pemantauan mesin berbasis log tunggal. Dengan pendekatan **1 Context – 4 Metrics**, sistem ini mudah dipahami, dikembangkan, dan dipresentasikan untuk kebutuhan akademik maupun implementasi industri skala kecil.
