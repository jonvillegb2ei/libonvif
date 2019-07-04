<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 17:03
 */

namespace LibOnvif\Commands\PTZ;


use LibOnvif\Commands\Command;
use LibOnvif\Contracts\ICommand;

class RelativeMove extends Command implements ICommand
{

    private $x;
    private $y;
    private $zoom;
    private $speedX;
    private $speedY;
    private $speedZoom;

    public function __construct (float $x = null, float $y = null, float $zoom = null, float $speedX = null, float $speedY = null, float $speedZoom = null)
    {
        $this->x = $x;
        $this->y = $y;
        $this->zoom = $zoom;
        $this->speedX = $speedX;
        $this->speedY = $speedY;
        $this->speedZoom = $speedZoom;
    }

    public function toString (): string
    {
        $return = '<RelativeMove xmlns="http://www.onvif.org/ver20/ptz/wsdl">';
        if (!is_null($this->profileToken))
            $return .= sprintf('<ProfileToken>%s</ProfileToken>', $this->getProfileToken());
        $return .= sprintf('<Translation>%s</Translation>', $this->getPosition());
        if (!is_null($this->speedX) and !is_null($this->speedY) and !is_null($this->speedZoom))
            $return .= sprintf('<Speed>%s</Speed>', $this->getSpeed());
        $return .= '</RelativeMove>';
        return $return;
    }


    private function getPosition()
    {
        $return = '';
        if (!is_null($this->x) and !is_null($this->y))
            $return .= sprintf('<PanTilt x="%s" y="%s" xmlns="http://www.onvif.org/ver10/schema"/>',$this->x,$this->y);
        if (!is_null($this->zoom))
            $return .= sprintf('<Zoom x="%s" xmlns="http://www.onvif.org/ver10/schema"/>',$this->x,$this->y);
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

    /**
     * @return float
     */
    public function getX (): float
    {
        return $this->x;
    }

    /**
     * @param float $x
     * @return RelativeMove
     */
    public function setX (float $x): RelativeMove
    {
        $this->x = $x;
        return $this;
    }

    /**
     * @return float
     */
    public function getY (): float
    {
        return $this->y;
    }

    /**
     * @param float $y
     * @return RelativeMove
     */
    public function setY (float $y): RelativeMove
    {
        $this->y = $y;
        return $this;
    }

    /**
     * @return float
     */
    public function getZoom (): float
    {
        return $this->zoom;
    }

    /**
     * @param float $zoom
     * @return RelativeMove
     */
    public function setZoom (float $zoom): RelativeMove
    {
        $this->zoom = $zoom;
        return $this;
    }

    /**
     * @return float
     */
    public function getSpeedX (): float
    {
        return $this->speedX;
    }

    /**
     * @param float $speedX
     * @return RelativeMove
     */
    public function setSpeedX (float $speedX): RelativeMove
    {
        $this->speedX = $speedX;
        return $this;
    }

    /**
     * @return float
     */
    public function getSpeedY (): float
    {
        return $this->speedY;
    }

    /**
     * @param float $speedY
     * @return RelativeMove
     */
    public function setSpeedY (float $speedY): RelativeMove
    {
        $this->speedY = $speedY;
        return $this;
    }

    /**
     * @return float
     */
    public function getSpeedZoom (): float
    {
        return $this->speedZoom;
    }

    /**
     * @param float $speedZoom
     * @return RelativeMove
     */
    public function setSpeedZoom (float $speedZoom): RelativeMove
    {
        $this->speedZoom = $speedZoom;
        return $this;
    }

}