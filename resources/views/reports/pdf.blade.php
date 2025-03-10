<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PDF Report</title>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>Report Data:</p>
    <pre>{{ print_r($data, true) }}</pre>
</body>
</html>
