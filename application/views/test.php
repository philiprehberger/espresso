<body>
    <form id="file-form" action="/upload/file" method="POST">
        <input type="file" id="file-select" name="files[]" multiple/>
        <button type="submit" id="upload-button">Upload</button>
    </form>
</body>
<script>
    var form = document.getElementById('file-form');
    var fileSelect = document.getElementById('file-select');
    var uploadButton = document.getElementById('upload-button');
    form.onsubmit = function (event) {
        event.preventDefault();

        // Update button text.
        uploadButton.innerHTML = 'Uploading...';
// Get the selected files from the input.
        var files = fileSelect.files;
// Create a new FormData object.
        var formData = new FormData();
// Loop through each of the selected files.
        for (var i = 0; i < files.length; i++) {
            var file = files[i];

            // Check the file type.
            if (!file.type.match('image.*')) {
                continue;
            }

            // Add the file to the request.
            formData.append('files[]', file, file.name);
 // Files
 //           formData.append(name, file, filename);

// Blobs
//            formData.append(name, blob, filename);

// Strings
 //           formData.append(name, value)

// Set up the request.
            var xhr = new XMLHttpRequest();

            // Open the connection.
            xhr.open('POST', '/upload/file', true);
            // Set up a handler for when the request finishes.
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // File(s) uploaded.
                    uploadButton.innerHTML = 'Upload';
                } else {
                    alert('An error occurred!');
                }
            };

// Send the Data.
            xhr.send(formData);





        }
    };

</script>