<?php


class Curl {

    protected $headers = [];
    protected $url;
    protected $method = 'GET';

    protected $_curl;

    protected $_responce;

    protected $_respHeaders;

    protected $_http_code;

    protected $_ERROR;

    protected $curlopt_headers = true;

    public static $http_status_header;

    public function __construct($url , $method , $headers = []) {
        $this->url = $url;
        $this->method = $method;
        $this->headers = $headers;
    }

    public function query($postData = null){

        $this->_curl = curl_init();

        curl_setopt($this->_curl, CURLOPT_URL, $this->url);
        curl_setopt($this->_curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($this->_curl, CURLOPT_HEADER, $this->curlopt_headers);
        curl_setopt($this->_curl, CURLOPT_POST, ($this->method == 'POST' ? TRUE : FALSE) );
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, TRUE);
        if(!empty($postData)){
            curl_setopt($this->_curl, CURLOPT_POSTFIELDS,http_build_query($postData));
        }

        $responce = curl_exec($this->_curl);

        $header_size        = curl_getinfo($this->_curl, CURLINFO_HEADER_SIZE);
        $this->_respHeaders = substr($responce, 0, $header_size);
        $this->_responce    = substr($responce, $header_size);

        $this->_http_code = curl_getinfo($this->_curl, CURLINFO_HTTP_CODE);
        $this->_ERROR = curl_error($this->_curl);
        curl_close($this->_curl);
        return $this->_responce;
    }

    public function getStatus(){
        return $this->_http_code;
    }

    public function getResponceHeaders(){
        $parts = explode("\r\n", $this->_respHeaders);
        $array = array_values(array_filter($parts , function ($var){
            if( !empty($var) && strpos($var , 'HTTP' ) !== false){
                self::$http_status_header = $var;
                return false;
            }else{
                return !empty($var);
            }
        }));
        $obj = [];
        foreach ($array as $aex){
            $x  = explode(':' , $aex , 2);
            $obj[trim($x[0])] = trim($x[1]);
        }
        return $obj;
    }
}



