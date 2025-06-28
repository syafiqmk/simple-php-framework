<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: #35424a;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        .navbar {
            background-color: #333;
            overflow: hidden;
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .navbar .right {
            float: right;
        }

        .content {
            background-color: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn {
            display: inline-block;
            background-color: #35424a;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
        }

        .profile-info {
            margin-bottom: 20px;
        }

        .profile-info p {
            margin: 5px 0;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #35424a;
            color: #fff;
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <h1><?= $title ?></h1>
        </div>
    </header>

    <div class="navbar">
        <div class="container">
            <a href="/">Home</a>
            <a href="/user">Profile</a>
            <a href="/user/logout" class="right">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="content">
            <h2>Welcome, <?= $user['username'] ?>!</h2>

            <div class="profile-info">
                <p><strong>Username:</strong> <?= $user['username'] ?></p>
                <p><strong>Email:</strong> <?= $user['email'] ?></p>
                <p><strong>Member Since:</strong> <?= date('F j, Y', strtotime($user['created_at'])) ?></p>
            </div>

            <a href="#" class="btn">Edit Profile</a>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> Simple PHP MVC Framework</p>
        </div>
    </footer>
</body>

</html>