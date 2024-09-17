<!DOCTYPE html>
<html>
<head>
    <title>Data Duplication Alert System </title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- External CSS -->
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
</head>
<body>
    <h1>Data Duplication Alert System </h1>
    <h2>Download File</h2>

    <form id="download-url-form">
        @csrf
        <label for="url">Enter URL:</label>
        <input type="url" name="url" id="url" required>
        <button type="submit">Download</button>
    </form>

    <h2>Existing Files</h2>
    <ul id="file-list">
        @foreach ($downloads as $download)
            <li data-file="{{ $download->file_path }}">{{ $download->file_name }}</li>
        @endforeach
    </ul>

    <script>
        $(document).ready(function() {
            $('#download-url-form').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                $.ajax({
                    url: "{{ route('files.downloadFromUrl') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(data) {
                        if (data.exists) {
                            if (confirm("File already exists at " + data.file + ". Do you want to download it anyway?")) {
                                // Proceed with downloading the file
                                window.location.href = data.file; // Adjust as needed to actually download the file
                            } else {
                                // Highlight the existing file
                                $('#file-list li').removeClass('highlight'); // Remove highlight from all
                                $('#file-list li[data-file="' + data.file + '"]').addClass('highlight');
                            }
                        } else {
                            // File downloaded successfully, refresh the page or update the file list
                            $('#file-list').append('<li data-file="' + data.file + '">' + data.file + '</li>');
                        }
                    },
                    error: function(xhr) {
                        alert('Error downloading file.');
                    }
                });
            });
        });
    </script>
</body>
</html>
