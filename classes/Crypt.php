<?php
/**
 * Cryptography class
 */
class Crypt {

    // Random key
    const KEY = 'duJGhaCruF3rPVrPp5mGvE9f94cUkA57';
    // Ecnryption method
    const METHOD = 'AES-128-CBC';

    /**
     * Encrypt data
     * @param $input text to be encrypted
     * @return string
     */
    public function encrypt($input) {
        $iv_size = $this->get_iv_size();
        $iv = $this->generate_key($iv_size);
        $cipher = openssl_encrypt($input, self::METHOD, self::KEY, false, $iv);
        return base64_encode($iv . $cipher);
    }

    /**
     * Decrypt data
     * @param $input input string
     * @param $key a key which was used for encrypting
     * @return string
     */
    public function decrypt($input) {
        $input = base64_decode($input);
        $iv_size = $this->get_iv_size();
        $iv = mb_substr($input, 0, $iv_size, '8bit');
        $cipher = mb_substr($input, $iv_size, null, '8bit');
        return openssl_decrypt($cipher, self::METHOD, self::KEY, false, $iv);
    }

    /**
     * Generate random key
     * @return string
     */
    private function generate_key($size) {
        return openssl_random_pseudo_bytes($size);
    }

    /**
     * Get the IV size for openssl
     * @return int
     */
    private function get_iv_size() {
        return openssl_cipher_iv_length(self::METHOD);
    }
}