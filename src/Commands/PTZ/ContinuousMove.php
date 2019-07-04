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

class ContinuousMove extends Command implements ICommand
{

    private $x;
    private $y;
    private $zoom;
    private $timeout;

    public function __construct (float $x = null, float $y = null, float $zoom = null, int $timeout = null)
    {
        $this->x = $x;
        $this->y = $y;
        $this->zoom = $zoom;
        $this->timeout = $timeout;
    }

    public function toString (): string
    {
        $return = '<ContinuousMove xmlns="http://www.onvif.org/ver20/ptz/wsdl">';
        if (!is_null($this->profileToken))
            $return .= sprintf('<ProfileToken>%s</ProfileToken>', $this->getProfileToken());
        $return .= sprintf('<Velocity>%s</Velocity>', $this->panTiltZoomVectors());
        $return .= '</ContinuousMove>';
        return $return;
    }

    private function panTiltZoomVectors()
    {
        $return = '';
        if (!is_null($this->x) and !is_null($this->y))
            $return .= sprintf('<PanTilt x="%s" y="%s" xmlns="http://www.onvif.org/ver10/schema"/>',$this->x,$this->y);
        if (!is_null($this->zoom))
            $return .= sprintf('<Zoom x="%s" xmlns="http://www.onvif.org/ver10/schema"/>',$this->x,$this->y);
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
     * @return ContinuousMove
     */
    public function setX (float $x): ContinuousMove
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
     * @return ContinuousMove
     */
    public function setY (float $y): ContinuousMove
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
     * @return ContinuousMove
     */
    public function setZoom (float $zoom): ContinuousMove
    {
        $this->zoom = $zoom;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout (): int
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     * @return ContinuousMove
     */
    public function setTimeout (int $timeout): ContinuousMove
    {
        $this->timeout = $timeout;
        return $this;
    }


}