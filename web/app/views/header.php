<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Тудулист</title>
    <link rel="stylesheet" type="text/css" href="/css/flatly.bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/noty.css" />
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <style type="text/css">
        nav.navbar{
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a href="/" class="navbar-brand">Тудулист</a>
    <?if(isset($_SESSION['authorized'])){?>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/auth/logout">Выйти</a>
            </li>
        </ul>
    </div>
    <?}?>
</nav>