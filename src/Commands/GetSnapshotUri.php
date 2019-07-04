<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 17:03
 */

namespace LibOnvif\Commands;



use LibOnvif\Contracts\ICommand;

class GetSnapshotUri extends Command implements ICommand
{

    public function toString (): string
    {
        $return = '<GetSnapshotUri xmlns="http://www.onvif.org/ver10/media/wsdl">';
        if (!is_null($this->profileToken))
            $return .= sprintf('<ProfileToken>%s</ProfileToken>', $this->getProfileToken());
        $return .= '</GetSnapshotUri>';
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

}