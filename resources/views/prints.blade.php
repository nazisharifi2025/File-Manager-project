<!DOCTYPE html>
<html>
<head>
    <title>Print File</title>
</head>
<body onload="window.print()">

    <h2>{{ $file->name }}</h2>

    <p>Type: {{ $file->type }}</p>

</body>
</html>