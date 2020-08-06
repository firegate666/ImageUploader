<?php

use firegate666\ImageUploader\ImageUploader;

$img_tag = '';
if (!empty($_FILES)) {
    $uploader = new ImageUploader($_FILES);
    $base_64_encoded = $uploader->getBase64EncodedImageData('uploaded_image');
    $width = $uploader->getWidth('uploaded_image');
    $height = $uploader->getHeight('uploaded_image');
    $img_tag = '<img src="' . $base_64_encoded . '" width=" . $width . " height=" . $height . " />';
}
?>
<html>
<body>

    <form method="post" enctype="multipart/form-data">
        <input type="file" name="uploaded_image" />
    </form>

    <?= $img_tag ?>

</body>
</html>
