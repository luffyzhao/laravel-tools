<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/admin/iview.css">
    <link rel="stylesheet" href="/css/admin/app.css">
    <title>管理系统</title>
</head>
<body>
<div id="app">

</div>
</body>
<script src="/js/admin/manifest.js"></script>
<script src="/js/admin/vendor.js"></script>
<script src="/js/admin/app.js"></script>
</html>