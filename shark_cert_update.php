<?php  
// script to update certificate on NetShark 
// replaces mnmp NetProfiler Export certificate that expired on 29th Sept 2016
// and then restarts the probe service
// can be run from NetProfiler or other device with https access to NetSharks
// usage:
// /usr/local/bin/php shark_cert_update.php <ip-address-netshark> <username> <password>
//
// comments: ian.rees@riverbed.com
//
$host = $argv[1];
$username = $argv[2];
$password = $argv[3];
if ($host == "") {
  echo "No host IP address provided, exiting !\n";
  exit(1);
}
if ($password == "") {
  echo "run again specifying IP username password, exiting !\n";
  exit(1);
}
DEFINE ('BASIC_AUTH', $username . ":" . $password);

// Lib functions 
// HTTP POST  
function do_POST($url, $string, &$info) {  
  $curl = curl_init();  
  curl_setopt($curl, CURLOPT_URL, $url);  
  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;  
  curl_setopt($curl, CURLOPT_USERPWD, BASIC_AUTH);  
  curl_setopt($curl, CURLOPT_SSLVERSION, 1);  
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);  
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);  
  curl_setopt($curl, CURLOPT_HEADER, true);  
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));  
  curl_setopt($curl, CURLOPT_POST,           1);  
  curl_setopt($curl, CURLOPT_POSTFIELDS,     $string);  
  $output = curl_exec($curl);  
  $info   = curl_getinfo($curl);  
  curl_close($curl);  
    
  $headers = substr($output, 0, $info['header_size']);  
  $headers = explode("\n", $headers);  
  $info['headers'] = $headers;  
  $body = substr($output, $info['header_size']);  
  return $body;  
}   
  
// HTTP PUT  
function do_PUT($url, $json, &$info) {  
  $curl = curl_init(); 
  $headers[] = 'Content-Type: application/json';
  $headers[] = 'Accept: application/json';
  $headers[] = 'X-Requested-With: XMLHttpRequest';  
  $headers[] = 'Cache-Control: no-cache';
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

$array_data = array('pem' => '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC8Hz+K0ZXFLxzR
1Y8YYfv2tcPztpCF3alFLbq27F1Xru52I9M6l48kn+IyNTEkCqLuiU+Yzxj8R1gq
8H5sKamGk5yGaSWDne/FiIbn0ZlT2+Y9UF0oTujTaqV4uafXKd/+HcWUrH6HCQi2
Z4LNB4kIRaN/NjE1HOSYP5aQYBYZ0rV97v0p2WSgHfom3HsPpg0WQw+APhzTqPhb
5ssG0Um2AFC++DE/SvtCSYB/0UwL4zgrFLkdXo+9KMUYyMCLjG3XR9HmfyjHRqfa
bXIEaXltomyEZxgWexTITXoLKJvHt1SGXoyg6yhAwILUn9eb/P9eW5KCLRs317Iw
F7etvETtAgMBAAECggEAT1qrFia0k7n84TLBqtpiS75+ywZezmkjZxAUK+dmtH8R
DfqstpGKCzv6UY2IjhiChDadwerSMlDyCyLpYTztytvncfdWoL91jhC6P311vR8M
bx7APSmUIuJgrJ08BJIgb3QljUJ6GQIhj7f4P/6GuluNzTKR7UKj1ukz3PD7Qaac
QHOiSnGiM/aaCTA3kimNd0Twmcbe9MjxlMK6PTPTQMG6l4iPKCM4qx5Ca7MxDJYB
VLoUIVZLggF3JX/kRvmlvHAAkxQAHGBGenLx5XL90JKp+93vs7ztMND058L9SMQP
YIEHOEebvBz3kHOIPofVHet67co7A2uPL+jbU2mVLQKBgQDgvIME3SQHOQ5nua1H
NxJj1Am2t3LhMoIzlT0kLZ3yrMu+tk3I+zTtPEnnCybHg/4/xsJJcUWNJBigwoJ5
a76U3jaiVtcllK4mkvxDbkLyAbFfnyeIQJD3jYKMYBTJsGj6479Ad/kp4l8Llxig
olao5ZNXyhphP9jiiU5XnCyKjwKBgQDWSsyAVQsb4XSet4FCjDvqEqs2VFw2zt7L
Y/3iE+aYkMHcPkDe9SOO27w53MYIQplvYSa16FKQaoMPdvaAsk+5taCGei0qAQDg
98ppIrHPZ9zW3b+K7kEA/1vkIf+rfxxE8U2PSOUQ8CY4uszWE0Dw73AJOG2eJRZo
8QtVfaqmwwKBgDxEcYJRZ4MEWweX9I7/hs/8oeo9AfLAX8hDglT8YrocersXGn8G
Vugz3cG27NxeWkVyINIfLT6vamdaIjE7oq07dj1Mun+agqVXj4zpucw6hf2Vqb1R
S08HxPWuIoT/6Pc3MiewxMHlsgVirLkPL9w1TLEjQrXHsHr3xWmbORqjAoGAFNhL
0kPzg6/Hr1S8XyPTBC7ytqM4ISC4bWJ5pM84xCh4oxrvJoEEB3Z6dcpy/QS1di8m
G9XQijnCK/PvgI9X0AHJ7qrdz9MvKbMQ3m9AiqNYyRHi+vm4Gwe8AZIJE76WmmI6
oDCAD8i8fxI0sLJIw+cWp970UsjayDSCXLuPqQ8CgYEAhIbpvtai0PMhL5yatECy
/NvMb0HdDvBeaocd9JbxOHMMgrMjlkFIJJkoPdQmqgr1aBRBYzT3q8h4+A/Q5217
M56uKvl1VTTzP8Sjp1LE9HJyaKFfsWa8nJK62zCr6t3SLaUMdJcbFI0Bl+NF8YPe
nXAA4NET2FaU0VQ0AV5UCBU=
-----END PRIVATE KEY-----
-----BEGIN CERTIFICATE-----
MIID3zCCAsegAwIBAgIJALUIRiJTYgMoMA0GCSqGSIb3DQEBDQUAMHQxKTAnBgNV
BAMTIENhc2NhZGUgTU5NUCBEZWZhdWx0IENlcnRpZmljYXRlMSIwIAYDVQQKExlS
aXZlcmJlZCBUZWNobm9sb2d5LCBJbmMuMSMwIQYJKoZIhvcNAQkBFhRzdXBwb3J0
QHJpdmVyYmVkLmNvbTAeFw0xMjA2MTIxNjI3MjVaFw0yMjA2MTAxNjI3MjVaMHQx
KTAnBgNVBAMTIENhc2NhZGUgTU5NUCBEZWZhdWx0IENlcnRpZmljYXRlMSIwIAYD
VQQKExlSaXZlcmJlZCBUZWNobm9sb2d5LCBJbmMuMSMwIQYJKoZIhvcNAQkBFhRz
dXBwb3J0QHJpdmVyYmVkLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoC
ggEBALwfP4rRlcUvHNHVjxhh+/a1w/O2kIXdqUUturbsXVeu7nYj0zqXjySf4jI1
MSQKou6JT5jPGPxHWCrwfmwpqYaTnIZpJYOd78WIhufRmVPb5j1QXShO6NNqpXi5
p9cp3/4dxZSsfocJCLZngs0HiQhFo382MTUc5Jg/lpBgFhnStX3u/SnZZKAd+ibc
ew+mDRZDD4A+HNOo+FvmywbRSbYAUL74MT9K+0JJgH/RTAvjOCsUuR1ej70oxRjI
wIuMbddH0eZ/KMdGp9ptcgRpeW2ibIRnGBZ7FMhNegsom8e3VIZejKDrKEDAgtSf
15v8/15bkoItGzfXsjAXt628RO0CAwEAAaN0MHIwHQYDVR0OBBYEFNHS1lF+lyYf
mVAXV/T0xjkgupO7MB8GA1UdIwQYMBaAFNHS1lF+lyYfmVAXV/T0xjkgupO7MBEG
CWCGSAGG+EIBAQQEAwIGwDAdBgNVHSUEFjAUBggrBgEFBQcDAgYIKwYBBQUHAwEw
DQYJKoZIhvcNAQENBQADggEBAB8FM5z12oDHfmPMQp2d7OfRQAzPtuCdHEYI9AJ9
8sTcA0d8xTfCIEYyglXCijoE42iVx9Pgfo4PO7yCqxNa9kpNMnEISm2ZQv2E0noM
DmpIrT0z5BcfxcWulqx1Y2RwP+RkeU2atdpPSAboyLex6SXbKKREmjBZLvP7OBZb
AuNnIdVPONpsHHDbOAh5UtVdvJ1fUmme/NHcKG8aixaynTV3HoOX7YtG1hT5H+YM
1ImpfK5NJJuBra13e6nDKvaLmd9EVbrW8vAQwP3/jLNT3DyRvCuq6MaJl+kSRNz2
i7gWkZzoLH0AKvL1Imu14gVKk6K9BLMrjeSR0fS6hMwpl0s=
-----END CERTIFICATE-----');
$json = json_encode($array_data);
   
  
// Put to update certificate
$url = 'https://' . $host . '/api/shark/5.1/settings/certificates/profiler_export HTTP/1.1';  
echo "Run command:\nPUT {$url}\n{$json}\n\n";  
//$output = do_GET($url, $info);  

//echo "http code".$info['http_code']."\n"; 
$output = do_PUT($url, $json, $info);
echo $output;  
if ($output != "") {  
  echo "Unable to run command on " . $host . " !\n";  
  exit(1);  
} 
// Post to restart box
$url = 'https://' . $host . '/api/shark/5.1/system/restart HTTP/1.1'; 
$array_data = array('type' => 'PROBE');
$json = json_encode($array_data);
echo "Restart Probe Server:\nPOST {$url}\n{$json}\n\n";  
$info = array();  
do_POST($url, $json, $info);  
// there should be an empty response
if ($info['http_code'] != 204) {  
  echo "Unable to restart service on " . $host . " !\n";  
  exit(1);  
}  

    

?>  
