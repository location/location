<?php

$uploaddir = 'location/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']) . time();

echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
} else {
    echo "Duplicate upload.  RETRY in 15 minutes.\n";
}

echo 'Here is some more debugging info:';
print_r($_FILES);

print "</pre>";

?>
