<?php

class XYZ
{
    function __construct()
    {
        $this->publickey = NULL;
        $this->encrKey = NULL;
        $this->encrHashSalt = NULL;
    }

    private static $AES_ALGORITHM = "AES";
    public static $TRANSFORMATION = "AES/CBC/PKCS5Padding";
    public static $UTF_8 = "UTF-8";
    public static $PBKDF_SHA256 = "PBKDF2WithHmacSHA256";
    private static $salt = "ETYBDIJPOklskdfslakf";
    private $publickey;
    private $encrKey;
    private $encrHashSalt;

    public static function XYZ_1()
    {
        $local_this = new XYZ();
        return $local_this;
    }

    public function test()
    {
        $value = "2296"; // Assuming "your value" is a string

        // Convert the string to a byte array
        $valueByteArray = mb_convert_encoding($value, 'UTF-8');
        $byteArray = array_values(unpack('C*', mb_convert_encoding($value, 'UTF-8')));
        my_var_dump($byteArray);
        //my_var_dump($valueByteArray);
        $valueByteArray = unpack('C*', $value);

        //my_var_dump($valueByteArray);
        //my_var_dump($value);
    }

    function encrypt($value, $hashIterations)
    {
        echo("---------------------step1 start-----------------\n");
        echo(" encrypt called with params -> password value: $value hashIterations: $hashIterations\n");
        try {
            $textEncrypted = encryptByte(strToByteArray($value), 1, $hashIterations);
            $encryptedText = toHex($textEncrypted);
            echo(" textEncrypted : " . print_r($textEncrypted, true) . "\n");
        } catch (Exception $e) {
            throw $e;
        } finally {
            $textEncrypted = null;
        }
        echo("---------------------step1 end-----------------\n");
        return $encryptedText;
    }

    function toHex($array)
    {
        echo("---------------------step4 start-----------------\n");
        echo("toHex called with params -> encryptedText byte array:" . print_r($array, true) . "\n");
        $bi = null;
        $hex = null;
        $paddingLength = 0;
        try {
            $bi = new BigInteger(1, $array);
            $hex = $bi->toString(16);
            $paddingLength = (count($array) * 2) - strlen($hex);
            if ($paddingLength > 0) {
                $hex = sprintf("%0" . $paddingLength . "d", 0) . $hex;
            }
            echo("output toHex: $hex\n");
        } finally {
            $bi = null;
            $hex = null;
        }
        echo("---------------------step4 end-----------------\n");
        return $hex;
    }

    function encryptByte($value, $opmode, $hashIterations)
    {
        echo("---------------------step2 start-----------------\n");
        echo(" encryptByte overloaded called with params -> password byte value: " . print_r($value, true) . " opmode: $opmode hashIterations: $hashIterations\n");
        $iv = new IvParameterSpec(strToByteArray("encryptionIntVec"));
        $secretKey = null;
        $desCipher = null;
        $textEncrypted = null;
        try {
            $secretKey = createKeySpec(getEncrKey(), $hashIterations);
            $desCipher = Cipher::getInstance("AES/CBC/PKCS5Padding");
            echo("---Cipher creation starts--\n");
            echo("opmode: $opmode\n");
            echo("secretKey: " . print_r($secretKey->getEncoded(), true) . "\n");
            echo(" iv: " . print_r($iv->getIV(), true) . "\n");
            $desCipher->init($opmode, $secretKey, $iv);
            $textEncrypted = $desCipher->doFinal($value);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $secretKey = null;
            $desCipher = null;
        }
        echo("---------------------step2 end-----------------\n");
        return $textEncrypted;
    }

    function createKeySpec($myKey, $hashIterations)
    {
        echo("---------------------step3 start-----------------\n");
        echo("createKeySpec called with params -> encryptionkey: $myKey hashIterations: $hashIterations\n");
        $factory = null;
        $spec = null;
        $tmp = null;
        $secretKey = null;
        $factory = SecretKeyFactory::getInstance("PBKDF2WithHmacSHA256");
        echo("myKey toCharArray: " . print_r(str_split($myKey), true) . "\n");
        echo("salt bytes: " . print_r(str_split($salt), true) . "\n");
        echo("key length: " . 128 . "\n");
        $spec = new PBEKeySpec(str_split($myKey), str_split($salt), $hashIterations, 128);
        $tmp = $factory->generateSecret($spec);
        $secretKey = new SecretKeySpec($tmp->getEncoded(), "AES");
        echo("output secretKey: " . print_r($secretKey->getEncoded(), true) . "\n");
        echo("---------------------step3 end-----------------\n");
        return $secretKey;
    }

    //Updated for Demo Class
    public function getHashingIterationCount()
    {
        return $iterationCount = 100;
    }

    public function setPublickey(string $publickey)
    {
        $this->publickey = $publickey;
    }

    public function getPublickey()
    {
        return $this->publickey;
    }

    public function getEncrKey()
    {
        return $this->encrKey;
    }

    public function setEncrKey(string $encrKey)
    {
        $this->encrKey = $encrKey;
    }

    public function getEncrHashSalt()
    {
        return $this->encrHashSalt;
    }

    public function setEncrHashSalt(string $encrHashSalt)
    {
        $this->encrHashSalt = $encrHashSalt;
    }
}

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

$obj = new XYZ();
$obj->test();