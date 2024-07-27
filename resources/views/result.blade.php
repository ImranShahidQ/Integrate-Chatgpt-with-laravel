<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>Analysis Result</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col" style="margin-left: 20px;">
                <h3>Analysis Result</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p>{{ $summary }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            <a class="btn btn-success" href="{{ route('analyze') }}">Analyze Again!</a>
            </div>
        </div>
    </div>
</body>
</html>
