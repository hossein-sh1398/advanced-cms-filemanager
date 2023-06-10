<!doctype html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="utf-8">
    <title>Document</title>
    <style>
        body {
            font-family: 'examplefont', sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: right;
            padding: 5px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center
        }
    </style>
</head>
<body>
<table>
    <tr>
        <th class="text-center">نام</th>
        <th class="text-center">نام</th>
        <th class="text-center">ایمیل</th>
        <th class="text-center">موبایل</th>
        <th class="text-center">تاریخ ایجاد</th>
    </tr>
    @foreach($users as $user)
        <tr>
            <td class="text-center">{{ $user['name'] }}</td>
            <td class="text-center">{{ $user['email'] }}</td>
            <td class="text-center">{{ $user['mobile'] }}</td>
            <td class="text-center">{{ $user['created_at'] }}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
