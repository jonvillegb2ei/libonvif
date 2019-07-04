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
use LibOnvif\Contracts\ICommand;

class GetCapabilities extends Command implements ICommand
{

    private $category;

    public function __construct (string $category  ='All')
    {
        $this->category = $category;
    }

    public function toString (): string
    {
        $return = '<GetCapabilities xmlns="http://www.onvif.org/ver10/device/wsdl">';
        $return.= sprintf('<Category>%s</Category>', $this->category);
        $return.= '</GetCapabilities>';
        return $return;
    }

    public function parse (string $response)
    {
        $domDocument = new DOMDocument();
        $domDocument->loadXML($response);
        $return = [];
        $capabilities = $domDocument->getElementsByTagName('Capabilities');
        foreach($capabilities as $capability)
            $return[] = $this->parseCapability($capability);
        return $return;
    }

    private function parseCapability (DOMElement $node)
    {
        $return = [];
        $analytics = $node->getElementsByTagName('Analytics');
        if ($analytics->count() > 0) {
            $return['analytics'] = [
                'XAddr' => $this->getFirstElementContent($node->getElementsByTagName('XAddr')),
                'ruleSupport' => $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('RuleSupport'))),
                'analyticsModuleSupport' => $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('AnalyticsModuleSupport'))),
            ];
        }
        $device = $node->getElementsByTagName('Device');
        if ($device->count() > 0) {
            $return['device'] = $this->parseDevice($device[0]);
        }
        return $return;
    }

    private function parseDevice (DOMElement $node)
    {
        $return = [];
        $return['XAddr'] = $this->getFirstElementContent($node->getElementsByTagName('XAddr'));
        $network = $node->getElementsByTagName('Network');
        if ($network->count() > 0) {
            $return['network'] = $this->parseNetwork($network[0]);
        }
        $system = $node->getElementsByTagName('System');
        if ($system->count() > 0) {
            $return['system'] = $this->parseSystem($system[0]);
        }
        $io = $node->getElementsByTagName('IO');
        if ($io->count() > 0) {
            $return['io'] = $this->parseIO($io[0]);
        }
        $security = $node->getElementsByTagName('Security');
        if ($security->count() > 0) {
            $return['security'] = $this->parseSecurity($security[0]);
        }
        return $return;
    }

    private function parseNetwork(DOMElement $node)
    {
        return [
            'IPFilter' => $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('IPFilter'))),
            'ZeroConfiguration' => $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('ZeroConfiguration'))),
            'IPVersion6' => $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('IPVersion6'))),
            'DynDNS' => $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('DynDNS'))),
            'Dot11Configuration' => $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('Dot11Configuration'))),
        ];
    }

    private function parseSystem(DOMElement $node)
    {
        $return = [
            'DiscoveryResolve' => $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('DiscoveryResolve'))),
            'DiscoveryBye' => $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('DiscoveryBye'))),
            'RemoteDiscovery' => $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('RemoteDiscovery'))),
            'SystemBackup' => $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('SystemBackup'))),
            'SystemLogging' => $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('SystemLogging'))),
            'FirmwareUpgrade' => $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('SystemLogging'))),
        ];
        $versions = $node->getElementsByTagName('SupportedVersions');
        if ($versions->count() > 0) {
            $return['supportedVersions'] = [];
            foreach($versions as $version) {
                $return['supportedVersions'][] = [
                    'major' => $this->parseInt($this->getFirstElementContent($version->getElementsByTagName('Major'))),
                    'minor' => $this->parseInt($this->getFirstElementContent($version->getElementsByTagName('Minor')))
                ];
            }
        }
        $extension = $node->getElementsByTagName('Extension');
        if ($extension->count() > 0) {
            $return['extension'] = [
                'HttpFirmwareUpgrade' => $this->parseBool($this->getFirstElementContent($extension[0]->getElementsByTagName('HttpFirmwareUpgrade'))),
                'HttpSystemBackup' => $this->parseBool($this->getFirstElementContent($extension[0]->getElementsByTagName('HttpFirmwareUpgrade'))),
                'HttpSystemLogging' => $this->parseBool($this->getFirstElementContent($extension[0]->getElementsByTagName('HttpFirmwareUpgrade'))),
                'HttpSupportInformation' => $this->parseBool($this->getFirstElementContent($extension[0]->getElementsByTagName('HttpFirmwareUpgrade'))),
            ];
        }
        return $return;
    }

    private function parseIO(DOMElement $node)
    {
        $return = [
            'inputConnectors' => $this->parseInt($this->getFirstElementContent($node->getElementsByTagName('InputConnectors'))),
            'relayOutputs' => $this->parseInt($this->getFirstElementContent($node->getElementsByTagName('RelayOutputs'))),
        ];
        $extension = $node->getElementsByTagName('Extension');
        if ($extension->count() > 0) {
            $return['extension'] = [
                'Auxiliary' => $this->parseBool($this->getFirstElementContent($extension[0]->getElementsByTagName('Auxiliary'))),
            ];
        }
        return $return;
    }

    private function parseSecurity (DOMElement $node)
    {
        $return = [];
        $return['TLS1.1'] = $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('TLS1.1')));
        $return['TLS1.2'] = $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('TLS1.1')));
        $return['OnboardKeyGeneration'] = $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('OnboardKeyGeneration')));
        $return['AccessPolicyConfig'] = $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('AccessPolicyConfig')));
        $return['X.509Token'] = $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('X.509Token')));
        $return['SAMLToken'] = $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('SAMLToken')));
        $return['KerberosToken'] = $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('KerberosToken')));
        $return['RELToken'] = $this->parseBool($this->getFirstElementContent($node->getElementsByTagName('RELToken')));
        return $return;
    }



}