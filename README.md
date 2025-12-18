# 🚀 Industrial Machine Condition Monitoring API

Backend logging sederhana berbasis **Native PHP** untuk memonitor kondisi mesin industri dengan pendekatan *1 Context – 4 Metrics*.

---

## 📋 Ringkasan Sistem
Sistem ini mencatat data kondisi mesin (Status & Mode) ke dalam satu tabel log tunggal dan menghasilkan 4 metrik operasional secara real-time melalui query SQL.

### 📊 1 Konteks - 4 Metrik
1. **Status Mesin:** Kondisi hidup/mati saat ini (Metrik 1).
2. **Mode Mesin:** Mode operasi saat ini (Metrik 2).
3. **Runtime:** Total waktu operasi dalam 8 jam terakhir (Metrik 3).
4. **Downtime:** Total waktu henti dalam 8 jam terakhir (Metrik 4).

---

## 🛠 Konfigurasi API
- **Base URL:** `http://localhost/monitoring_api`
- **Format Data:** `JSON`
- **Metode:** `HTTP GET` (Sesuai arahan untuk kemudahan integrasi IoT/Excel)

---

## 📡 Endpoint API

### 1. Insert Kondisi Mesin
Mencatat log kondisi terbaru dari mesin.
- **Endpoint:** `/api/insert.php`
- **Method:** `GET`
- **Parameter:**
| Nama | Tipe | Wajib | Keterangan |
| :--- | :--- | :--- | :--- |
| `status_mesin` | String | Ya | `ON` atau `OFF` |
| `mode_mesin` | String | Ya | `AUTO` atau `MANUAL` |
| `waktu` | Datetime | Tidak | Format: `YYYY-MM-DD HH:MM:SS` |

**Contoh Request:**
`GET /api/insert.php?status_mesin=ON&mode_mesin=AUTO`

---

### 2. Monitoring Status & Mode Terakhir
Mengambil data kondisi mesin yang paling baru dimasukkan.
- **Endpoint:** `/api/latest.php`
- **Method:** `GET`

**Contoh Response:**
```json
{
  "metrik": "Status & Mode Terakhir",
  "status_mesin": "ON",
  "mode_mesin": "AUTO",
  "last_update": "2025-12-18 09:15:00"
}