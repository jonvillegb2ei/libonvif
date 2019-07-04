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

class Stop extends Command implements ICommand
{

    private $panTilt;
    private $zoom;

    public function __construct (string $panTilt = null, string $zoom = null)
    {
        $this->panTilt = $panTilt;
        $this->zoom = $zoom;
    }

    public function toString (): string
    {
        $return = '<Stop xmlns="http://www.onvif.org/ver20/ptz/wsdl">';
        if (!is_null($this->profileToken))
            $return .= sprintf('<ProfileToken>%s</ProfileToken>', $this->getProfileToken());
        if (!is_null($this->panTilt))
            $return .= sprintf('<PanTilt>%s</PanTilt>', $this->panTilt);
        if (!is_null($this->zoom))
            $return .= sprintf('<Zoom>%s</Zoom>', $this->zoom);
        $return .= '</Stop>';
        return $return;
    }

    /**
     * @return string
     */
    public function getPanTilt (): string
    {
        return $this->panTilt;
    }

    /**
     * @param string $panTilt
     * @return Stop
     */
    public function setPanTilt (string $panTilt): Stop
    {
        $this->panTilt = $panTilt;
        return $this;
    }

    /**
     * @return string
     */
    public function getZoom (): string
    {
        return $this->zoom;
    }

    /**
     * @param string $zoom
     * @return Stop
     */
    public function setZoom (string $zoom): Stop
    {
        $this->zoom = $zoom;
        return $this;
    }
}