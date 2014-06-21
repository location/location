<?php

$uploaddir = 'location/push/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']) . time();

echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "Push was valid and successful.\n";
} else {
    echo "Duplicate push.  RETRY in 15 minutes.\n";
}

echo 'Here is some more debugging info:';
print_r($_FILES);

print "</pre>";

?>
