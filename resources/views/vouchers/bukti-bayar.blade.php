<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pasien</title>
    <style>
        @page {
            size: A5;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        .container {
            width: 100%;
            /* max-width: 148mm; */
            /* padding: 10px; */
            /* margin: 0 auto; */
        }

        h1 {
            text-align: left;
            font-size: 20px;
            text-transform: uppercase;
            /* margin-bottom: 10px; */
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 2px;
            border: 1px solid #ccc;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .no-border {
            border: none !important;
        }

        .right-align {
            text-align: right;
        }

        .center-align {
            text-align: center;
        }

        .footer {
            /* margin-top: 20px; */
            font-size: 10px;
            text-align: center;
        }

        .signature-table {
            width: 100%;
            margin-top: 40px;
        }

        .signature-table td {
            width: 50%;
            padding: 10px;
            text-align: center;
            vertical-align: bottom;
        }

        .bold {
            font-weight: bold;
        }

        .header-transaksi {
            text-align:center;
            margin-top: 8px;
            margin-bottom: 4px;
            font-size: 16px;
        }
        .img img {
            width: 80px; /* Set the width to make the image smaller */
            height: auto; /* Ensure the height is automatically adjusted */
        }
    </style>
</head>

<body>
    <div class="container">
        <table class="no-border">
            <tr class="no-border">
                <td class="no-border"><div class="img"><img src="images/logo/cmc-logo.png" alt=""></div></td>
                <td class="no-border right-align">Jl. Prof. DR. Satrio, RT.18/RW.4, Kuningan, Karet Kuningan, Kecamatan Setiabudi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12940</td>
            </tr>
        </table>
        <hr>
        <table class="no-border">
            <tr class="no-border">
                <td class="no-border">
                    <h1>Kwitansi Pasien</h1>
                </td>
                <td class="no-border right-align"><strong>Nomor Tagihan:</strong> OP-00000</td>
            </tr>
        </table>
        <hr>

        <table class="no-border">
            <tr class="no-border">
                <td class="no-border"><strong>Nama Pasien:</strong> {{ $patient->name }}</td>
                <td class="no-border right-align"><strong>Tanggal Registrasi:</strong> {{ $purchase_date }}</td>
            </tr>
            <tr class="no-border">
                <td class="no-border"><strong>Nomor RM:</strong> RM-000</td>
                <td class="no-border right-align"><strong>Outpatient Poliklinik</strong></td>
            </tr>
            <tr class="no-border">
                <td class="no-border"><strong>Tipe Kunjungan:</strong> AKUPUNKTUR</td>
                
            </tr>
        </table>

        {{-- <h3 style="text-align: center">Detail Transaksi</h3> --}}
        <div class="header-transaksi bold">Detail Transaksi</div>
        <table>
            <thead>
                <tr>
                    <th class="center-align">Deskripsi</th>
                    <th class="center-align">Jumlah</th>
                    <th class="right-align">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$package_name}}</td>
                    <td class="center-align">1</td>
                    <td class="right-align">{{$voucher_price}}</td>
                </tr>
            </tbody>
        </table>


        <div class="header-transaksi bold">Pembayaran</div>
        <table>
            <thead>
                <th class="center-align">Tanggal</th>
                <th class="center-align">Metode Pembayaran</th>
                <th class="right-align">Total Bayar</th>
            </thead>
            <tbody>
                <tr>
                    <td> {{ $purchase_date }}</td>
                    <td> {{$payment_method}} - {{$no_card}}</td>
                    <td class="right-align"> {{$voucher_price}}</td>
                </tr>
            </tbody>
        </table>
        <div style="font-size: 12px; margin-top:2px"><strong>Terbilang:</strong> {{$terbilang}} rupiah</div>

        <table class="signature-table">
            <tr class="no-border">
                <td class="no-border">
                    <div style="margin-bottom: 60px"><strong>Pasien</strong></div>
                    <p>( {{ $patient->name }} )</p>
                </td>
                <td class="no-border">
                    <div class="footer">Jakarta, {{ \Carbon\Carbon::parse($purchase_date)->format('d M Y') }}</div>
                    <div style="margin-bottom: 60px"><strong>Kasir</strong></div>
                    <p>( {{ auth()->user()->name }} )</p>
                </td>
            </tr>
        </table>

    </div>
</body>

</html>
