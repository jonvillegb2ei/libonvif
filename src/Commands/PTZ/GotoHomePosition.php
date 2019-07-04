<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 17:03
 */

namespace LibOnvif\Commands\PTZ;


use Carbon\Carbon;
use DOMElement;
use LibOnvif\Commands\Command;
use LibOnvif\Contracts\ICommand;

class GotoHomePosition extends Command implements ICommand
{

    private $speedX;
    private $speedY;
    private $speedZoom;

    public function toString (): string
    {
        $return = '<GotoHomePosition xmlns="http://www.onvif.org/ver20/ptz/wsdl">';
        if (!is_null($this->profileToken))
            $return .= sprintf('<ProfileToken>%s</ProfileToken>', $this->getProfileToken());

        if (!is_null($this->speedX) and !is_null($this->speedY) and !is_null($this->speedZoom))
            $return .= sprintf('<Speed>%s</Speed>', $this->getSpeed());

        $return .= '</GotoHomePosition>';
        return $return;
    }

    private function getSpeed()
    {
        $return = '';
        if (!is_null($this->speedX) and !is_null($this->speedX))
            $return .= sprintf('<PanTilt x="%s" y="%s" xmlns="http://www.onvif.org/ver10/schema"/>',$this->speedX,$this->speedX);
        if (!is_null($this->speedZoom))
            $return .= sprintf('<Zoom x="%s" xmlns="http://www.onvif.org/ver10/schema"/>',$this->speedZoom);
        return $return;
    }

    public function parse (string $response)
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadXML($response);
        $return = [];

        var_dump($response);

        return $return;
    }


    /**
     * @return mixed
     */
    public function getSpeedX ()
    {
        return $this->speedX;
    }

    /**
     * @param mixed $speedX
     * @return GotoHomePosition
     */
    public function setSpeedX ($speedX)
    {
        $this->speedX = $speedX;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpeedY ()
    {
        return $this->speedY;
    }

    /**
     * @param mixed $speedY
     * @return GotoHomePosition
     */
    public function setSpeedY ($speedY)
    {
        $this->speedY = $speedY;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpeedZoom ()
    {
        return $this->speedZoom;
    }

    /**
     * @param mixed $speedZoom
     * @return GotoHomePosition
     */
    public function setSpeedZoom ($speedZoom)
    {
        $this->speedZoom = $speedZoom;
        return $this;
    }


}