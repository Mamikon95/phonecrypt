<?php
/**
 * Cryptography class
 */
class Crypt {

    /**
     * Encrypt data
     * @param $input text to be encrypted
     * @return array
     */
    public function encrypt($input) {
        $key = $this->generate_key();
        $iv = mcrypt_create_iv($this->get_iv_size(), MCRYPT_RAND);
        $cipher = $iv . mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $input, MCRYPT_MODE_CBC, $iv);
        $output = base64_encode($cipher);
        return [ $output, $key ];
    }

    /**
     * Decrypt data
     * @param $input input string
     * @param $key a key which was used for encrypting
     * @return string
     */
    public function decrypt($input, $key) {
        $cipher = base64_decode($input);
        $iv_size = $this->get_iv_size();
        $iv = substr($cipher, 0, $iv_size);
        $cipher = substr($cipher, $iv_size);
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $cipher, MCRYPT_MODE_CBC, $iv);
    }

    /**
     * Get the IV size for mcrypt
     * @return int
     */
    private function get_iv_size() {
        return mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    }

    /**
     * Generate random key
     * @return string
     */
    private function generate_key() {
        return base64_encode(openssl_random_pseudo_bytes(16));
    }
}