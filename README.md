# Image uploader

HTML

  <form method="post" enctype="multipart/form-data">
    <input type="file" name="uploaded_image" />
  </form>
  
PHP

  $uploader = new ImageUploader($_FILES);
  $base_64_encoded = $uploader->getBase64EncodedImageData('uploaded_image');
