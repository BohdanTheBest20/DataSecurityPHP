<?php
/*
* This is a examples of how to using DataSecurityPHP.
*/
require 'DataSecurityPHP.class.php'; #include DataSecurityPHP class.
$security = new DataSecurityPHP(); # Creating an instance of the DataSecurityPHP class
$str = 'Hello, world!'; #Simple string
$binary_str = '1001000 1100101 1101100 1101100 1101111 101100 100000 1110111 1101111 1110010 1101100 1100100 100001'; #Binary string
$keys = $security->get_keys(); #Get keys
$public_key = $keys[0]; #public key
$private_key = $keys[1]; #private key
$binary = $security->binary($str);  #Convert string to binary
$string_from_binary = $security->unbinary($str);  #Convert the new string from binary string
$hash = $security->hash($str, 'public key'); #Encrypting information using a public key. It can accept three argumets. But two arguments are required is a string and public key to encrypt the information. If you set the third argument to a function other than NULL, then the information is still converted to binary
$decrypt = $security->decrypt($hash, 'private key'); #Decrypting information using a private key. It can accept three argumets. But two arguments are required is a hash and private key to decrypt the information. If you set the third argument to a function other than NULL, then the binary is still converted to string
