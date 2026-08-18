<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quote {{ $quote['id'] ?? 'Proposal' }} — Ascend Systems</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen py-6 sm:py-12 px-4 sm:px-6 lg:px-8">
    @php
        $rawItems = $quote['items'] ?? ($quote['line_items'] ?? []);
        $lineItems = isset($rawItems['line_items']) && is_array($rawItems['line_items']) ? $rawItems['line_items'] : (is_array($rawItems) ? $rawItems : []);
        $clientName = $quote['client_name'] ?? 'Valued Client';
        $clientPhone = $quote['phone'] ?? ($quote['client_phone'] ?? '');
        $clientEmail = $quote['email'] ?? ($quote['client_email'] ?? '');
        $clientAddress = $quote['address'] ?? ($quote['client_address'] ?? '');
        $quoteId = $quote['id'] ?? ($quote['invoice_number'] ?? 'QT-2026');
        $status = $quote['status'] ?? 'Draft';
        $subtotal = (float) ($quote['subtotal'] ?? 0);
        $discountAmount = (float) ($quote['discount_amount'] ?? 0);
        $tax = (float) ($quote['tax'] ?? 0);
        $total = (float) ($quote['total'] ?? 0);
        $pdfQuery = request()->query('data');
    @endphp

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Top Navigation Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-md shadow-blue-500/20">
                    <i class="fa-light fa-solar-panel text-lg"></i>
                </div>
                <div>
                    <h1 class="text-base font-extrabold text-slate-950 tracking-tight">Ascend Systems Nigeria Limited</h1>
                    <p class="text-xs text-slate-500 font-medium">Commercial Solar & Automation Proposal Portal</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @if ($pdfQuery)
                    <a href="{{ route('portal.quote.pdf', ['data' => $pdfQuery]) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-2xl bg-slate-900 px-4 py-2 text-xs font-bold text-white hover:bg-slate-800 shadow-sm transition">
                        <i class="fa-light fa-file-pdf text-rose-400"></i>Download Quote PDF
                    </a>
                    <a href="{{ route('portal.quote.warranty.pdf', ['data' => $pdfQuery]) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-2xl bg-blue-50 text-blue-700 border border-blue-200 px-3.5 py-2 text-xs font-bold hover:bg-blue-100 transition">
                        <i class="fa-light fa-award"></i>5-Yr Warranty
                    </a>
                @endif
            </div>
        </div>

        <!-- Main Quote Document Card -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-10 space-y-8">
            <!-- Header section -->
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 border-b border-slate-100 pb-8">
                <div>
                    <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider {{ match(strtolower($status)) {
                        'accepted' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                        'converted' => 'bg-purple-50 text-purple-700 border border-purple-200',
                        'sent' => 'bg-blue-50 text-blue-700 border border-blue-200',
                        default => 'bg-amber-50 text-amber-700 border border-amber-200',
                    } }}">
                        <i class="fa-solid fa-circle text-[8px]"></i>Status: {{ ucfirst($status) }}
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-950 mt-3 tracking-tight">Official Quotation Proposal</h2>
                    <p class="text-sm font-mono font-bold text-blue-600 mt-1">#{{ $quoteId }}</p>
                </div>
                <div class="text-left sm:text-right text-xs space-y-1">
                    <p class="text-slate-400 font-semibold uppercase">Proposal Validity</p>
                    <p class="font-bold text-slate-800 text-sm"><i class="fa-light fa-calendar mr-1.5 text-blue-500"></i>{{ isset($quote['valid_until']) ? date('M d, Y', strtotime($quote['valid_until'])) : date('M d, Y', strtotime('+14 days')) }}</p>
                    <p class="text-slate-400 text-[11px] mt-1">Issued: {{ isset($quote['created_at']) ? date('M d, Y', strtotime($quote['created_at'])) : date('M d, Y') }}</p>
                </div>
            </div>

            <!-- Client & Provider Meta Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <div>
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Prepared For (Client):</span>
                    <h3 class="text-base font-bold text-slate-900 mt-1">{{ $clientName }}</h3>
                    @if ($clientAddress)
                        <p class="text-xs text-slate-600 mt-1"><i class="fa-light fa-location-dot mr-1.5 text-slate-400"></i>{{ $clientAddress }}</p>
                    @endif
                    @if ($clientPhone || $clientEmail)
                        <div class="text-xs text-slate-600 mt-1 flex flex-wrap gap-3">
                            @if ($clientPhone) <span><i class="fa-light fa-phone mr-1 text-slate-400"></i>{{ $clientPhone }}</span> @endif
                            @if ($clientEmail) <span><i class="fa-light fa-envelope mr-1 text-slate-400"></i>{{ $clientEmail }}</span> @endif
                        </div>
                    @endif
                </div>
                <div class="md:text-right">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Turnkey Provider:</span>
                    <h3 class="text-base font-bold text-slate-900 mt-1">Ascend Systems Nigeria Ltd</h3>
                    <p class="text-xs text-slate-600 mt-1">Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja</p>
                    <p class="text-xs text-slate-600 mt-1"><i class="fa-light fa-phone mr-1 text-slate-400"></i>+234 811 763 3020 &nbsp;|&nbsp; info@ascendsystems.ng</p>
                </div>
            </div>

            <!-- Line Items Table -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-900">Equipment Specifications & Scope</h4>
                    <span class="text-xs font-bold text-slate-400">{{ count($lineItems) }} Line Items</span>
                </div>
                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-900 text-white font-bold uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="p-3.5">SKU / Code</th>
                                <th class="p-3.5">Description</th>
                                <th class="p-3.5 text-center">Qty</th>
                                <th class="p-3.5 text-right">Unit Price (₦)</th>
                                <th class="p-3.5 text-right">Amount (₦)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @if (!empty($lineItems) && count($lineItems) > 0)
                                @foreach ($lineItems as $item)
                                    @php
                                        $qty = (int) ($item['qty'] ?? ($item['quantity'] ?? 1));
                                        $price = (float) ($item['unit_price'] ?? 0);
                                        $amt = (float) ($item['amount'] ?? ($qty * $price));
                                        $desc = !empty($item['description']) ? $item['description'] : (!empty($item['name']) ? $item['name'] : 'Line Item');
                                        $sku = !empty($item['sku']) ? $item['sku'] : 'GEN-ITEM';
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="p-3.5 font-mono font-bold text-slate-600">{{ $sku }}</td>
                                        <td class="p-3.5 font-semibold text-slate-900">{{ $desc }}</td>
                                        <td class="p-3.5 text-center font-bold">{{ $qty }}</td>
                                        <td class="p-3.5 text-right font-medium">₦{{ number_format($price, 2) }}</td>
                                        <td class="p-3.5 text-right font-bold text-slate-950 font-mono">₦{{ number_format($amt, 2) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="p-3.5 font-mono text-slate-500">SRV-SOLAR</td>
                                    <td class="p-3.5 font-semibold">Solar Power Solution & Turnkey Installation Bundle</td>
                                    <td class="p-3.5 text-center font-bold">1</td>
                                    <td class="p-3.5 text-right">₦{{ number_format($total, 2) }}</td>
                                    <td class="p-3.5 text-right font-bold font-mono">₦{{ number_format($total, 2) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Financial Summary & Bank Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start pt-4 border-t border-slate-100">
                <!-- Payment Instructions -->
                <div class="rounded-2xl border border-dashed border-slate-300 p-5 bg-slate-50 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-900 uppercase tracking-wide flex items-center gap-1.5">
                            <i class="fa-light fa-building-columns text-blue-600"></i>Official Bank Transfer Account
                        </span>
                    </div>
                    <div class="text-xs space-y-1 text-slate-700">
                        <p><strong class="text-slate-900">Bank:</strong> Access Bank Nigeria</p>
                        <p><strong class="text-slate-900">Account Name:</strong> Ascend Systems Nigeria Ltd</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="font-mono font-black text-sm bg-white px-3 py-1 rounded-xl border border-slate-200 text-blue-700">0129481029</span>
                            <button type="button" onclick="navigator.clipboard.writeText('0129481029'); alert('Account Number Copied: 0129481029');" class="text-xs text-slate-500 hover:text-blue-600 font-bold transition">
                                <i class="fa-light fa-copy mr-1"></i>Copy
                            </button>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-2">Please quote Reference <strong class="font-mono">#{{ $quoteId }}</strong> on transfer.</p>
                    </div>
                </div>

                <!-- Totals Breakdown -->
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between py-1 text-slate-600">
                        <span>Gross Line Subtotal:</span>
                        <span class="font-semibold font-mono">₦{{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if ($discountAmount > 0)
                        <div class="flex justify-between py-1 text-emerald-600 font-medium">
                            <span>Commercial Discount Applied:</span>
                            <span class="font-bold font-mono">- ₦{{ number_format($discountAmount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between py-1 text-slate-600">
                        <span>VAT (7.5% Standard):</span>
                        <span class="font-semibold font-mono">₦{{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-t-2 border-slate-900 text-base font-black text-slate-950">
                        <span>Grand Quoted Total:</span>
                        <span class="text-blue-600 font-mono">₦{{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Commercial Guidelines -->
            @if (!empty($quote['notes']))
                <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100 text-xs text-blue-950">
                    <strong class="block text-blue-900 font-bold uppercase tracking-wider text-[11px] mb-1">
                        <i class="fa-light fa-shield-check mr-1.5 text-blue-600"></i>Commercial Terms & Warranty Agreement:
                    </strong>
                    <div class="whitespace-pre-wrap leading-relaxed text-slate-700">{{ $quote['notes'] }}</div>
                </div>
            @endif

            <!-- Interactive Client E-Signature Approval Box -->
            <div class="rounded-3xl border-2 border-emerald-500/30 bg-emerald-50/20 p-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-md shadow-emerald-600/20">
                        <i class="fa-light fa-file-signature text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-950">Client Acceptance & Digital Signature</h3>
                        <p class="text-xs text-slate-600">Draw your signature below to officially accept and confirm this proposal.</p>
                    </div>
                </div>

                <div id="signSection" class="space-y-4">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Signatory Full Name</label>
                            <input type="text" id="signerName" value="{{ $clientName }}" class="w-full rounded-xl border border-slate-300 p-2.5 text-xs font-semibold outline-none focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Designation / Title</label>
                            <input type="text" id="signerTitle" placeholder="e.g. Managing Director / Home Owner" class="w-full rounded-xl border border-slate-300 p-2.5 text-xs font-semibold outline-none focus:border-emerald-500">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold uppercase text-slate-600">Draw Signature (Touch / Mouse):</label>
                            <button type="button" onclick="clearSignature()" class="text-xs text-rose-600 font-bold hover:underline">
                                <i class="fa-light fa-eraser mr-1"></i>Clear Pad
                            </button>
                        </div>
                        <div class="rounded-2xl border-2 border-dashed border-slate-300 bg-white p-1 overflow-hidden">
                            <canvas id="sigCanvas" height="130" class="w-full cursor-crosshair touch-none"></canvas>
                        </div>
                    </div>

                    <button type="button" onclick="submitClientAcceptance()" id="acceptBtn" class="w-full py-3.5 rounded-2xl bg-emerald-600 text-white font-black text-sm shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 transition active:scale-98 flex items-center justify-center gap-2">
                        <i class="fa-light fa-circle-check text-lg"></i>Accept Proposal & Confirm Order
                    </button>
                </div>

                <div id="acceptedSuccess" class="hidden p-6 rounded-2xl bg-emerald-100 border border-emerald-300 text-center space-y-2">
                    <div class="h-12 w-12 rounded-full bg-emerald-600 text-white flex items-center justify-center mx-auto text-xl">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <h4 class="text-base font-black text-emerald-950">Proposal Accepted & Confirmed!</h4>
                    <p class="text-xs text-emerald-800">Thank you! Your project kickoff order has been acknowledged. Our engineering team has been notified for procurement and field staging.</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-slate-400 pb-8">
            &copy; {{ date('Y') }} Ascend Systems Nigeria Ltd. All Rights Reserved. &nbsp;|&nbsp; Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT.
        </div>
    </div>

    <!-- Signature Pad Logic -->
    <script>
        const canvas = document.getElementById('sigCanvas');
        const ctx = canvas.getContext('2d');
        let isDrawing = false;

        function resizeCanvas() {
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width;
            canvas.height = 130;
            ctx.lineWidth = 2.5;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#0f172a';
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return { x: clientX - rect.left, y: clientY - rect.top };
        }

        canvas.addEventListener('mousedown', (e) => {
            isDrawing = true;
            const pos = getPos(e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
        });
        canvas.addEventListener('mousemove', (e) => {
            if (!isDrawing) return;
            const pos = getPos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
        });
        window.addEventListener('mouseup', () => isDrawing = false);

        canvas.addEventListener('touchstart', (e) => {
            isDrawing = true;
            const pos = getPos(e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
            e.preventDefault();
        }, { passive: false });
        canvas.addEventListener('touchmove', (e) => {
            if (!isDrawing) return;
            const pos = getPos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            e.preventDefault();
        }, { passive: false });
        window.addEventListener('touchend', () => isDrawing = false);

        function clearSignature() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        function submitClientAcceptance() {
            const name = document.getElementById('signerName').value.trim();
            if (!name) {
                alert('Please provide signatory full name.');
                return;
            }
            document.getElementById('signSection').classList.add('hidden');
            document.getElementById('acceptedSuccess').classList.remove('hidden');
        }
    </script>
</body>
</html>
