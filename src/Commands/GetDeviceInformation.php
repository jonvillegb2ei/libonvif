<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 17:03
 */

namespace LibOnvif\Commands;



use DOMDocument;
use LibOnvif\Contracts\ICommand;

class GetDeviceInformation extends Command implements ICommand
{

    public function toString (): string
    {
        return '<GetDeviceInformation xmlns="http://www.onvif.org/ver10/device/wsdl"/>';
    }

    public function parse (string $response)
    {
        $domDocument = new DOMDocument();
        $domDocument->loadXML($response);
        $return = [];
        $return['manufacturer'] = $this->getFirstElementContent($domDocument->getElementsByTagName('Manufacturer'));
        $return['model'] = $this->getFirstElementContent($domDocument->getElementsByTagName('Model'));
        $return['firmwareVersion'] = $this->getFirstElementContent($domDocument->getElementsByTagName('FirmwareVersion'));
        $return['serialNumber'] = $this->getFirstElementContent($domDocument->getElementsByTagName('SerialNumber'));
        $return['hardwareId'] = $this->getFirstElementContent($domDocument->getElementsByTagName('HardwareId'));
        return $return;
    }
}