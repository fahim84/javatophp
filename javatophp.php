<?php

use phpseclib3\Crypt\AES;

function encrypt($value, $hashIterations) {
    echo "---------------------step1 start-----------------\n";
    echo "encrypt called with params -> password value: $value, hashIterations: $hashIterations\n";

    $textEncrypted = null;
    $encryptedText = null;
    try {
        $textEncrypted = encryptHelper($value, OPENSSL_RAW_DATA, $hashIterations);
        echo "textEncrypted: $textEncrypted\n";
        $encryptedText = bin2hex($textEncrypted);
    } catch (Exception $e) {
        throw $e;
    } finally {
        $textEncrypted = null;
    }
    echo "---------------------step1 end-----------------\n";
    return $encryptedText;
}

function encryptHelper($value, $opmode, $hashIterations) {
    echo "---------------------step2 start-----------------\n";
    echo "encryptHelper called with params -> password byte value: $value, opmode: $opmode, hashIterations: $hashIterations\n";

    $iv = "encryptionIntVec";

    $secretKey = null;
    $desCipher = null;
    $textEncrypted = null;
    try {
        $secretKey = createKeySpec(getEncrKey(), $hashIterations);
        dump($secretKey);
        exit();
        $desCipher = new AES(AES::MODE_CBC);
        echo "---Cipher creation starts--" . PHP_EOL;
        echo "opmode: " . $opmode . PHP_EOL;
        echo "secretKey: " . $secretKey->getKey() . PHP_EOL;
        echo " iv: " . $iv . PHP_EOL;
        $desCipher->setKey($secretKey->getKey());
        $desCipher->setIV($iv);
        if ($opmode == 1) { // Encrypt
            $textEncrypted = $desCipher->encrypt($value);
        } elseif ($opmode == 2) { // Decrypt
            $textEncrypted = $desCipher->decrypt($value);
        }

    } catch (Exception $e) {
        throw $e;
    } finally {
        $secretKey = null;
        $desCipher = null;
    }
    echo "---------------------step2 end-----------------\n";
    return $textEncrypted;
}

function createKeySpec($myKey, $hashIterations) {
    echo "---------------------step3 start-----------------\n";
    echo "createKeySpec called with params -> encryptionkey: $myKey, hashIterations: $hashIterations\n";

    $salt = "ETYBDIJPOklskdfslakf";
    $tmp = null;
    $secretKey = null;
    try {
        $tmp = hash_pbkdf2("sha256", $myKey, $salt, $hashIterations, 128);
        $secretKey = $tmp;
    } catch (Exception $e) {
        throw $e;
    }
    echo "output secretKey: " . base64_encode($secretKey) . "\n";
    echo "output secretKey: " . (gettype($secretKey)) . "\n";
    echo "---------------------step3 end-----------------\n";
    exit();
    return $secretKey;
}

function getEncrKey() {
    // Return your encryption key here
    return "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";
}

// Usage example
$value = "2296";
$hashIterations = 100;
$encrypted = encrypt($value, $hashIterations);
echo "Encrypted: $encrypted\n";
?>
