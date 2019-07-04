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

class GetStatus extends Command implements ICommand
{

    public function toString (): string
    {
        $return = '<GetStatus xmlns="http://www.onvif.org/ver20/ptz/wsdl">';
        if (!is_null($this->profileToken))
            $return .= sprintf('<ProfileToken>%s</ProfileToken>', $this->getProfileToken());
        $return .= '</GetStatus>';
        return $return;
    }

    public function parse (string $response)
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadXML($response);
        $return = [];
        $PTZStatus = $domDocument->getElementsByTagName('PTZStatus');
        foreach($PTZStatus as $status) {
            $return[] = $this->parseStatus($status);
        }
        return $return;
    }

    private function parseStatus (DOMElement $node)
    {
        $return = [];
        $position = $node->getElementsByTagName('Position');
        $return['position'] = $position->count() > 0 ? $this->parsePosition($position[0]) : null;
        $moveStatus = $node->getElementsByTagName('MoveStatus');
        $return['moveStatus'] = $moveStatus->count() > 0 ? $this->parseMoveStatus($moveStatus[0]) : null;
        $return['UtcTime'] = Carbon::parse($this->getFirstElementContent($node->getElementsByTagName('UtcTime')));
        return $return;
    }

    private function parsePosition (DOMElement $node)
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

    private function parseMoveStatus (DOMElement $node)
    {
        $return = [];
        $return['panTilt'] = $this->getFirstElementContent($node->getElementsByTagName('PanTilt'));
        $return['zoom'] = $this->getFirstElementContent($node->getElementsByTagName('Zoom'));
        return $return;
    }


}