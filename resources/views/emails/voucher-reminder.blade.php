<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher Expiry Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            background-color: #007bff;
            padding: 10px 0;
            color: white;
            border-radius: 8px 8px 0 0;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            margin: 20px 0;
            text-align: center;
        }

        .content p {
            font-size: 16px;
            color: #333;
        }

        .content strong {
            color: #007bff;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #888;
            font-size: 12px;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Voucher Expiry Reminder</h1>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $data->patient->name }}</strong>,</p>

            <p>We would like to remind you that your voucher <strong>{{$data->paketVoucher->name}}</strong> for  is set to expire on
                <strong>{{ \Carbon\Carbon::parse($data->expiry_date)->format('Y-m-d') }}</strong>.
            </p>

            <p>Please make sure to redeem your voucher before the expiration date to avoid losing it.</p>

        </div>

        <div class="footer">
            <p>If you have any questions, feel free to contact us at support@gmail.com.</p>
        </div>
    </div>
</body>

</html>
