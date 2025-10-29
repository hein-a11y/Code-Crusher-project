<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <script>
    let keyword = 'test';
    fetch('receive_get.php?search=' + encodeURIComponent(keyword));
    </script>



</body>
</html>