<?php
// Usage:
// $password = 'reallydifficultpassword';
// $encryptedStr = Encryptor::aesEncrypt($password);
// 
// $decryptedStr = Encryptor::aesEncrypt($encryptedStr);

class Encryptor extends CApplicationComponent {

 	const KEY = 'D057-0n3l4b-3ncrypt0R';

    /**
     * Ensure that this class acts like an enum and that it cannot be instantiated
     */
    private function __construct() {

    }

    /**
     * @return string - AES-decrypted $val, using either key passed in, or local key if no key given.
     * Compatible with mysql's aes_decrypt.
     * Found this at : http://us.php.net/mcrypt, and modified.
     * @param $val - string - The string to be encrypted.
     * @param $key - string - The key to use for decryption. If none specified, use the local key.
     */
    public static function aesDecrypt($val, $key=null) {
        if ($key == null)
            $key = self::KEY;
        $mode = MCRYPT_MODE_ECB;
        $enc = MCRYPT_RIJNDAEL_128;
        $dec = @mcrypt_decrypt($enc, $key, $val, $mode, @mcrypt_create_iv(@mcrypt_get_iv_size($enc, $mode), MCRYPT_DEV_URANDOM));
        return rtrim($dec, ( ( ord(substr($dec, strlen($dec) - 1, 1)) >= 0 and ord(substr($dec, strlen($dec) - 1, 1)) <= 16 ) ? chr(ord(substr($dec, strlen($dec) - 1, 1))) : null));
    }

    /**
     * @return string - Reversible, AES-encrypted $val, using either key passed in, or local key if no key given.
     * Compatible with mysql's aes_encrypt.
     * @param $key - string - The key to use for decryption. If none specified, use the local key.
     * Found this at : http://us.php.net/mcrypt, and modified.
     */
    public static function aesEncrypt($val, $key=null) {
        if ($key == null)
            $key = self::KEY;
        $mode = MCRYPT_MODE_ECB;
        $enc = MCRYPT_RIJNDAEL_128;
        $val = str_pad($val, (16 * (floor(strlen($val) / 16) + (strlen($val) % 16 == 0 ? 2 : 1))), chr(16 - (strlen($val) % 16)));
        return @mcrypt_encrypt($enc, $key, $val, $mode, mcrypt_create_iv(mcrypt_get_iv_size($enc, $mode), MCRYPT_DEV_URANDOM));
    }
}