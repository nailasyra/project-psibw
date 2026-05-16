<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal UNRI</title>
    <style>
        /* Reset dasar dan penyesuaian font */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6; /* Warna background abu-abu terang */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Container form (Card) */
        .login-card {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Efek bayangan halus */
            width: 100%;
            max-width: 350px;
        }

        .login-card h2 {
            text-align: center;
            color: #333333;
            margin-bottom: 30px;
            font-size: 24px;
        }

        /* Styling untuk input dan label */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555555;
            font-size: 14px;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cccccc;
            border-radius: 6px;
            box-sizing: border-box; /* Memastikan padding tidak melebarkan input */
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        /* Efek ketika input diklik (fokus) */
        .form-group input:focus {
            border-color: #0056b3; /* Warna aksen biru yang profesional */
            outline: none;
            box-shadow: 0 0 5px rgba(0, 86, 179, 0.2);
        }

        /* Styling tombol */
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #0056b3; /* Warna biru profesional */
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: #004494; /* Warna biru lebih gelap saat kursor diarahkan */
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>Login Portal UNRI</h2>

        <form action="proses_login.php" method="POST">
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" required>
            </div>

            <div class="form-group">
                <label for="pass">Password</label>
                <input type="password" id="pass" name="pass" placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="btn-login">Login</button>

        </form>
    </div>

</body>
</html>