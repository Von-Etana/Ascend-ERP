<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field Operations & Commissioning Portal — Ascend Systems</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 antialiased min-h-screen py-4 sm:py-8 px-3 sm:px-6">
    @php
        $rawItems = $quote['items'] ?? ($quote['line_items'] ?? []);
        $lineItems = isset($rawItems['line_items']) && is_array($rawItems['line_items']) ? $rawItems['line_items'] : (is_array($rawItems) ? $rawItems : []);
        $clientName = $quote['client_name'] ?? 'Client Installation Site';
        $clientPhone = $quote['phone'] ?? ($quote['client_phone'] ?? '+234 800 000 0000');
        $clientAddress = $quote['address'] ?? ($quote['client_address'] ?? 'Site Address');
        $quoteId = $quote['id'] ?? ($quote['invoice_number'] ?? 'QT-2026');
        $pdfQuery = request()->query('data');
    @endphp

    <div class="max-w-3xl mx-auto space-y-4">
        <!-- Top App Bar -->
        <div class="bg-slate-800/90 border border-slate-700/80 backdrop-blur rounded-3xl p-4 sm:p-5 flex items-center justify-between shadow-xl">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-md shadow-blue-500/20 text-lg">
                    <i class="fa-light fa-helmet-safety"></i>
                </div>
                <div>
                    <h1 class="text-sm sm:text-base font-extrabold text-white">Ascend Field Operations Studio</h1>
                    <p class="text-[11px] text-blue-400 font-mono">WO #{{ $quoteId }} &bull; {{ $clientName }}</p>
                </div>
            </div>
            @if ($pdfQuery)
                <div class="flex items-center gap-2">
                    <a href="{{ route('portal.quote.inspection.pdf', ['data' => $pdfQuery]) }}" target="_blank" class="rounded-xl bg-blue-600/20 text-blue-400 border border-blue-500/30 px-3 py-1.5 text-xs font-bold hover:bg-blue-600/30 transition flex items-center gap-1">
                        <i class="fa-light fa-file-pdf"></i>Inspection PDF
                    </a>
                </div>
            @endif
        </div>

        <!-- Tab Switcher -->
        <div class="grid grid-cols-2 gap-2 bg-slate-800/60 p-1.5 rounded-2xl border border-slate-700">
            <button type="button" onclick="switchStage(1)" id="tab1" class="py-2.5 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5 bg-blue-600 text-white shadow-md">
                <i class="fa-light fa-clipboard-check"></i>Stage 1: Site Inspection
            </button>
            <button type="button" onclick="switchStage(2)" id="tab2" class="py-2.5 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5 text-slate-400 hover:text-white">
                <i class="fa-light fa-bolt-lightning"></i>Stage 2: Commissioning
            </button>
        </div>

        <!-- STAGE 1: PRE-INSTALLATION SITE INSPECTION -->
        <div id="stage1" class="bg-slate-800 border border-slate-700 rounded-3xl p-5 sm:p-7 space-y-5 shadow-xl">
            <div class="border-b border-slate-700 pb-3">
                <h2 class="text-base font-black text-white flex items-center gap-2">
                    <span class="flex h-6 w-6 rounded-full bg-blue-500/20 text-blue-400 items-center justify-center text-xs">1</span>
                    Pre-Installation Technical Site Audit
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Perform physical inspection of roof, DB wiring, and inverter enclosure before staging hardware.</p>
            </div>

            <!-- Site & Customer Summary -->
            <div class="bg-slate-900/60 p-3.5 rounded-2xl border border-slate-700/50 text-xs space-y-1">
                <p><span class="text-slate-400">Client:</span> <strong class="text-white">{{ $clientName }}</strong></p>
                <p><span class="text-slate-400">Site Location:</span> <span class="text-slate-200">{{ $clientAddress }}</span></p>
                <p><span class="text-slate-400">Phone:</span> <span class="text-slate-200">{{ $clientPhone }}</span></p>
            </div>

            <form id="inspectionForm" onsubmit="event.preventDefault(); alert('Site inspection details recorded successfully! Ready to generate report.');" class="space-y-4 text-xs">
                <!-- 1. Roof Inspection -->
                <div class="space-y-3 bg-slate-900/40 p-4 rounded-2xl border border-slate-700/50">
                    <div class="text-[11px] font-bold text-blue-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-light fa-solar-panel"></i>Roof Structure & Array Mounting
                    </div>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Roof Covering Material</label>
                            <select class="w-full rounded-xl bg-slate-800 border border-slate-600 p-2.5 text-white outline-none focus:border-blue-500">
                                <option>Aluminium Longspan Sheets</option>
                                <option>Stone-Coated Metal Tile (Steptile / Bond)</option>
                                <option>Concrete Flat Roof Deck (Ballasted Mount)</option>
                                <option>Ground Mount / Carport Canopy</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Roof Azimuth / Orientation</label>
                            <select class="w-full rounded-xl bg-slate-800 border border-slate-600 p-2.5 text-white outline-none focus:border-blue-500">
                                <option>South / South-West Facing (Ideal)</option>
                                <option>East-West Dual Pitch</option>
                                <option>North Facing (Requires 15° Reverse Tilt Kit)</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Shading Assessment</label>
                            <select class="w-full rounded-xl bg-slate-800 border border-slate-600 p-2.5 text-white outline-none focus:border-blue-500">
                                <option>Zero Shade (Full All-Day Sun Exposure)</option>
                                <option>Partial Morning Shade (Trees to Trim)</option>
                                <option>Partial Afternoon Shade (Adjacent High-rise)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Estimated Roof Usable Area (m²)</label>
                            <input type="text" value="Approx. 75 m²" class="w-full rounded-xl bg-slate-800 border border-slate-600 p-2.5 text-white outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- 2. Electrical Audit -->
                <div class="space-y-3 bg-slate-900/40 p-4 rounded-2xl border border-slate-700/50">
                    <div class="text-[11px] font-bold text-amber-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-light fa-plug-circle-bolt"></i>Electrical Distribution & Cable Run
                    </div>
                    <div class="grid sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Main DB Phase Type</label>
                            <select class="w-full rounded-xl bg-slate-800 border border-slate-600 p-2.5 text-white outline-none focus:border-blue-500">
                                <option>Single Phase (230V)</option>
                                <option>Three Phase (415V)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">DC Cable Run (Roof to Inverter)</label>
                            <input type="text" placeholder="e.g. 18 Meters" value="18 Meters (6mm²)" class="w-full rounded-xl bg-slate-800 border border-slate-600 p-2.5 text-white outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">AC Cable Run (Inverter to DB)</label>
                            <input type="text" placeholder="e.g. 5 Meters" value="5 Meters (10mm²)" class="w-full rounded-xl bg-slate-800 border border-slate-600 p-2.5 text-white outline-none focus:border-blue-500">
                        </div>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Earthing Ground Pit Status</label>
                            <select class="w-full rounded-xl bg-slate-800 border border-slate-600 p-2.5 text-white outline-none focus:border-blue-500">
                                <option>Existing Dedicated Earth Pit (< 5.0 Ohms)</option>
                                <option>Requires New Copper Earth Rod Installation</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Generator / ATS Changeover</label>
                            <select class="w-full rounded-xl bg-slate-800 border border-slate-600 p-2.5 text-white outline-none focus:border-blue-500">
                                <option>Automatic Transfer Switch (ATS) present</option>
                                <option>Manual 63A Changeover Switch</option>
                                <option>Direct Inverter Bypass Required</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 3. Site Photo Upload Logs -->
                <div class="space-y-3 bg-slate-900/40 p-4 rounded-2xl border border-slate-700/50">
                    <div class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-light fa-camera"></i>Site Pre-Installation Photo Logs
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-center">
                        <label class="border-2 border-dashed border-slate-700 rounded-2xl p-4 bg-slate-800/50 hover:border-blue-500 cursor-pointer block">
                            <i class="fa-light fa-cloud-arrow-up text-2xl text-blue-400 mb-1 block"></i>
                            <span class="text-[11px] font-bold block text-slate-300">Roof Area Photo</span>
                            <span class="text-[9px] text-slate-500">Tap to capture</span>
                            <input type="file" accept="image/*" class="hidden" onchange="this.previousElementSibling.innerText = 'Uploaded: ' + this.files[0].name">
                        </label>
                        <label class="border-2 border-dashed border-slate-700 rounded-2xl p-4 bg-slate-800/50 hover:border-blue-500 cursor-pointer block">
                            <i class="fa-light fa-cloud-arrow-up text-2xl text-amber-400 mb-1 block"></i>
                            <span class="text-[11px] font-bold block text-slate-300">Main DB Board Photo</span>
                            <span class="text-[9px] text-slate-500">Tap to capture</span>
                            <input type="file" accept="image/*" class="hidden" onchange="this.previousElementSibling.innerText = 'Uploaded: ' + this.files[0].name">
                        </label>
                        <label class="border-2 border-dashed border-slate-700 rounded-2xl p-4 bg-slate-800/50 hover:border-blue-500 cursor-pointer block col-span-2 sm:col-span-1">
                            <i class="fa-light fa-cloud-arrow-up text-2xl text-emerald-400 mb-1 block"></i>
                            <span class="text-[11px] font-bold block text-slate-300">Inverter Room Photo</span>
                            <span class="text-[9px] text-slate-500">Tap to capture</span>
                            <input type="file" accept="image/*" class="hidden" onchange="this.previousElementSibling.innerText = 'Uploaded: ' + this.files[0].name">
                        </label>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit" class="flex-1 py-3 rounded-2xl bg-blue-600 text-white font-bold text-xs shadow-lg shadow-blue-500/20 hover:bg-blue-700 transition flex items-center justify-center gap-1.5">
                        <i class="fa-light fa-floppy-disk"></i>Save Inspection Data
                    </button>
                    @if ($pdfQuery)
                        <a href="{{ route('portal.quote.inspection.pdf', ['data' => $pdfQuery]) }}" target="_blank" class="py-3 px-5 rounded-2xl bg-slate-700 text-slate-200 font-bold text-xs hover:bg-slate-600 transition flex items-center justify-center gap-1.5">
                            <i class="fa-light fa-file-pdf text-rose-400"></i>Download Inspection PDF
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- STAGE 2: COMMISSIONING & HANDOVER -->
        <div id="stage2" class="hidden bg-slate-800 border border-slate-700 rounded-3xl p-5 sm:p-7 space-y-5 shadow-xl">
            <div class="border-b border-slate-700 pb-3">
                <h2 class="text-base font-black text-white flex items-center gap-2">
                    <span class="flex h-6 w-6 rounded-full bg-emerald-500/20 text-emerald-400 items-center justify-center text-xs">2</span>
                    System Energization & Commissioning Sign-Off
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Scan hardware serials, record live electrical readings, and capture customer commissioning acceptance.</p>
            </div>

            <form id="commissionForm" onsubmit="event.preventDefault(); alert('Commissioning sign-off completed! Warranty and Job Card reports updated.');" class="space-y-4 text-xs">
                <!-- Hardware Serials Scanned -->
                <div class="space-y-3 bg-slate-900/40 p-4 rounded-2xl border border-slate-700/50">
                    <div class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-light fa-barcode-read"></i>Hardware Serial Numbers (BOM Scanned)
                    </div>
                    <div class="space-y-2.5">
                        @if (!empty($lineItems))
                            @foreach ($lineItems as $idx => $it)
                                <div class="grid sm:grid-cols-12 gap-2 items-center bg-slate-800/80 p-2.5 rounded-xl border border-slate-700">
                                    <div class="sm:col-span-6 font-semibold text-slate-200">
                                        <span class="text-blue-400 font-mono text-[11px] mr-1">[{{ $it['sku'] ?? 'SLR-EQP' }}]</span>
                                        {{ $it['description'] ?? 'Hardware Item' }}
                                    </div>
                                    <div class="sm:col-span-6">
                                        <input type="text" placeholder="Scan or Type Serial Number..." value="SN-{{ rand(1000000, 9999999) }}-NG" class="w-full rounded-lg bg-slate-900 border border-slate-600 p-1.5 text-xs font-mono text-emerald-400 outline-none focus:border-emerald-500">
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="grid sm:grid-cols-12 gap-2 items-center bg-slate-800/80 p-2.5 rounded-xl border border-slate-700">
                                <div class="sm:col-span-6 font-semibold text-slate-200">Ascend 5.5kVA Inverter + 10.2kWh LiFePO4</div>
                                <div class="sm:col-span-6">
                                    <input type="text" value="SN-8941029-INV / SN-9840120-BAT" class="w-full rounded-lg bg-slate-900 border border-slate-600 p-1.5 text-xs font-mono text-emerald-400 outline-none">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Live Measurements -->
                <div class="space-y-3 bg-slate-900/40 p-4 rounded-2xl border border-slate-700/50">
                    <div class="text-[11px] font-bold text-amber-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-light fa-gauge-max"></i>Live Commissioning Electrical Readings
                    </div>
                    <div class="grid sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Solar PV String Voc (VDC)</label>
                            <input type="text" value="385 VDC (String 1) &bull; 390 VDC (String 2)" class="w-full rounded-xl bg-slate-800 border border-slate-600 p-2.5 text-white outline-none font-mono">
                        </div>
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Battery Bank Resting (VDC)</label>
                            <input type="text" value="53.4 VDC (100% SoC)" class="w-full rounded-xl bg-slate-800 border border-slate-600 p-2.5 text-white outline-none font-mono">
                        </div>
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Earth Ground (Ohms)</label>
                            <input type="text" value="1.8 Ohms (PASS)" class="w-full rounded-xl bg-slate-800 border border-slate-600 p-2.5 text-white outline-none font-mono">
                        </div>
                    </div>
                </div>

                <!-- Customer Sign-Off Pad -->
                <div class="space-y-3 bg-slate-900/40 p-4 rounded-2xl border border-slate-700/50">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-blue-400 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-light fa-file-signature"></i>Customer On-Site Commissioning Acceptance
                        </span>
                        <button type="button" onclick="clearFieldSig()" class="text-[11px] text-rose-400 font-bold hover:underline">Clear</button>
                    </div>
                    <div class="rounded-xl border border-slate-700 bg-slate-900 p-1 overflow-hidden">
                        <canvas id="fieldSigCanvas" height="110" class="w-full cursor-crosshair touch-none"></canvas>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit" class="flex-1 py-3 rounded-2xl bg-emerald-600 text-white font-bold text-xs shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 transition flex items-center justify-center gap-1.5">
                        <i class="fa-light fa-circle-check"></i>Complete Commissioning & Validate 5-Yr Warranty
                    </button>
                    @if ($pdfQuery)
                        <a href="{{ route('portal.quote.warranty.pdf', ['data' => $pdfQuery]) }}" target="_blank" class="py-3 px-5 rounded-2xl bg-slate-700 text-slate-200 font-bold text-xs hover:bg-slate-600 transition flex items-center justify-center gap-1.5">
                            <i class="fa-light fa-award text-amber-400"></i>Warranty Certificate
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="text-center text-xs text-slate-500 py-4">
            Ascend Systems Nigeria Limited &bull; Field Operations Engineering Unit
        </div>
    </div>

    <script>
        function switchStage(stage) {
            if (stage === 1) {
                document.getElementById('stage1').classList.remove('hidden');
                document.getElementById('stage2').classList.add('hidden');
                document.getElementById('tab1').className = 'py-2.5 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5 bg-blue-600 text-white shadow-md';
                document.getElementById('tab2').className = 'py-2.5 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5 text-slate-400 hover:text-white';
            } else {
                document.getElementById('stage1').classList.add('hidden');
                document.getElementById('stage2').classList.remove('hidden');
                document.getElementById('tab2').className = 'py-2.5 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5 bg-emerald-600 text-white shadow-md';
                document.getElementById('tab1').className = 'py-2.5 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5 text-slate-400 hover:text-white';
                setTimeout(resizeFieldCanvas, 100);
            }
        }

        const fieldCanvas = document.getElementById('fieldSigCanvas');
        const fCtx = fieldCanvas.getContext('2d');
        let fDrawing = false;

        function resizeFieldCanvas() {
            const rect = fieldCanvas.getBoundingClientRect();
            fieldCanvas.width = rect.width;
            fieldCanvas.height = 110;
            fCtx.lineWidth = 2.5;
            fCtx.lineCap = 'round';
            fCtx.strokeStyle = '#38bdf8';
        }
        window.addEventListener('resize', resizeFieldCanvas);
        resizeFieldCanvas();

        function getFPos(e) {
            const rect = fieldCanvas.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return { x: clientX - rect.left, y: clientY - rect.top };
        }

        fieldCanvas.addEventListener('mousedown', (e) => {
            fDrawing = true;
            const pos = getFPos(e);
            fCtx.beginPath();
            fCtx.moveTo(pos.x, pos.y);
        });
        fieldCanvas.addEventListener('mousemove', (e) => {
            if (!fDrawing) return;
            const pos = getFPos(e);
            fCtx.lineTo(pos.x, pos.y);
            fCtx.stroke();
        });
        window.addEventListener('mouseup', () => fDrawing = false);

        fieldCanvas.addEventListener('touchstart', (e) => {
            fDrawing = true;
            const pos = getFPos(e);
            fCtx.beginPath();
            fCtx.moveTo(pos.x, pos.y);
            e.preventDefault();
        }, { passive: false });
        fieldCanvas.addEventListener('touchmove', (e) => {
            if (!fDrawing) return;
            const pos = getFPos(e);
            fCtx.lineTo(pos.x, pos.y);
            fCtx.stroke();
            e.preventDefault();
        }, { passive: false });
        window.addEventListener('touchend', () => fDrawing = false);

        function clearFieldSig() {
            fCtx.clearRect(0, 0, fieldCanvas.width, fieldCanvas.height);
        }
    </script>
</body>
</html>
