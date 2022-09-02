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
<div class="container py-5">
    <div class="row">
        <div class="col-xl-6 m-auto">
            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ Session::get('success') }}
                        </div>

                    @elseif(Session::has('failed'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ Session::get('failed') }}
                        </div>
                    @endif

                    <div class="card-header">
                        <h5 class="card-title font-weight-bold">Import CSV</h5>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label for="file">Choose File</label>
                            <input type="file" name="csvfile" class="form-control">
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label for="file">Enter dinner items</label>
                            <input type="text" id="dinnerItems" name="dinnerItems" class="form-control">
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Upload File</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
