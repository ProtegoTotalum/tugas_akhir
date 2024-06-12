<!DOCTYPE html>
<html>
<head>
    <title>Upload File Excel</title>
</head>
<body>
    <h1>Upload File Excel</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    <form action="{{ route('makanan.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
