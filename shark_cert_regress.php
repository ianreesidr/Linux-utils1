<?php  
  
define('HOST', '10.64.8.67'); // IP address of Profiler  
define('BASIC_AUTH', 'tester:password');  
  

// Lib functions  
  
// HTTP PUT  
function do_PUT($url, $json, &$info) {  
  $curl = curl_init(); 
  $headers[] = 'Content-Type: application/json';
  $headers[] = 'Accept: application/json';
  $headers[] = 'X-Requested-With: XMLHttpRequest';  
  $headers[] = 'Cache-Control: no-cache';
  $headers[] = 'X-HTTP-Method-Override: PUT';
  curl_setopt($curl, CURLOPT_URL, $url);  
  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;  
  curl_setopt($curl, CURLOPT_USERPWD, BASIC_AUTH);  
  curl_setopt($curl, CURLOPT_SSLVERSION, 1);  
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);  
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);  
  curl_setopt($curl, CURLOPT_HEADER, true);  
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); 
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST,  "PUT");
  curl_setopt($curl, CURLOPT_POSTFIELDS, $json);  
  $output = curl_exec($curl);  
  $info   = curl_getinfo($curl);  
  curl_close($curl);  
    
  $headers = substr($output, 0, $info['header_size']);  
  $headers = explode("\n", $headers);  
  $info['headers'] = $headers;  
  $body = substr($output, $info['header_size']);  
  return $body;  
}  
  // HTTP GET  
function do_GET($url, &$info) {  
  $curl = curl_init();  
  curl_setopt($curl, CURLOPT_URL, $url);  
  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;  
  curl_setopt($curl, CURLOPT_USERPWD, BASIC_AUTH);  
  curl_setopt($curl, CURLOPT_SSLVERSION, 3);  
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);  
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);  
  curl_setopt($curl, CURLOPT_HEADER, true);  
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json'));  
  curl_setopt($curl, CURLOPT_HTTPGET, true);  
  $output = curl_exec($curl);  
  $info   = curl_getinfo($curl);  
  curl_close($curl);  
      
  $headers = substr($output, 0, $info['header_size']);  
  $headers = explode("\n", $headers);  
  $info['headers'] = $headers;  
  $body = substr($output, $info['header_size']);  
  return $body;  
}  
 
// End lib functions  

$array_data = array('pem' => '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQC0e+f4pJY2eSm18U579OKJIyxc/sdKXlLOw0zK6SoNu7XNHmNo
ObNhQV+3PoSbZNyqW3GuZ54EEKUwG54kDzHu9cZMGEWvNO6syjZZlfBORpklQoNE
sNxAkbhTr9DXfloFKiLouDl8E7jBhMkbKxnpNcfcl+NEuQ8av2QQWp3jfQIDAQAB
AoGAGDk5HSoZ7x2792t3uTNY5EcQQTsAzH50ZsPXnrRErKsw72LQvMBhzv/TNOjz
K1gSNdsoGtxVXP5O7XIHe5d+f/YjUk1Om6zXsE3bWFHVitSAzl+DVATAl9utse9E
qlOVHh0UQz0sPywkn+ZRpuwHeIpE8v8mXPCluZEBDd1Dj4ECQQDopBnuxw8/APEV
HRhg+4g8Q4YpxHdhBB8nP5l4pEJdSfSBQB54ZzKtYg6JVnVSHjypiEYN2WUQ50/I
bQMTXMb1AkEAxpsj0xZJeql60YO2Z20+0jt14DvY1bSbEDQccWxVF8P+5oR7viX6
7HTZi/32W+HQHuPQoNEPTM6rc+uPP4eFaQJAfrdLzMyuWEH6DucPLVw0o6agAEYc
RELLeURiIt1NrKSownIbZrfHWbtscZAfTUBdCHbKuaZtL1zfDyuZnkVNtQJBALCX
ASOPjYYja7EfC/CqklEbzZOzovlhvP6LVz8CtCaNfvg4lE5eNt5Ih6aSCZtpDURq
C/bx7Ei++nP330b9mQECQEzdWEAT6n71Br4mKS8MDHnNmK4DZJXpKnNVN+Ae4wEZ
QxMxJWZscEEGJNnW1gmSqfRmBpmRvsYX/NdF/A4RZfc=
-----END RSA PRIVATE KEY-----
-----BEGIN CERTIFICATE-----
MIIBsTCCARqgAwIBAgIJAOqvgxZRcO+ZMA0GCSqGSIb3DQEBBAUAMA8xDTALBgNV
BAMTBE1henUwHhcNMDYxMDAyMTY0MzQxWhcNMTYwOTI5MTY0MzQxWjAPMQ0wCwYD
VQQDEwRNYXp1MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC0e+f4pJY2eSm1
8U579OKJIyxc/sdKXlLOw0zK6SoNu7XNHmNoObNhQV+3PoSbZNyqW3GuZ54EEKUw
G54kDzHu9cZMGEWvNO6syjZZlfBORpklQoNEsNxAkbhTr9DXfloFKiLouDl8E7jB
hMkbKxnpNcfcl+NEuQ8av2QQWp3jfQIDAQABoxUwEzARBglghkgBhvhCAQEEBAMC
BkAwDQYJKoZIhvcNAQEEBQADgYEATnoqJSym+wATLxgb2Ujdy4CY0gawUXHjidaE
ehyejGdw6VhXpf4lP9Q8JfVERjCoroVkiXenVQe/zer7Qf2hiDB/5s02/+8uiEeq
MJpzsSdEYZUSgpyAcws5PDyr2GVFMI3dfPnl28hVavIkR8r05BPDxKbb8Ic6HWpT
CTDPH3w=
-----END CERTIFICATE-----');
$json = json_encode($array_data);
   
  
// Put to run the command  
$url = 'https://' . HOST . '/api/shark/5.1/settings/certificates/profiler_export HTTP/1.1';  
echo "Run command:\nPUT {$url}\n{$json}\n\n";  
//$output = do_GET($url, $info);  

//echo "http code".$info['http_code']."\n"; 
$output = do_PUT($url, $json, $info);
echo $output;  
//if ($info['http_code'] != 201) {  
//  echo "Unable to run command!\n";  
//  exit(1);  
//}  

    

  
?>  
