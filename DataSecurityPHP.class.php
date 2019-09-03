<?php
/**
 * DataSecurityPHP is a simple solution for protecting information with PHP.
 * Before you start working with DataSecurityPHP, get the open and closed keys.
 *The information is encrypted with the public key and decrypted with the private key.
 *
 * @version 1.0.0
 * @link https://github.com/BohdanTheBest20/DataSecurityPHP
 * @author Bogdan Shafranskiy  <shafranskiy98509@gmail.com>
 * @license https://github.com/BohdanTheBest20/DataSecurityPHP/blob/master/LICENSE
 * @package DataSecurityPHP
 */
class DataSecurityPHP 
{
	/*
	* Method for creating public and private keys
	*/
	static public function get_keys() 
	{
		$config = [
			'private_key_type' => OPENSSL_KEYTYPE_RSA,
			'private_key_bits' => 1024
		];
		$result = openssl_pkey_new($config);
		$privKey = '';
		openssl_pkey_export($result, $privKey);
		$arr = [
			'countryName' => 'name your country', #Example: UA - Ukraine
			'stateOrProvinceName' => 'name your state or province',#Example:Vinnitska Oblast
			'localityName' => 'Name your city or province', #Exemple: Vinnitsia
			'organisationalUnitName' => 'name your organisational unit',#Exemple: BestWeb
			'commonName' => $_SERVER['HTTP_HOST'], # Your host
			'emailAddress' => 'your e-mail' #Exemple: shafranskiy98509@gmail.com
		];
		$csr = openssl_csr_new($arr, $privKey);
		$cert = openssl_csr_sign($csr, NULL, $privKey, 10);
		openssl_x509_export($cert, $str_sert);
		$public_key = openssl_pkey_get_public($str_sert);
		$public_key_details = openssl_pkey_get_details($public_key);
		$public_key_string = $public_key_details['key'];
		return [
			'public' => $public_key_string,
			'private' => $privKey
		];
	}
	/*
	* Method for convert string to binary
	*/
	public function binary($string) 
	{
		$characters = str_split($string);
		$binary = [];
		foreach ($characters as $character)
		{
			$data = unpack('H*', $character);
			$binary[] = base_convert($data[1], 16, 2);
		}
		return implode(' ', $binary);   
	}
	/*
	* Method for convert binary to string
	*/
	public function unbinary($binary) 
	{
		$binaries = explode(' ', $binary);
		$string = null;
		foreach ($binaries as $binary) {
			$string .= pack('H*', dechex(bindec($binary)));
		}
		return $string;  	
	}
	/*
	* A method for encrypting information using a public key. If you set the third argument to a function other than NULL, then the information is still converted to binary
	*/
	public function hash($str, $public_key, $binary = null) 
	{
		openssl_public_encrypt($str, $data, $public_key);
		$get_data = base64_encode($data);
		$data = ($binary === TRUE) ? $this->binary($get_data) : $get_data;
		return $data;
	}
	/*
	* A method for decrypting information using a private key. If you set the third argument to a function other than NULL, then the information from binary to normal information
	*/
	public function decrypt($hash, $private_key, $unbinary = null) 
	{
		$get_data = base64_decode($hash);
		$get_string = ($unbinary !== null) ? base64_decode($this->unbinary($hash)) :  $get_data;
		openssl_private_decrypt($get_string, $data, $private_key);
		return $data;
	}
}
