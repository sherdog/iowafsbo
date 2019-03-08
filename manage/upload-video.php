<?php
if($_FILES['Filedata']['size'] > 0) {
    $path = 'repository/'.$_FILES['Filedata']['name'];
    move_uploaded_file($_FILES['Filedata']['tmp_name'], $path);
}
?>

