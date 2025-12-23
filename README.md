# 🚀 Industrial Curing Monitoring System — API Documentation

**Version:** 3.0 *(Industry Ready)*

**Base URL:**

```
http://localhost/curing-system/
```

**Standard:**
Native **PHP REST API** *(GET-based untuk kompatibilitas PLC / Script industri)*

Sistem ini dirancang untuk memantau kondisi **mesin curing pada manufaktur ban** dengan pendekatan:

* **Event-Based Logging**
* **Automatic Duration Calculation**
* **Shift-Based Production Tracking**
* **Quality (OEE) Measurement via Fault Mode**

---

## 1️⃣ Kondisi Mesin (Write & Auto-Log)

Endpoint utama untuk **mengubah status mesin**.
Jika status berubah (**ON ⇄ OFF**), sistem otomatis **menutup durasi status sebelumnya**.

### Endpoint

```
GET api/kondisi-mesin-write.php
```

### Parameters

| Parameter   | Tipe   | Wajib | Deskripsi                               |
| ----------- | ------ | ----- | --------------------------------------- |
| machine_id  | String | Ya    | ID unik mesin (contoh: `MC-01`)         |
| perintah    | Enum   | Ya    | Status target: `ON` / `OFF`             |
| mode_target | Enum   | Ya    | Mode mesin: `AUTO`, `MANUAL`, `FAULT`   |
| shift       | Enum   | Ya    | `SHIFT_1`, `SHIFT_2`, `SHIFT_3`         |
| tanggal     | Date   | Tidak | Format `YYYY-MM-DD` (default: hari ini) |

### Request Example

```
/api/kondisi-mesin-write.php?machine_id=MC-01&perintah=OFF&mode_target=FAULT&shift=SHIFT_1
```

### Response Success

```json
{
  "status": "success",
  "message": "Log diperbarui",
  "update_duration": "Status berubah, durasi diupdate: 450 detik"
}
```

---

## 2️⃣ Produksi Curing (Hybrid: Write & Read)

Endpoint ganda untuk **mencatat produksi** atau **membaca riwayat produksi**.

### Endpoint

```
GET api/produksi-curing.php
```

### Logic

* `jumlah_ban` **diisi** → **Write (Insert produksi)**
* `jumlah_ban` **kosong** → **Read (History produksi)**

---

### A. Post Production (Write)

#### Request Example

```
/api/produksi-curing.php?machine_id=MC-01&shift=SHIFT_1&jumlah_ban=25
```

#### Response Success

```json
{
  "status": "success",
  "message": "Data produksi berhasil dicatat",
  "details": {
    "machine": "MC-01",
    "added_count": "25"
  }
}
```

---

### B. Get Production History (Read)

#### Request Example

```
/api/produksi-curing.php?machine_id=MC-01&shift=SHIFT_1
```

#### Response Success

```json
{
  "status": "success",
  "total_akumulasi": 150,
  "data_list": [
    {
      "id": 1,
      "machine_id": "MC-01",
      "jumlah_ban": 25,
      "waktu": "2025-12-23 09:00:00"
    }
  ]
}
```

---

## 3️⃣ Monitoring Aggregator (Dashboard Data)

Endpoint terpadu untuk **Dashboard (Vue / React / Mobile App)**.

### Endpoint

```
GET api/monitoring.php
```

### View Types

| View           | Fungsi                                                     |
| -------------- | ---------------------------------------------------------- |
| `overview`     | Detail satu mesin (Runtime, Downtime, Production, Quality) |
| `shift-report` | Tabel performa semua mesin dalam satu shift                |
| `latest`       | 10 log kejadian terbaru                                    |

---

### Request Example (Overview)

```
/api/monitoring.php?view=overview&machine_id=MC-01&shift=SHIFT_1
```

### Response Success

```json
{
  "status": "success",
  "data": {
    "runtime": 12400,
    "downtime": 1200,
    "production": 350,
    "quality": 80
  }
}
```

---

## 4️⃣ Total Produksi Global (Plant Wide)

Menghitung **akumulasi produksi dari seluruh mesin** di lantai pabrik.

### Endpoint

```
GET api/total-produksi.php
```

### Request Example

```
/api/total-produksi.php?shift=SHIFT_1&tanggal=2025-12-23
```

### Response Success

```json
{
  "status": "success",
  "data": {
    "total_produksi": 1250,
    "jumlah_mesin_aktif": 12,
    "unit": "pcs"
  }
}
```

---

## 5️⃣ Mode Fault (Quality OEE)

Mengambil **metrik kualitas (Quality OEE)** berdasarkan **frekuensi gangguan (FAULT)**
Data diambil dari **Database View** (tanpa insert langsung).

### Endpoint

```
GET api/mode-fault.php
```

### Request Example

```
/api/mode-fault.php?machine_id=MC-01&shift=SHIFT_1
```

### Response Success

```json
{
  "status": "success",
  "data": {
    "machine_id": "MC-01",
    "total_fault": 2,
    "quality_percentage": 80
  }
}
```

---

## 6️⃣ Log Kondisi Mesin (History)

Membaca **Event Log** kondisi mesin secara kronologis.

### Endpoint

```
GET api/kondisi-mesin.php
```

### Parameters

| Parameter  | Wajib | Deskripsi                 |
| ---------- | ----- | ------------------------- |
| machine_id | Tidak | Filter mesin tertentu     |
| limit      | Tidak | Jumlah data (default: 10) |

---

### Response Success

```json
{
  "status": "success",
  "data": [
    {
      "id": 45,
      "machine_id": "MC-01",
      "status_mesin": "OFF",
      "mode_mesin": "FAULT",
      "durasi": 0,
      "waktu": "2025-12-23 10:15:00"
    }
  ]
}
```

---

## 🔴 Error Handling

Semua error menggunakan format JSON standar:

```json
{
  "status": "error",
  "message": "Machine ID wajib diisi"
}
```

---

## 📌 Catatan Integrasi

* Cocok untuk **PLC / IoT / Script Industri**
* Tidak menggunakan POST → **Aman untuk device terbatas**
* Siap diintegrasikan ke **Dashboard Web / Mobile**
* Mendukung **OEE Calculation (Runtime, Downtime, Quality)**

---
