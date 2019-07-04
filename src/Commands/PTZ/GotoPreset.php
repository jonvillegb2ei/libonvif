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

class GotoPreset extends Command implements ICommand
{

    private $presetToken;
    private $speedX;
    private $speedY;
    private $speedZoom;


    public function __construct (string $presetToken = null)
    {
        $this->presetToken = $presetToken;
    }

    public function toString (): string
    {
        $return = '<GotoPreset xmlns="http://www.onvif.org/ver20/ptz/wsdl">';
        if (!is_null($this->profileToken))
            $return .= sprintf('<ProfileToken>%s</ProfileToken>', $this->getProfileToken());
        if (!is_null($this->getPresetToken()))
            $return .= sprintf('<PresetToken>%s</PresetToken>', $this->getPresetToken());
        if (!is_null($this->speedX) and !is_null($this->speedY) and !is_null($this->speedZoom))
            $return .= sprintf('<Speed>%s</Speed>', $this->getSpeed());
        $return .= '</GotoPreset>';
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
     * @return string
     */
    public function getPresetToken (): ?string
    {
        return $this->presetToken;
    }

    /**
     * @param string $presetToken
     * @return GotoPreset
     */
    public function setPresetToken (string $presetToken = null): GotoPreset
    {
        $this->presetToken = $presetToken;
        return $this;
    }
}