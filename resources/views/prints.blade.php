<!DOCTYPE html>
<html>
<head>
    <title>Print File</title>

    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        @media print {
            button, nav {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <h2>{{ $file->name }}</h2>

    <hr>

    {{-- اگر فایل عکس است --}}
    @if(str_contains($file->type, 'image'))
        <img src="{{ asset('storage/'.$file->path) }}" style="max-width:100%;">
    @else
        {{-- اگر PDF یا فایل دیگر --}}
        <iframe src="{{ asset('storage/'.$file->path) }}" width="100%" height="600px"></iframe>
    @endif

</body>
</html>