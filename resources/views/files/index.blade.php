<!DOCTYPE html>
<html>
<head>
    <title>Downloaded Files</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .highlight {
            background-color: yellow; /* Highlight color for existing files */
        }
    </style>
</head>
<body>
    <h1>Downloaded Files</h1>

    <form id="download-url-form">
        @csrf
        <label for="url">Enter File URL:</label>
        <input type="url" name="url" id="url" required>
        <button type="submit">Download File</button>
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
