<?php

 namespace App\Exceptions\User\Address;

 use App\Exceptions\BaseException;

// class AddressNotOwnedByUserException extends BaseException
// {
//     protected $message = 'You do not have permission to access or use this address.';
//     protected $code = 403;
// }



class AddressNotOwnedByUserException extends BaseException
{
    protected $message = 'You do not have permission to access or use this address.';
    protected $code = 403;
}
