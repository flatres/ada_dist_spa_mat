<?php

function moveUploadedFile($directory, Slim\Http\UploadedFile $uploadedFile, $filename = false)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    if (!$filename) {
      $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
      $filename = sprintf('%s.%0.8s', $basename, $extension);
    } else {
      $filename = sprintf('%s.%0.8s', $filename, $extension);
    }


    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}
