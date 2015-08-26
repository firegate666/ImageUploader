# Image uploader

## Print base 64 encoded img tag

Upload an image file and print it as base64 encoded image

### HTML

    <form method="post" enctype="multipart/form-data">
        <input type="file" name="uploaded_image" />
    </form>
  
### PHP

    $uploader = new ImageUploader($_FILES);
    $base_64_encoded = $uploader->getBase64EncodedImageData('uploaded_image');
    $width = $uploader->getWidth('uploaded_image');
    $height = $uploader->getHeight('uploaded_image');
    
    print '<img src="' . $base_64_encoded . '" width=" . $width . " height=" . $height . " />'

