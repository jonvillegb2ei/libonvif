<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 18:04
 */

namespace LibOnvif;


class Response
{
    public $xml;

    public function __construct (string $body)
    {
        $xml = $body;
        $xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $xml);
        $this -> xml = simplexml_load_string($xml);
    }

}