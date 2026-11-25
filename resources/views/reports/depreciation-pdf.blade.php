<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 16px; }
        .header p { margin: 5px 0; color: #666; font-size: 11px; }
        .badge-mode { padding: 4px 10px; background: #4f46e5; color: white; border-radius: 4px; font-weight: bold; text-transform: uppercase; margin-top: 10px; display: inline-block; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; table-layout: fixed; }
        th, td { border: 1px solid #ccc; padding: 6px 4px; text-align: left; word-wrap: break-word; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 9px; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .bg-gray { background-color: #f9f9f9; }
        .footer { margin-top: 20px; text-align: right; font-size: 9px; color: #999; }
        .font-bold { font-weight: bold; }
        .text-primary { color: #4f46e5; }
        .text-danger { color: #ef4444; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Penyusutan Aset ({{ ucfirst($mode) }})</h2>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
        <div class="badge-mode">Mode: {{ $mode }}</div>
    </div>

    <table>
        <thead>
            @if($mode == 'comparison')
                <tr>
                    <th style="width: 20%;" rowspan="2">Aset / Barcode</th>
                    <th colspan="2" class="text-center">Komersial</th>
                    <th colspan="2" class="text-center">Fiskal</th>
                    <th style="width: 15%;" rowspan="2" class="text-end">Selisih</th>
                </tr>
                <tr>
                    <th class="text-end">Nilai Buku</th>
                    <th class="text-center">Umur</th>
                    <th class="text-end">Nilai Buku</th>
                    <th class="text-center">Umur</th>
                </tr>
            @else
                <tr>
                    <th style="width: 12%;">Barcode</th>
                    <th style="width: 20%;">Nama Aset</th>
                    <th style="width: 12%;">Kategori</th>
                    <th style="width: 10%;">Tgl Beli</th>
                    <th style="width: 13%;" class="text-end">Harga Perolehan</th>
                    <th style="width: 8%;" class="text-center">Umur</th>
                    <th style="width: 12%;" class="text-end">Akumulasi</th>
                    <th style="width: 13%;" class="text-end">Nilai Buku</th>
                </tr>
            @endif
        </thead>
        <tbody>
            @php 
                $totalPurchase = 0; 
                $totalAccumulated = 0; 
                $totalBookValue = 0; 
            @endphp
            @foreach($items as $item)
                @php
                    $commValue = (float) $item->commercial_value;
                    $fiscalValue = (float) $item->fiscal_value;
                    $currentValue = $mode == 'fiscal' ? $fiscalValue : $commValue;
                    $accumulated = (float) $item->purchase_price - $currentValue;
                    
                    $totalPurchase += (float) $item->purchase_price;
                    $totalAccumulated += $accumulated;
                    $totalBookValue += $currentValue;
                @endphp
                <tr>
                    <td>
                        <div class="font-bold text-primary">{{ $item->item_code }}</div>
                        @if($mode == 'comparison') <div style="font-size: 8px;">{{ $item->asset->name }}</div> @endif
                    </td>
                    
                    @if($mode == 'comparison')
                        <td class="text-end">Rp {{ number_format($commValue, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->useful_life_months }} bln</td>
                        <td class="text-end">Rp {{ number_format($fiscalValue, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->fiscal_useful_life }} bln</td>
                        <td class="text-end font-bold">Rp {{ number_format($commValue - $fiscalValue, 0, ',', '.') }}</td>
                    @else
                        <td>{{ $item->asset->name }}</td>
                        <td>{{ $item->asset->category->name }}</td>
                        <td class="text-center">{{ $item->purchase_date->format('d/m/Y') }}</td>
                        <td class="text-end">Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $mode == 'fiscal' ? $item->fiscal_useful_life : $item->useful_life_months }}</td>
                        <td class="text-end text-danger">-Rp {{ number_format($accumulated, 0, ',', '.') }}</td>
                        <td class="text-end font-bold">Rp {{ number_format($currentValue, 0, ',', '.') }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
        @if($mode != 'comparison')
        <tfoot>
            <tr class="bg-gray font-bold">
                <td colspan="4" class="text-end">TOTAL</td>
                <td class="text-end">Rp {{ number_format($totalPurchase, 0, ',', '.') }}</td>
                <td></td>
                <td class="text-end text-danger">-Rp {{ number_format($totalAccumulated, 0, ',', '.') }}</td>
                <td class="text-end text-primary">Rp {{ number_format($totalBookValue, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        Halaman 1 dari 1 - Sistem Manajemen Aset
    </div>
</body>
</html>
