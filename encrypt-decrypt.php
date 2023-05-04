<?php
// Store a string into the variable which
// need to be Encrypted
$simple_string = "Welcome to GeeksforGeeks\n";

// Display the original string
my_var_dump("Original String: " . $simple_string);

// Store the cipher method
$ciphering = "AES-128-CTR";

// Use OpenSSl Encryption method
$iv_length = openssl_cipher_iv_length($ciphering);
$options = 0;

// Non-NULL Initialization Vector for encryption
$encryption_iv = '1234567891011121';

// Store the encryption key
$encryption_key = "GeeksforGeeks";

// Use openssl_encrypt() function to encrypt the data
$encryption = openssl_encrypt($simple_string, $ciphering,
    $encryption_key, $options, $encryption_iv);

// Display the encrypted string
my_var_dump("Encrypted String: " . $encryption . "\n");

// Non-NULL Initialization Vector for decryption
$decryption_iv = '1234567891011121';

// Store the decryption key
$decryption_key = "GeeksforGeeks";

// Use openssl_decrypt() function to decrypt the data
$decryption = openssl_decrypt($encryption, $ciphering,
    $decryption_key, $options, $decryption_iv);

// Display the decrypted string
my_var_dump("Decrypted String: " . $decryption);


function my_var_dump($string)
{
    if (is_array($string) or is_object($string)) {
        echo "<pre>";
        print_r($string);
        echo "</pre>";
    } elseif (is_string($string)) {
        echo $string . "<br>\n";
    } else {
        echo "<pre>";
        var_dump($string);
        echo "</pre>";
    }
}