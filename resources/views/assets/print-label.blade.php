<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Asset Labels</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @page {
            margin: 0;
            size: 50mm 20mm;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: white;
        }
        .label-container {
            width: 50mm;
            height: 20mm;
            padding: 1.5mm 2mm;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            gap: 3mm;
            overflow: hidden;
            page-break-after: always;
            position: relative;
        }
        .qr-code {
            flex-shrink: 0;
            width: 16mm;
            height: 16mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .qr-code svg {
            width: 100% !important;
            height: 100% !important;
        }
        .info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            overflow: hidden;
        }
        .text-group {
            display: flex;
            flex-direction: column;
        }
        .asset-name {
            font-size: 8px;
            font-weight: 700;
            margin: 0;
            line-height: 1.1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            color: #000;
        }
        .asset-code {
            font-size: 7px;
            font-weight: 400;
            margin: 1px 0 2px 0;
            color: #333;
            letter-spacing: 0.2px;
        }
        .barcode-container {
            margin-top: auto;
            width: 100%;
            height: 6mm;
            display: flex;
            align-items: flex-end;
        }
        .barcode-container svg {
            width: 100% !important;
            height: 100% !important;
        }
        
        /* Preview Helper */
        @media screen {
            body {
                background-color: #f3f4f6;
                padding: 40px;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 20px;
            }
            .label-container {
                background-color: white;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
                border: 1px solid #e5e7eb;
            }
            .no-print {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                padding: 12px;
                background: white;
                border-bottom: 1px solid #e5e7eb;
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 15px;
                z-index: 100;
            }
            .btn-print {
                background-color: #4f46e5;
                color: white;
                padding: 8px 20px;
                border-radius: 6px;
                font-weight: 600;
                font-size: 14px;
                border: none;
                cursor: pointer;
                transition: background 0.2s;
            }
            .btn-print:hover {
                background-color: #4338ca;
            }
            .print-instructions {
                font-size: 12px;
                color: #6b7280;
            }
        }
        
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
                background-color: white;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">Cetak Label</button>
        <span class="print-instructions">Ukuran: 50mm x 20mm. Pilih "Destination: Save as PDF" atau Printer Label Anda.</span>
    </div>

    @foreach($assets as $asset)
        <div class="label-container">
            <div class="qr-code">
                {!! QrCode::size(100)->margin(0)->generate(route('assets.show', $asset)) !!}
            </div>
            <div class="info">
                <div class="text-group">
                    <p class="asset-name">{{ $asset->name }}</p>
                    <p class="asset-code">{{ $asset->asset_code }}</p>
                </div>
                <div class="barcode-container">
                    {!! DNS1D::getBarcodeSVG($asset->asset_code, 'C128', 1, 25) !!}
                </div>
            </div>
        </div>
    @endforeach
</body>
</html>
