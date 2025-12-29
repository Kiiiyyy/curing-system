<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Curing Monitor v3.2</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        .card-shadow { box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: none; border-radius: 12px; }
        .filter-bar { background: #fff; padding: 15px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); }
        .bg-dark-blue { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; }
        .table-custom th { font-weight: 600; font-size: 0.85rem; color: #666; background-color: #f9faub; }
        .table-custom td { font-size: 0.9rem; }
    </style>
</head>
<body x-data="dashboardApp()">

    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container py-2">
            <span class="navbar-brand mb-0 h1 fw-bold">
                <i class="fa-solid fa-industry text-warning me-2"></i>CURING MONITOR
            </span>
            <div class="text-white">
                <small class="me-2 text-white-50">System Time:</small>
                <span class="font-monospace fw-bold" x-text="currentTime"></span>
            </div>
        </div>
    </nav>

    <div class="container mt-4 pb-5">

        <div class="filter-bar d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <h5 class="mb-0 text-secondary me-3"><i class="fa-solid fa-filter me-2"></i>Filter Data</h5>
                <span x-show="isLoading" class="badge bg-warning text-dark">
                    <i class="fa-solid fa-spinner fa-spin me-1"></i> Syncing...
                </span>
            </div>
            <div class="d-flex gap-2">
                <input type="date" class="form-control" x-model="filters.date" @change="fetchData()">
                <select class="form-select fw-bold" style="width: 150px;" x-model="filters.shift" @change="fetchData()">
                    <option value="SHIFT_1">SHIFT 1</option>
                    <option value="SHIFT_2">SHIFT 2</option>
                    <option value="SHIFT_3">SHIFT 3</option>
                </select>
            </div>
        </div>

        <div class="row g-4 mb-4">
            
            <div class="col-md-6">
                <div class="card card-shadow h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="fa-regular fa-clock me-2"></i>STATUS SHIFT TERPILIH (<span x-text="filters.shift"></span>)
                        </h6>
                        <span class="badge" :class="machineData.runtime > machineData.downtime ? 'bg-success' : 'bg-danger'">
                            MC-01
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row text-center align-items-center h-100">
                            <div class="col-6 border-end">
                                <h3 class="fw-bold text-success mb-0" x-text="formatDuration(machineData.runtime)">0m</h3>
                                <small class="text-muted text-uppercase">Runtime</small>
                            </div>
                            <div class="col-6">
                                <h3 class="fw-bold text-danger mb-0" x-text="formatDuration(machineData.downtime)">0m</h3>
                                <small class="text-muted text-uppercase">Downtime</small>
                            </div>
                            <div class="col-12 mt-3 pt-3 border-top">
                                <div class="d-flex justify-content-between px-4">
                                    <span>Output: <b x-text="machineData.production || 0"></b> pcs</span>
                                    <span>Quality: <b x-text="(machineData.quality || 0) + '%'"></b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-shadow h-100 bg-dark-blue border-0">
                    <div class="card-header bg-transparent border-white border-opacity-25 py-3 text-white">
                        <h6 class="mb-0 fw-bold">
                            <i class="fa-solid fa-calendar-day me-2 text-warning"></i>TOTAL HARI INI (ALL SHIFTS)
                        </h6>
                    </div>
                    <div class="card-body text-white">
                        <div class="row text-center h-100 align-items-center">
                            <div class="col-6 border-end border-white border-opacity-25">
                                <h2 class="fw-bold mb-0 text-warning" x-text="formatDuration(dailyStats.runtime)">0m</h2>
                                <small class="text-white-50 text-uppercase">Total Runtime</small>
                            </div>
                            <div class="col-6">
                                <h2 class="fw-bold mb-0 text-danger" style="filter: brightness(1.5);" x-text="formatDuration(dailyStats.downtime)">0m</h2>
                                <small class="text-white-50 text-uppercase">Total Downtime</small>
                            </div>
                            <div class="col-12 mt-3 pt-3 border-top border-white border-opacity-25">
                                <div class="d-flex justify-content-center gap-4">
                                    <span class="badge bg-white bg-opacity-25 p-2">
                                        Total Output: <span class="fw-bold text-white fs-6" x-text="dailyStats.production">0</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-shadow">
                    <div class="card-header bg-white py-3">
                        <i class="fa-solid fa-list-ul me-2 text-secondary"></i>Log Aktivitas Terkini (MC-01)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Waktu</th>
                                    <th>Status Mesin</th>
                                    <th>Mode</th>
                                    <th>Durasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="log in eventLogs" :key="log.id">
                                    <tr>
                                        <td class="ps-4 text-muted" x-text="formatTimeOnly(log.waktu)"></td>
                                        <td>
                                            <span class="badge rounded-pill" 
                                                  :class="log.status_mesin === 'ON' ? 'bg-success' : 'bg-danger'"
                                                  x-text="log.status_mesin"></span>
                                        </td>
                                        <td x-text="log.mode_mesin"></td>
                                        <td class="fw-bold text-dark" x-text="log.durasi + 's'"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function dashboardApp() {
            return {
                baseUrl: 'https://curing.bagipanen.my.id/api',
                
                // Filters
                filters: {
                    date: new Date().toISOString().split('T')[0],
                    shift: 'SHIFT_1'
                },

                // Data Containers
                machineData: { runtime: 0, downtime: 0, production: 0, quality: 0 }, // Data Shift Terpilih
                dailyStats: { runtime: 0, downtime: 0, production: 0 }, // Data Akumulasi (All Shifts)
                eventLogs: [],
                
                // System
                isLoading: false,
                currentTime: '',

                init() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);
                    
                    this.fetchData();
                    setInterval(() => this.fetchData(true), 5000); // Auto refresh
                },

                async fetchData(silent = false) {
                    if(!silent) this.isLoading = true;

                    // 1. Ambil Data Shift Terpilih (Normal)
                    fetch(`${this.baseUrl}/monitoring.php?view=overview&machine_id=MC-01&shift=${this.filters.shift}`)
                        .then(res => res.json())
                        .then(data => { if(data.status === 'success') this.machineData = data.data; });

                    // 2. Ambil Logs
                    fetch(`${this.baseUrl}/kondisi-mesin.php?machine_id=MC-01&limit=5`)
                        .then(res => res.json())
                        .then(data => { if(data.status === 'success') this.eventLogs = data.data; });

                    // 3. HITUNG TOTAL AKUMULASI (SHIFT 1 + SHIFT 2 + SHIFT 3)
                    // Kita tembak 3 request sekaligus
                    const shifts = ['SHIFT_1', 'SHIFT_2', 'SHIFT_3'];
                    const promises = shifts.map(s => 
                        fetch(`${this.baseUrl}/monitoring.php?view=overview&machine_id=MC-01&shift=${s}`)
                        .then(res => res.json())
                    );

                    Promise.all(promises).then(results => {
                        let totalRun = 0;
                        let totalDown = 0;
                        let totalProd = 0;

                        results.forEach(res => {
                            if(res.status === 'success') {
                                totalRun += parseInt(res.data.runtime || 0);
                                totalDown += parseInt(res.data.downtime || 0);
                                totalProd += parseInt(res.data.production || 0);
                            }
                        });

                        this.dailyStats = {
                            runtime: totalRun,
                            downtime: totalDown,
                            production: totalProd
                        };
                        
                        if(!silent) this.isLoading = false;
                    });
                },

                // Helpers
                formatDuration(seconds) {
                    if (!seconds) return '0m';
                    const h = Math.floor(seconds / 3600);
                    const m = Math.floor((seconds % 3600) / 60);
                    if (h > 0) return `${h}j ${m}m`;
                    return `${m} mnt`;
                },
                
                formatTimeOnly(dateStr) {
                    return dateStr ? new Date(dateStr).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'}) : '-';
                },

                updateTime() {
                    this.currentTime = new Date().toLocaleTimeString('id-ID');
                }
            }
        }
    </script>
</body>
</html>