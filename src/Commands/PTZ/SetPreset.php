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

class SetPreset extends Command implements ICommand
{

    private $presetName;
    private $presetToken;


    public function __construct (string $presetName = null, string $presetToken = null)
    {
        $this->presetName = $presetName;
        $this->presetToken = $presetToken;
    }

    public function toString (): string
    {
        $return = '<SetPreset xmlns="http://www.onvif.org/ver20/ptz/wsdl">';
        if (!is_null($this->profileToken))
            $return .= sprintf('<ProfileToken>%s</ProfileToken>', $this->getProfileToken());
        if (!is_null($this->getPresetName()))
            $return .= sprintf('<PresetName>%s</PresetName>', $this->getPresetName());
        if (!is_null($this->getPresetToken()))
            $return .= sprintf('<PresetToken>%s</PresetToken>', $this->getPresetToken());
        $return .= '</SetPreset>';
        return $return;
    }

    public function parse (string $response)
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadXML($response);
        $return = [];
        $return['token'] = $this->getFirstElementContent($domDocument->getElementsByTagName('PresetToken'));
        return $return;
    }



    /**
     * @return string
     */
    public function getPresetName (): ?string
    {
        return $this->presetName;
    }

    /**
     * @param string $presetName
     * @return SetPreset
     */
    public function setPresetName (string $presetName = null): SetPreset
    {
        $this->presetName = $presetName;
        return $this;
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
     * @return SetPreset
     */
    public function setPresetToken (string $presetToken = null): SetPreset
    {
        $this->presetToken = $presetToken;
        return $this;
    }
}