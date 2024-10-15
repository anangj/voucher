<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher Sent - Clinic</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f8fa;
            margin: 0;
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .email-header {
            background-color: #1e3a8a;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
        }

        .email-content {
            padding: 20px;
            color: #333333;
        }

        .email-content h2 {
            color: #1e3a8a;
            margin-bottom: 10px;
        }

        .details-section {
            margin-bottom: 20px;
        }

        .details-section p {
            margin: 5px 0;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            background-color: #1e3a8a;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }

        .service-info {
            margin: 20px 0;
        }

        .purchase-info {
            background-color: #f9fafb;
            padding: 10px;
            border-radius: 4px;
        }

        .purchase-info h3 {
            margin: 0 0 10px 0;
            color: #1e3a8a;
        }

        .purchase-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .purchase-info th,
        .purchase-info td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .purchase-info th {
            background-color: #f3f4f6;
        }

        .footer {
            text-align: center;
            color: #6b7280;
            padding: 10px;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer a {
            color: #1e3a8a;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>Clinic Voucher Confirmation</h1>
        </div>

        <!-- Email Content -->
        <div class="email-content">
            <!-- Voucher Details -->
            {{-- <h2>Hello, {{ $patient->name }}!</h2> --}}
            <p>Your voucher for clinic services has been successfully issued. Below are the details of your purchase.</p>

            <!-- Voucher Summary -->
            <div class="details-section">
                <p><strong>Order Number:</strong> </p>
                <p><strong>Service:</strong> </p>
                <p><strong>Purchase Date:</strong> </p>
                <p><strong>Expiry Date:</strong> </p>
            </div>

            <a href="#" class="btn">Download Voucher</a>

            <!-- Service Information -->
            <div class="service-info">
                <h3>Service Information</h3>
                <p><strong>Clinic Name:</strong> Ciputra Medical Center</p>
                <p><strong>Service:</strong> </p>
                <p><strong>Service Date:</strong> </p>
                <p><strong>Clinic Location:</strong> </p>
            </div>

            <!-- Purchase Details -->
            <div class="purchase-info">
                <h3>Purchase Summary</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td>Rp </td>
                            <td>1</td>
                            <td>Rp </td>
                        </tr>
                        <tr>
                            <td colspan="3"><strong>Total Amount</strong></td>
                            <td><strong>Rp </strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>For any questions, feel free to contact us at <a href="mailto:support@example.com">support@example.com</a></p>
            <p>&copy; 2024 Ciputra Medical Center</p>
        </div>
    </div>
</body>

</html>
