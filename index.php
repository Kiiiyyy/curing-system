<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Control Center - Industrial Curing System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f172a; color: #f8fafc; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .terminal-bg { background: #000000; border: 1px solid #334155; }
        input, select { background: #1e293b !important; color: white !important; border: 1px solid #334155 !important; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #475569; border-radius: 10px; }
    </style>
</head>
<body class="p-4 md:p-8">

    <div class="max-w-7xl mx-auto mb-8 glass p-6 rounded-[2rem] flex flex-col md:flex-row justify-between items-center gap-6 shadow-2xl">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-500/20">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black italic tracking-tighter">INDUSTRIAL<span class="text-indigo-400">CURING</span>.OS</h1>
                <p class="text-[10px] mono text-slate-500 font-bold uppercase tracking-widest">Politeknik Gajah Tunggal - Production Suite</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-4 items-center">
            <div class="flex flex-col">
                <label class="text-[9px] font-bold text-slate-500 uppercase mb-1">Production Date</label>
                <input type="date" id="g-tanggal" value="<?= date('Y-m-d') ?>" onchange="refreshAll()" class="p-2 rounded-lg text-xs outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
            <div class="flex flex-col">
                <label class="text-[9px] font-bold text-slate-500 uppercase mb-1">Active Shift</label>
                <select id="g-shift" onchange="refreshAll()" class="p-2 rounded-lg text-xs outline-none focus:ring-1 focus:ring-indigo-500">
                    <option value="SHIFT_1">SHIFT 1 (07:00-15:00)</option>
                    <option value="SHIFT_2">SHIFT 2 (15:00-23:00)</option>
                    <option value="SHIFT_3">SHIFT 3 (23:00-07:00)</option>
                </select>
            </div>
            <button onclick="refreshAll()" class="p-4 bg-indigo-600 hover:bg-indigo-500 rounded-2xl transition shadow-lg shadow-indigo-500/20 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="glass p-6 rounded-[2rem] border-l-4 border-indigo-500">
            <p class="text-[10px] font-bold text-slate-500 uppercase">Shift Runtime</p>
            <h3 id="m-runtime" class="text-3xl font-black mono text-indigo-400">0s</h3>
        </div>
        <div class="glass p-6 rounded-[2rem] border-l-4 border-red-500">
            <p class="text-[10px] font-bold text-slate-500 uppercase">Shift Downtime</p>
            <h3 id="m-downtime" class="text-3xl font-black mono text-red-500">0s</h3>
        </div>
        <div class="glass p-6 rounded-[2rem] border-l-4 border-green-500">
            <p class="text-[10px] font-bold text-slate-500 uppercase">Shift Production</p>
            <h3 id="m-prod" class="text-3xl font-black mono text-green-400">0</h3>
        </div>
        <div class="glass p-6 rounded-[2rem] border-l-4 border-yellow-500">
            <p class="text-[10px] font-bold text-slate-500 uppercase">Quality Grade</p>
            <h3 id="m-quality" class="text-3xl font-black mono text-yellow-400">100%</h3>
        </div>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-4 space-y-6">
            
            <div class="glass p-6 rounded-[2rem]">
                <h4 class="font-black text-xs text-indigo-400 uppercase mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span> 1. Machine Command (Write)
                </h4>
                <form id="form-write" class="space-y-4">
                    <input type="text" name="machine_id" value="MC-01" placeholder="Machine ID" class="w-full p-3 rounded-xl text-sm font-bold">
                    <div class="grid grid-cols-2 gap-2">
                        <select name="perintah" class="p-3 rounded-xl text-xs font-bold">
                            <option value="ON">STATUS: ON</option>
                            <option value="OFF">STATUS: OFF</option>
                        </select>
                        <select name="mode_target" class="p-3 rounded-xl text-xs font-bold text-indigo-400">
                            <option value="AUTO">MODE: AUTO</option>
                            <option value="MANUAL">MODE: MANUAL</option>
                            <option value="FAULT">MODE: FAULT</option>
                        </select>
                    </div>
                    <button type="button" onclick="apiWrite()" class="w-full bg-indigo-600 hover:bg-indigo-500 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition shadow-lg shadow-indigo-500/20">Execute Command</button>
                </form>
            </div>

            <div class="glass p-6 rounded-[2rem]">
                <h4 class="font-black text-xs text-green-400 uppercase mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span> 2. Production Log (Hybrid)
                </h4>
                <form id="form-prod" class="space-y-4">
                    <input type="hidden" name="machine_id" value="MC-01">
                    <div class="flex gap-2">
                        <input type="number" name="jumlah_ban" placeholder="Qty Ban" class="flex-1 p-3 rounded-xl text-sm font-bold">
                        <button type="button" onclick="apiProduction()" class="bg-green-600 px-6 rounded-xl font-bold text-xs uppercase">Post</button>
                    </div>
                    <button type="button" onclick="apiProductionRead()" class="w-full bg-slate-800 border border-slate-700 py-3 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-slate-700 transition">Get Production History</button>
                </form>
            </div>

            <div class="glass p-6 rounded-[2rem] border border-indigo-500/30 bg-indigo-500/5">
                <h4 class="font-black text-xs text-slate-300 uppercase mb-4">3. Plant Wide Analytics</h4>
                <button onclick="apiTotalGlobal()" class="w-full bg-white text-slate-900 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-400 transition">Get Plant Total Production</button>
            </div>
        </div>

        <div class="lg:col-span-8 space-y-6">
            
            <div class="terminal-bg rounded-[2rem] shadow-2xl flex flex-col h-[500px] overflow-hidden">
                <div class="bg-slate-800/50 p-4 border-b border-slate-700 flex justify-between items-center">
                    <div class="flex gap-1.5">
                        <div class="w-3 h-3 rounded-full bg-red-500/50"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500/50"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500/50"></div>
                    </div>
                    <span class="text-[10px] mono text-slate-500 font-bold uppercase">system_log_stream.sh</span>
                </div>
                <div id="terminal" class="p-6 flex-1 overflow-auto mono text-xs text-green-400 leading-relaxed space-y-2">
                    <p class="text-slate-500">// Menunggu inisialisasi system...</p>
                </div>
            </div>

            <div class="glass p-8 rounded-[2.5rem] overflow-hidden">
                <h3 class="font-black text-lg mb-6 italic italic tracking-tighter text-indigo-400 underline decoration-indigo-500 decoration-4 underline-offset-8 uppercase">API Documentation & Endpoints</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-[10px] uppercase font-bold text-slate-400">
                        <thead>
                            <tr class="border-b border-slate-700">
                                <th class="py-4">Endpoint Path</th>
                                <th class="py-4">Method</th>
                                <th class="py-4">Logic / Purpose</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-200">
                            <tr class="border-b border-slate-800/50 hover:bg-white/5 transition">
                                <td class="py-3 mono text-indigo-300">/api/kondisi-mesin-write.php</td>
                                <td class="py-3 text-green-500">GET</td>
                                <td class="py-3">Write Command & Auto Calc Duration (Only on Status Change)</td>
                            </tr>
                            <tr class="border-b border-slate-800/50 hover:bg-white/5 transition">
                                <td class="py-3 mono text-indigo-300">/api/monitoring.php</td>
                                <td class="py-3 text-green-500">GET</td>
                                <td class="py-3">Aggregate Data (Runtime, Downtime, Quality, Prod)</td>
                            </tr>
                            <tr class="border-b border-slate-800/50 hover:bg-white/5 transition">
                                <td class="py-3 mono text-indigo-300">/api/produksi-curing.php</td>
                                <td class="py-3 text-green-500">GET</td>
                                <td class="py-3">Hybrid: Post Production or Read History per Shift</td>
                            </tr>
                            <tr class="border-b border-slate-800/50 hover:bg-white/5 transition">
                                <td class="py-3 mono text-indigo-300">/api/total-produksi.php</td>
                                <td class="py-3 text-green-500">GET</td>
                                <td class="py-3">Calculate Global Plant Output per Shift</td>
                            </tr>
                            <tr class="border-b border-slate-800/50 hover:bg-white/5 transition">
                                <td class="py-3 mono text-indigo-300">/api/mode-fault.php</td>
                                <td class="py-3 text-green-500">GET</td>
                                <td class="py-3">Read Quality Metrics from DB View v_quality_per_shift</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-20 text-center pb-10">
        <p class="text-[10px] mono text-slate-600 font-bold uppercase tracking-[0.4em]">Politeknik Gajah Tunggal &bull; Industrial Backend Solution &bull; UAS 2025</p>
    </footer>

    <script>
        const API = 'api/';

        // Helper: Log ke Terminal
        function logTerm(msg, color = 'text-green-400') {
            const term = document.getElementById('terminal');
            const p = document.createElement('p');
            p.className = color;
            p.innerHTML = `<span class="text-slate-600">[${new Date().toLocaleTimeString()}]</span> > ${msg}`;
            term.appendChild(p);
            term.scrollTop = term.scrollHeight;
        }

        // --- 1. Simulasi Write (Command) ---
        async function apiWrite() {
            const f = new FormData(document.getElementById('form-write'));
            const params = new URLSearchParams(f);
            params.append('shift', document.getElementById('g-shift').value);
            params.append('tanggal', document.getElementById('g-tanggal').value);

            logTerm(`Executing Write: ${params.toString()}`, 'text-indigo-400');
            const res = await fetch(`${API}kondisi-mesin-write.php?${params}`);
            const data = await res.json();
            logTerm(JSON.stringify(data), data.status === 'success' ? 'text-green-400' : 'text-red-400');
            refreshAll();
        }

        // --- 2. Simulasi Produksi (Post) ---
        async function apiProduction() {
            const f = new FormData(document.getElementById('form-prod'));
            const params = new URLSearchParams(f);
            params.append('shift', document.getElementById('g-shift').value);
            params.append('tanggal', document.getElementById('g-tanggal').value);

            logTerm(`Posting Production...`, 'text-green-400');
            const res = await fetch(`${API}produksi-curing.php?${params}`);
            const data = await res.json();
            logTerm(JSON.stringify(data));
            document.getElementById('form-prod').reset();
            refreshAll();
        }

        // --- 3. Simulasi Produksi (Read) ---
        async function apiProductionRead() {
            const shift = document.getElementById('g-shift').value;
            const tanggal = document.getElementById('g-tanggal').value;
            const mc = "MC-01";

            logTerm(`Fetching Production History for ${mc} (${shift})`, 'text-yellow-400');
            const res = await fetch(`${API}produksi-curing.php?machine_id=${mc}&shift=${shift}&tanggal=${tanggal}`);
            const data = await res.json();
            logTerm(JSON.stringify(data));
        }

        // --- 4. Simulasi Total Global ---
        async function apiTotalGlobal() {
            const shift = document.getElementById('g-shift').value;
            const tanggal = document.getElementById('g-tanggal').value;

            logTerm(`Fetching Plant Global Total...`, 'text-white');
            const res = await fetch(`${API}total-produksi.php?shift=${shift}&tanggal=${tanggal}`);
            const data = await res.json();
            logTerm(JSON.stringify(data), 'text-indigo-300 font-bold');
        }

        // --- 5. Sync Monitoring & Analytics ---
        async function refreshAll() {
            const shift = document.getElementById('g-shift').value;
            const tanggal = document.getElementById('g-tanggal').value;
            const mc = "MC-01";

            try {
                const res = await fetch(`${API}monitoring.php?view=overview&machine_id=${mc}&shift=${shift}&tanggal=${tanggal}`);
                const json = await res.json();
                const d = json.data;

                document.getElementById('m-runtime').innerText = (d.runtime || 0) + 's';
                document.getElementById('m-downtime').innerText = (d.downtime || 0) + 's';
                document.getElementById('m-prod').innerText = d.production || 0;
                document.getElementById('m-quality').innerText = (d.quality || 100) + '%';
            } catch (e) { console.error("Sync Error"); }
        }

        // Boot
        refreshAll();
    </script>
</body>
</html>