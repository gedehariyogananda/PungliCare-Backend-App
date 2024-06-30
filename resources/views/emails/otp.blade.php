<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify Your Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            border: 2px solid #000000;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            padding: 15px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
        }

        .header img {
            width: 200px;
            height: auto;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        .content {
            padding: 20px;
        }

        .content p {
            font-size: 16px;
            line-height: 1.5;
        }

        .content h6 {
            font-size: 6px;
            color: red;
            margin-top: 5px;
        }

        .content h5 {
            font-size: 9px;
        }

        .otp {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background-color: #f4f4f4;
            border-radius: 4px;
        }

        .footer {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #555555;
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- <div class="header">
            <img src="{{ asset('storage/bukti-comments/bukticomment1.2.png') }}" alt="Pungli Care">
            <h3>Service Lupa Password PungliCare</h3>
        </div> --}}
        <div class="content">
            <p>Selamat Datang {{ $get_name_user }} !</p>
            <br>
            <p>Silahkan Melakukan Verifikasi Email OTP Untuk Lupa Password Pada Kode Dibawah Ini:</p>
            <div class="otp">{{ $otp }}</div>
            <p>Kode OTP Akan Expired Dalam 10 Menit!</p>
            <br>
            <p>Terima Kasih Telah Mendaftar PungliCare, Mari Ciptakan Lingkungan Kota Yang Bersih Dan Nyaman!</p>
            <h6>*jika kamu tidak merasa melakukan ini, email ini tidak perlu ditanggapi!.</h6>
            <br>
            <h5>Admin PungliCare</h5>
            <h5>PungliCare 2024</h5>
        </div>
        <div class="footer">
            <p>jika kamu menemukan permasalahan, silahkan menghubungi pungli.care@gmail.com.</p>
        </div>
    </div>
</body>

</html>