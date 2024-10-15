<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .ticket-container {
            background-color: white;
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            align-items: center;
        }

        .ticket-header img {
            width: 100%;
        }

        .ticket-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border: 1px solid black;
        }

        .ticket-type {
            font-size: 16px;
            font-weight: bold;
            color: red;
        }

        /* .event-info,
        .barcode-section {
            width: 45%;
        } */

        .event-info h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .barcode-section {
            text-align: center;
        }

        .barcode-section img {
            width: 150px;
        }

        .terms-title {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
            color: red;
        }

        .terms-list {
            font-size: 14px;
            margin-bottom: 20px;
        }

        .terms-list li {
            margin-bottom: 10px;
        }

        .contact-info {
            text-align: center;
            margin-top: 20px;
        }

        .contact-info a {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .footer div {
            width: 30%;
        }

        .footer .social-icons img {
            width: 20px;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="ticket-container">
        <!-- Header with logo and info -->
        <div class="ticket-header">
            <img src="data:image/png;base64,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" alt="Loket Logo">
        </div>

        <!-- Event info and barcode -->
        <div class="ticket-details">
            {{-- <div class="event-info">
                <h1>Akupunktur 12</h1>
                <p>Ciputra Medical Center</p>
            </div> --}}
            <div class="barcode-section">
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
                <p>Voucher No {{ $voucher_no }}</p>
                <p>TICKET 1 of 2</p>
            </div>
        </div>

        <!-- Terms and Conditions -->
        <h3 class="terms-title">TERMS & CONDITION</h3>
        <ul class="terms-list">
            <li>Acara ini hanya untuk 17 tahun ke atas, dan mengandung kata-kata dan tema yang kasar/sensitif.</li>
            <li>Wajib menunjukkan kartu identitas yang sah (KTP/Paspor/SIM) pada saat penukaran E-Voucher dengan tiket.</li>
            <li>Registrasi ulang dimulai 2 jam sebelum acara. Pintu masuk ditutup ketika acara berlangsung.</li>
            <li>Pengunjung wajib mematikan handphone dan perangkat elektronik lain selama acara.</li>
            <li>Dilarang membawa makanan/minuman, anak-anak, bayi, atau hewan peliharaan.</li>
            <li>Tiket yang sudah dibeli tidak dapat dikembalikan/dipertukarkan.</li>
            <li>Penyelenggara berhak menolak penonton yang tidak memenuhi syarat dan ketentuan di atas.</li>
            <li>Jika ada pertanyaan lebih lanjut bisa email ke <a href="mailto:support@loket.com">support@loket.com</a>.</li>
        </ul>

        <!-- Footer -->
        <div class="footer">
            <div>WWW.CIPUTRA.COM</div>
            <div>
                <img src="https://image.flaticon.com/icons/png/512/724/724664.png" alt="Phone" width="20px"> +62-21-8060-0822
            </div>
        </div>
    </div>
</body>

</html>
