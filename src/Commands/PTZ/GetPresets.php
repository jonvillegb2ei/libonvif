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

class GetPresets extends Command implements ICommand
{

    public function toString (): string
    {
        $return = '<GetPresets xmlns="http://www.onvif.org/ver20/ptz/wsdl">';
        if (!is_null($this->profileToken))
            $return .= sprintf('<ProfileToken>%s</ProfileToken>', $this->getProfileToken());
        $return .= '</GetPresets>';
        return $return;
    }

    public function parse (string $response)
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadXML($response);
        $return = [];
        $presets = $domDocument->getElementsByTagName('Preset');
        foreach($presets as $preset) {
            $return[$preset->getAttribute('token')] = $this->parsePreset($preset);
        }
        return $return;
    }

    private function parsePreset (DOMElement $node)
    {
        $return = [];
        $return['token'] = $node->getAttribute('token');
        $return['name'] = $this->getFirstElementContent($node->getElementsByTagName('Name'));
        $PTZPosition = $node->getElementsByTagName('PTZPosition');
        $return['PTZPosition'] = $PTZPosition->count() > 0 ? $this->parsePTZPosition($PTZPosition[0]) : null;
        return $return;
    }

    private function parsePTZPosition (DOMElement $node)
    {
        $return = [];
        $panTilt = $node->getElementsByTagName('PanTilt');
        $return['panTilt'] = [
            'space' => $panTilt->count() > 0 ? $panTilt[0]->getAttribute('space') : null,
            'x' => $panTilt->count() > 0 ? (float)$panTilt[0]->getAttribute('x') : null,
            'y' => $panTilt->count() > 0 ? (float)$panTilt[0]->getAttribute('y') : null
        ];
        $zoom = $node->getElementsByTagName('Zoom');
        $return['zoom'] = [
            'space' => $zoom->count() > 0 ? $zoom[0]->getAttribute('space') : null,
            'x' => $zoom->count() > 0 ? (float)$zoom[0]->getAttribute('x') : null
        ];
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