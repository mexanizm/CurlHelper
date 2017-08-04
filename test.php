<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    </heade>
<body>

<pre>
<?
require 'Curl.php';

$curl = new Curl('https://www.google.com/recaptcha/api/siteverify' , 'GET');
$responce = $curl->query(['secret' => '' , 'response' => $code , 'remoteip' => $_SERVER['REMOTE_ADDR']]);
$headers = $curl->getResponceHeaders();
var_dump($responce , $headers);

?>
</pre>

</body>
</html>