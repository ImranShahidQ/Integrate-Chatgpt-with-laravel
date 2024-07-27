<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>Analyze</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div style="margin-left: 20px;" class="col">
                <h3>Analyze</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('generate-summary') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row" style="padding-top: 20px;">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="file" class="form-label">Upload File (XML, TXT, PDF):</label>
                                <input type="file" class="form-control" name="file" id="file" accept=".xml, .txt, .pdf" required>
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 20px;">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="what_is_your_relationship" class="form-label">What is your relationship with this person?</label>
                                <select class="form-select" name="what_is_your_relationship" id="what_is_your_relationship" required>
                                    <option value="">Select an option</option>
                                    <option value="Brother">Brother</option>
                                    <option value="Business Partner">Business Partner</option>
                                </select>
                                @error('what_is_your_relationship')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 20px;">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="time_since_you_know" class="form-label">How long have you known this person?</label>
                                <select class="form-select" name="time_since_you_know" id="time_since_you_know" required>
                                    <option value="">Select an option</option>
                                    <option value="6 Months">6 Months</option>
                                    <option value="1 Year">1 Year</option>
                                </select>
                                @error('time_since_you_know')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 20px;">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">Analyze</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
