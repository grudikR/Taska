<!doctype html>
<html lang="en">
<head>
    <title>Town of Invoiceville</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<div class="container py-4">
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Output</h5>
        </div>
        <div class="card-body">
            @if ($min_price != PHP_INT_MAX)
                Restaurant: {{$min_id}}
            @else
                Restaurant: none
            @endif
            <br/>
            @if ($min_price!= PHP_INT_MAX)
                Total cost: {{$min_price}}
            @endif
        </div>
    </div>
</div>
</body>
</html>
