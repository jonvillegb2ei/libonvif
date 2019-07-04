<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 17:03
 */

namespace LibOnvif\Commands;



use LibOnvif\Contracts\ICommand;

class GetStreamUri extends Command implements ICommand
{

    private $stream;
    private $protocol;

    public function __construct (string $stream = 'RTP-Unicast', string $protocol = 'RTSP')
    {
        $this->stream = $stream;
        $this->protocol = $protocol;
    }

    public function toString (): string
    {
        $return = '<GetStreamUri xmlns="http://www.onvif.org/ver10/media/wsdl">';
        $return.= '<StreamSetup>';
        $return.= sprintf('<Stream xmlns="http://www.onvif.org/ver10/schema">%s</Stream>', $this->getStream());
        $return.= '<Transport xmlns="http://www.onvif.org/ver10/schema">';
        $return.= sprintf('<Protocol>%s</Protocol>', $this->getProtocol());
        $return.= '</Transport>';
        $return.= '</StreamSetup>';
        if (!is_null($this->profileToken))
            $return .= sprintf('<ProfileToken>%s</ProfileToken>', $this->getProfileToken());
        $return .= '</GetStreamUri>';
        return $return;
    }

    public function parse (string $response)
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadXML($response);
        $return = [];
        $return['uri'] = $this->getFirstElementContent($domDocument->getElementsByTagName('Uri'));
        $return['invalidAfterConnect'] = $this->parseBool($this->getFirstElementContent($domDocument->getElementsByTagName('InvalidAfterConnect')));
        $return['invalidAfterReboot'] = $this->parseBool($this->getFirstElementContent($domDocument->getElementsByTagName('InvalidAfterReboot')));
        $return['timeout'] = $this->getFirstElementContent($domDocument->getElementsByTagName('Timeout'));
        return $return;
    }

    /**
     * @return string
     */
    public function getStream (): string
    {
        return $this->stream;
    }

    /**
     * @param string $stream
     * @return GetStreamUri
     */
    public function setStream (string $stream): GetStreamUri
    {
        $this->stream = $stream;
        return $this;
    }

    /**
     * @return string
     */
    public function getProtocol (): string
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     * @return GetStreamUri
     */
    public function setProtocol (string $protocol): GetStreamUri
    {
        $this->protocol = $protocol;
        return $this;
    }


}