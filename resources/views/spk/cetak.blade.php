<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Rekomendasi Lokasi Cabang Ritel Baru - SPK ROC & SAW</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            color: #222;
            margin: 25px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .header p {
            margin: 4px 0 0 0;
            font-size: 10pt;
            color: #555;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 8px;
            color: #1e3a8a;
            border-left: 4px solid #1e3a8a;
            padding-left: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9.5pt;
        }
        table, th, td {
            border: 1px solid #bbb;
        }
        th {
            background-color: #f1f5f9;
            padding: 7px 6px;
            text-align: center;
        }
        td {
            padding: 6px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .highlight { background-color: #fef3c7; }
        .footer-sign {
            margin-top: 40px;
            width: 100%;
        }
        .footer-sign td {
            border: none;
            padding: 10px;
        }
        @media print {
            .no-print { display: none; }
            body { margin: 10mm; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #2563eb; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
            🖨️ Cetak / Simpan sebagai PDF
        </button>
    </div>

    <div class="header">
        <h2>Laporan Hasil Rekomendasi Lokasi Cabang Ritel Baru</h2>
        <p>Sistem Pendukung Keputusan Metode Integrasi ROC (Rank Order Centroid) & SAW (Simple Additive Weighting)</p>
        <p>Tanggal Cetak: {{ date('d F Y, H:i') }}</p>
    </div>

    <div class="section-title">1. Hasil Akhir Rekomendasi & Perankingan Lokasi</div>
    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Peringkat</th>
                <th style="width: 35%;">Nama Lokasi Cabang</th>
                <th style="width: 35%;">Alamat</th>
                <th style="width: 20%;">Nilai Preferensi ($V_i$)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($calculation['rankings'] as $res)
            <tr class="{{ $res['ranking'] === 1 ? 'highlight font-bold' : '' }}">
                <td class="text-center">{{ $res['ranking'] }}</td>
                <td>{{ $res['nama_lokasi'] }} {{ $res['ranking'] === 1 ? '(Rekomendasi Utama)' : '' }}</td>
                <td>{{ $res['alamat'] }}</td>
                <td class="text-right">{{ number_format($res['nilai_preferensi'], 4) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">2. Bobot Kriteria ROC ($W_j$)</div>
    <table>
        <thead>
            <tr>
                <th>Peringkat Prioritas</th>
                <th>Nama Kriteria</th>
                <th>Jenis / Atribut</th>
                <th>Bobot ROC ($W_j$)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($calculation['kriterias'] as $krit)
            <tr>
                <td class="text-center">#{{ $krit->peringkat }}</td>
                <td>{{ $krit->nama_kriteria }}</td>
                <td class="text-center">{{ strtoupper($krit->jenis) }}</td>
                <td class="text-right">{{ number_format($krit->bobot_roc, 4) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">3. Matriks Normalisasi ($R_{ij}$)</div>
    <table>
        <thead>
            <tr>
                <th>Alternatif Lokasi</th>
                @foreach($calculation['kriterias'] as $krit)
                    <th>{{ $krit->nama_kriteria }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($calculation['alternatifs'] as $alt)
            <tr>
                <td>{{ $alt->nama_lokasi }}</td>
                @foreach($calculation['kriterias'] as $krit)
                    <td class="text-center">{{ number_format($calculation['matrix_r'][$alt->id][$krit->id], 4) }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer-sign">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: center;">
                <p>Mengetahui,</p>
                <p style="margin-bottom: 60px;">Pimpinan Ritel</p>
                <p style="font-weight: bold; text-decoration: underline;">( ........................................ )</p>
            </td>
        </tr>
    </table>
</body>
</html>
