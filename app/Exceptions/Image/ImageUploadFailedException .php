<?php

namespace App\Exceptions\Image;

use App\Exceptions\BaseException;

class ImageUploadFailedException extends BaseException
{
    protected $message = 'errors.image_upload_failed';
    protected $code = 500;
}