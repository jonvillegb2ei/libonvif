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

class RemovePreset extends Command implements ICommand
{

    private $presetToken;


    public function __construct (string $presetToken = null)
    {
        $this->presetToken = $presetToken;
    }

    public function toString (): string
    {
        $return = '<RemovePreset xmlns="http://www.onvif.org/ver20/ptz/wsdl">';
        if (!is_null($this->profileToken))
            $return .= sprintf('<ProfileToken>%s</ProfileToken>', $this->getProfileToken());
        if (!is_null($this->getPresetToken()))
            $return .= sprintf('<PresetToken>%s</PresetToken>', $this->getPresetToken());
        $return .= '</RemovePreset>';
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
     * @return RemovePreset
     */
    public function setPresetToken (string $presetToken = null): RemovePreset
    {
        $this->presetToken = $presetToken;
        return $this;
    }
}