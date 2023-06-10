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
        @foreach($thead as $title)
            <th class="text-center">{{ $title }}</th>
        @endforeach
    </tr>
    @foreach($data as $list)
    <tr>
        @foreach($list as $value)
            <td class="text-center">{{ $value }}</td>
        @endforeach
        </tr>
    @endforeach
</table>
</body>
</html>
