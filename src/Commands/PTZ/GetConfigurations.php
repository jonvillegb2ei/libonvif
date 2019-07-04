<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 17:03
 */

namespace LibOnvif\Commands\PTZ;


use DOMElement;
use LibOnvif\Commands\Command;
use LibOnvif\Contracts\ICommand;

class GetConfigurations extends Command implements ICommand
{

    public function toString (): string
    {
        $return = '<GetConfigurations xmlns="http://www.onvif.org/ver20/ptz/wsdl">';
        $return .= '</GetConfigurations>';
        return $return;
    }

    public function parse (string $response)
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadXML($response);
        $return = [];
        $configurations = $domDocument->getElementsByTagName('PTZConfiguration');
        foreach($configurations as $configuration) {
            $return[$configuration->getAttribute('token')] = $this->parseConfiguration($configuration);
        }
        return $return;
    }

    private function parseConfiguration (DOMElement $node)
    {
        $return = [];
        $return['token'] = $node->getAttribute('token');
        $return['moveRamp'] = $node->getAttribute('MoveRamp');
        $return['presetRamp'] = $node->getAttribute('PresetRamp');
        $return['presetTourRamp'] = $node->getAttribute('PresetTourRamp');
        $return['name'] = $this->getFirstElementContent($node->getElementsByTagName('Name'));
        $return['useCount'] = $this->getFirstElementContent($node->getElementsByTagName('UseCount'));
        $return['nodeToken'] = $this->getFirstElementContent($node->getElementsByTagName('NodeToken'));
        $return['defaultAbsolutePantTiltPositionSpace'] = $this->getFirstElementContent($node->getElementsByTagName('DefaultAbsolutePantTiltPositionSpace'));
        $return['defaultAbsoluteZoomPositionSpace'] = $this->getFirstElementContent($node->getElementsByTagName('DefaultAbsoluteZoomPositionSpace'));
        $return['defaultRelativePanTiltTranslationSpace'] = $this->getFirstElementContent($node->getElementsByTagName('DefaultAbsoluteZoomPositionSpace'));
        $return['defaultRelativeZoomTranslationSpace'] = $this->getFirstElementContent($node->getElementsByTagName('DefaultAbsoluteZoomPositionSpace'));
        $return['defaultContinuousPanTiltVelocitySpace'] = $this->getFirstElementContent($node->getElementsByTagName('DefaultAbsoluteZoomPositionSpace'));
        $return['defaultContinuousZoomVelocitySpace'] = $this->getFirstElementContent($node->getElementsByTagName('DefaultAbsoluteZoomPositionSpace'));
        $defaultPTZSpeed = $node->getElementsByTagName('DefaultPTZSpeed');
        $return['defaultPTZSpeed'] = $defaultPTZSpeed->count() > 0 ? $this->parseDefaultPTZSpeed($defaultPTZSpeed[0]) : null;
        $return['defaultPTZTimeout'] = $this->getFirstElementContent($node->getElementsByTagName('DefaultPTZTimeout'));
        $panTiltLimits = $node->getElementsByTagName('PanTiltLimits');
        $return['panTiltLimits'] = $panTiltLimits->count() > 0 ? $this->parsePanTiltLimits($panTiltLimits[0]) : null;
        $zoomLimits = $node->getElementsByTagName('ZoomLimits');
        $return['zoomLimits'] = $zoomLimits->count() > 0 ? $this->parseZoomLimits($zoomLimits[0]) : null;
        return $return;
    }

    private function parseDefaultPTZSpeed (DOMElement $node)
    {
        $return = [];
        $panTilt = $node->getElementsByTagName('PanTilt');
        $return['PanTilt'] = [
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

    private function parsePanTiltLimits (DOMElement $node)
    {
        $return = [];
        $return['URI'] = $this->getFirstElementContent($node->getElementsByTagName('URI'));
        $xRange = $node->getElementsByTagName('XRange');
        $return['XRange'] = $xRange->count() > 0 ? $this->parseRange($xRange[0]) : null;
        $yRange = $node->getElementsByTagName('YRange');
        $return['YRange'] = $yRange->count() > 0 ? $this->parseRange($yRange[0]) : null;
        return $return;
    }

    private function parseZoomLimits (DOMElement $node)
    {
        $return = [];
        $return['URI'] = $this->getFirstElementContent($node->getElementsByTagName('URI'));
        $xRange = $node->getElementsByTagName('XRange');
        $return['XRange'] = $xRange->count() > 0 ? $this->parseRange($xRange[0]) : null;
        return $return;
    }


    private function parseRange (DOMElement $node)
    {
        $return = [];
        $return['min'] = (float)$this->getFirstElementContent($node->getElementsByTagName('Min'));
        $return['max'] = (float)$this->getFirstElementContent($node->getElementsByTagName('Max'));
        return $return;
    }


}