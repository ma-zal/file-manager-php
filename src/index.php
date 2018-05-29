<?php

$GLOBALS['config'] = Array();
$GLOBALS['config']['filesRoot'] = './files';


function normalizeFilename($filename) {
    return preg_replace("/[^a-z0-9\.]/", "", strtolower($filename));
}

if (!empty($_GET['action']) &&  $_GET['action'] === 'file-upload') {

    if ($_FILES["file"]["error"] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["file"]["tmp_name"];
        $name = normalizeFilename(basename($_FILES["file"]["name"]));
        move_uploaded_file($tmp_name, $GLOBALS['config']['filesRoot'] . "/$name");

        header('Location: ' . '?action=file-upload-ok', true, 302);
    }

}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>File Manager</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous" />
    </head>
    <body>

        <nav class="navbar navbar-light bg-light">
            <span class="navbar-brand mb-0 h1">File Manager</span>
        </nav>

        <br /><br />

        <div class="container">

<?php
if (!empty($_GET['action']) && $_GET['action'] === 'file-upload-ok') {
    echo '<div class="alert alert-success" role="alert">';
    echo '<h4 class="alert-heading">Upload done!</h4>';
    echo '<p>File is now published in directory.</p>';
    echo '</div>';
}

$dir = opendir($GLOBALS['config']['filesRoot']);

echo '<table class="table table-striped">';
echo     '<thead>';
echo         '<tr>';
echo             '<th>File</th>';
echo             '<th>Date</th>';
echo             '<th>Size</th>';
echo         '</tr>';
echo     '</thead>';
echo     '<tbody>';
while($file = readdir($dir)) {
    if ($file === '.' || $file === '..') { continue; }

    $filePath = $GLOBALS['config']['filesRoot'] . '/' . $file;

    if (is_dir($filePath)) { continue; }

    echo         '<tr>';
    echo             '<th>';
    echo                 '<a href="' . htmlspecialchars($filePath) . '">';
    echo                      htmlspecialchars($file);
    echo                 '</a>';
    echo             '</th>';
    echo             '<td>' . date ("Y-m-d H:i", filemtime($filePath)) . '</td>';
    echo             '<td class="right">' . number_format  (filesize($filePath) / 1024, 2) . '&nbsp;KiB</td>';
    echo         '</tr>';
}
echo     '</tbody>';
echo '</table>';
?>

            <br /><br />
            <h4>Upload a New File(s)</h4>

            <form action="?action=file-upload" method="post" enctype="multipart/form-data" class="form-inline">
                <div class="form-group mx-sm-3 mb-2">
                    <input type="file" name="file" class="form-control-file">
                </div>
                <button type="submit" class="btn btn-primary mb-2">Sumbit</button>
            </form>

        </div>

<!--
    <br />
    <hr />
    <footer class="text-center font-weight-light text-muted">
    </footer>
-->
    </body>
</html>
