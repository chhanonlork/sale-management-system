<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS System - Sale</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Battambang', sans-serif;
            height: 100vh;
            overflow: hidden;
            /* មិនឱ្យ Scroll ទំព័រធំ */
        }

        .pos-header {
            background: linear-gradient(to right, #1d2b64, #f8cdda);
            /* ឬពណ៌ដែលបងចូលចិត្ត */
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }

        .pos-content {
            height: calc(100vh - 60px);
            /* ដកកម្ពស់ Header ចេញ */
            padding: 15px;
        }
    </style>
</head>

<body>

    @yield('content')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
</body>

</html>