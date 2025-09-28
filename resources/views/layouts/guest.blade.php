<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MDRRMO Rainfall Monitoring System</title>

    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

    <!-- Tailwind and Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
  /* Full‑screen background image */
        background-image: url('/images/bg3.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;

        font-family: 'Times New Roman', serif;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        }



        .navbar {
            background-color: #003366; /* Dark blue header */
            color: white;
            padding: 10px 20px;
            font-weight: bold;
            font-size: 18px;
            display: flex;
            align-items: center;
        }

        .navbar img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-right: 10px;
        }

        .form-box {
           
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 450px;
            margin: 40px auto;
            color: white;
        }

        .form-box input, .form-box select {
            background-color: gray;
            color: black;
            padding: 10px;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 12px;
            border: none;
        }

        .form-box label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .form-box .btn-login {
            background-color:rgb(0, 0, 0);
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            margin-bottom: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .form-box .btn-register {
            background-color: #2f9e44;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .form-box .btn-login:hover {
            background-color:rgb(231, 231, 231);
        }

        .form-box .btn-register:hover {
            background-color: #29863a;
        }

        .forgot {
            text-align: right;
            font-size: 12px;
            color: #ccc;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="navbar">
        <img src="https://storage.googleapis.com/a1aa/image/990f5c66-0691-4f9c-cc09-31a72304bb56.jpg" alt="Logo">
        <span>MDRRMO Rainfall Monitoring System</span>
    </div>

    <main>
        <div class="form-box">
            {{ $slot }}
        </div>
    </main>
</body>
</html>
