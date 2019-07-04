<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 17:03
 */

namespace LibOnvif\Commands;


use DOMDocument;
use DOMElement;
use DOMNodeList;
use LibOnvif\Contracts\ICommand;

class GetProfiles extends Command implements ICommand
{

    public function toString (): string
    {
        return '<GetProfiles xmlns="http://www.onvif.org/ver10/media/wsdl"/>';
    }

    private function parseAudioSourceConfiguration(DOMElement $node)
    {
        $return = [];
        $return['name'] = $this->getFirstElementContent($node->getElementsByTagName('Name'));
        $return['useCount'] = $this->getFirstElementContent($node->getElementsByTagName('UseCount'));
        $return['sourceToken'] = $this->getFirstElementContent($node->getElementsByTagName('SourceToken'));
        return $return;
    }

    private function parseVideoEncoderConfiguration(DOMElement $node)
    {
        $return = [];
        $return['name'] = $this->getFirstElementContent($node->getElementsByTagName('Name'));
        $return['useCount'] = $this->getFirstElementContent($node->getElementsByTagName('UseCount'));
        $return['encoding'] = $this->getFirstElementContent($node->getElementsByTagName('Encoding'));
        $return['resolution'] = $this->extractElements($node, 'Resolution', ['width' => 'Width', 'height' => 'Height']);
        $return['quality'] = $this->getFirstElementContent($node->getElementsByTagName('Quality'));
        $return['rateControl'] = $this->extractElements($node, 'RateControl', ['frameRateLimit' => 'FrameRateLimit', 'encodingInterval' => 'EncodingInterval', 'bitrateLimit' => 'BitrateLimit']);
        $return['H264'] = $this->extractElements($node, 'H264', ['govLength' => 'GovLength', 'H264Profile' => 'H264Profile']);
        $return['multicast'] = $this->extractElements($node, 'Multicast', ['port' => 'Port', 'TTL' => 'TTL', 'autoStart' => 'AutoStart', 'type' => 'Type', 'IPv4Address' => 'IPv4Address', 'IPv6Address' => 'IPv6Address']);
        $return['sessionTimeout'] = $this->getFirstElementContent($node->getElementsByTagName('SessionTimeout'));
        return $return;
    }

    private function parseVideoSourceConfiguration(DOMElement $node)
    {
        $return = [];
        $return['name'] = $this->getFirstElementContent($node->getElementsByTagName('Name'));
        $return['useCount'] = $this->getFirstElementContent($node->getElementsByTagName('UseCount'));
        $return['sourceToken'] = $this->getFirstElementContent($node->getElementsByTagName('SourceToken'));
        $bounds = $node->getElementsByTagName('Bounds');
        $return['Bounds'] = [
            'x' => $bounds->count() > 0 ? $bounds[0]->getAttribute('x') : null,
            'y' => $bounds->count() > 0 ? $bounds[0]->getAttribute('y') : null,
            'width' => $bounds->count() > 0 ? $bounds[0]->getAttribute('width') : null,
            'height' => $bounds->count() > 0 ? $bounds[0]->getAttribute('height') : null
        ];
        return $return;
    }

    private function parseProfile(DOMElement $node)
    {
        $return = [];

        $return['token'] = $node->getAttribute('token');
        $return['fixed'] = $node->getAttribute('fixed') == 'true';
        $return['name'] = $this->getFirstElementContent($node->getElementsByTagName('Name'));

        $videoSourceConfiguration = $node->getElementsByTagName('VideoSourceConfiguration');
        $return['videoSourceConfiguration'] = $videoSourceConfiguration->count() > 0 ? $this->parseVideoSourceConfiguration($videoSourceConfiguration[0]) : null;

        $audioSourceConfiguration = $node->getElementsByTagName('AudioSourceConfiguration');
        $return['audioSourceConfiguration'] = $audioSourceConfiguration->count() > 0 ? $this->parseAudioSourceConfiguration($audioSourceConfiguration[0]) : null;

        $videoEncoderConfiguration = $node->getElementsByTagName('VideoEncoderConfiguration');
        $return['videoEncoderConfiguration'] = $videoEncoderConfiguration->count() > 0 ? $this->parseVideoEncoderConfiguration($videoEncoderConfiguration[0]) : null;


        return $return;
    }

    public function parse (string $response)
    {
        $domDocument = new DOMDocument();
        $domDocument->loadXML($response);
        $return = [];
        $profiles = $domDocument->getElementsByTagName('Profiles');
        foreach($profiles as $profile) {
            $return[$profile->getAttribute('token')] = $this->parseProfile($profile);
        }
        return $return;
    }
}