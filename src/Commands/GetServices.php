<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 17:03
 */

namespace LibOnvif\Commands;



use DOMElement;
use LibOnvif\Contracts\ICommand;

class GetServices extends Command implements ICommand
{
    private $includeCapability;

    public function __construct (bool $includeCapability = true)
    {
        $this->includeCapability = $includeCapability;
    }

    public function toString (): string
    {
        $return = '<GetServices xmlns="http://www.onvif.org/ver10/device/wsdl">';
        $return .= sprintf('<IncludeCapability>%s</IncludeCapability>', $this->includeCapability ? 'true' : 'false');
        $return .= '</GetServices>';
        return $return;
    }

    public function parse (string $response)
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadXML($response);
        $return = [];
        $services = $domDocument->getElementsByTagName('Service');
        foreach($services as $service) {
            $return[$this->getServiceName($service)] = $this->parseService($service);
        }
        return $return;
    }

    public function getServiceName(DOMElement $node)
    {
        $XAddr = $this->getFirstElementContent($node->getElementsByTagName('XAddr'));
        if ($XAddr) {
            $parts = explode('/', $XAddr);
            return $parts[count($parts) - 1];
        } else return uniqid('service');
    }

    private function parseService (DOMElement $node)
    {
        $return = [];
        $return['namespace'] = $this->getFirstElementContent($node->getElementsByTagName('Namespace'));
        $return['XAddr'] = $this->getFirstElementContent($node->getElementsByTagName('XAddr'));
        $capabilities = $node->getElementsByTagName('Capabilities');
        foreach($capabilities as $capability) {
            $capabilityEntry = $this->parseCapability($capability);
            if (count($capabilityEntry) > 0)
                $return['capabilities'][] = $capabilityEntry;
        }
        $version = $node->getElementsByTagName('Version');
        if ($version->count() > 0) {
            $return['version'] = [
                'major' => (int)$this->getFirstElementContent($version[0]->getElementsByTagName('Major')),
                'minor' => (int)$this->getFirstElementContent($version[0]->getElementsByTagName('Minor')),
            ];
        }
        return $return;
    }


    private function parseCapability (DOMElement $node)
    {
        $return = [];

        $attributes = [
            'ruleSupport' => function($value) { return $this->parseBool($value); },
            'analyticsModuleSupport' => function($value) { return $this->parseBool($value); },
            'cellBasedSceneDescriptionSupported' => function($value) { return $this->parseBool($value); },
            'imageStabilization' => function($value) { return $this->parseBool($value); },
            'snapshotUri' => function($value) { return $this->parseBool($value); },
            'rotation' => function($value) { return $this->parseBool($value); },
            'EFlip' => function($value) { return $this->parseBool($value); },
            'reverse' => function($value) { return $this->parseBool($value); },
            'getCompatibleConfigurations' => function($value) { return $this->parseBool($value); },
            'WSSubscriptionPolicySupport' => function($value) { return $this->parseBool($value); },
            'WSPullPointSupport' => function($value) { return $this->parseBool($value); },
            'WSPausableSubscriptionManagerInterfaceSupport' => function($value) { return $this->parseBool($value); },
            'maxNotificationProducers' => function($value) { return $this->parseInt($value); },
            'maxPullPoints' => function($value) { return $this->parseInt($value); },
            'persistenNotificationStorage' => function($value) { return $this->parseBool($value); },
            'videoSources' => function($value) { return $this->parseInt($value); },
            'videoOutputs' => function($value) { return $this->parseInt($value); },
            'audioSources' => function($value) { return $this->parseInt($value); },
            'audioOutputs' => function($value) { return $this->parseInt($value); },
            'relayOutputs' => function($value) { return $this->parseInt($value); },
            'serialPorts' => function($value) { return $this->parseInt($value); },
            'digitalInputs' => function($value) { return $this->parseInt($value); },
        ];

        foreach($attributes as $name => $callback) {
            if ($node->hasAttribute(ucwords($name)))
                $return[$name] = $callback($node->getAttribute(ucwords($name)));
        }

        $network = $node->getElementsByTagName('Network');
        if ($network->count() > 0) {
            $return['network'] = [
                'NTP' => $this->parseBool($network[0]->getAttribute('NTP')),
                'hostnameFromDHCP' => $this->parseBool($network[0]->getAttribute('HostnameFromDHCP')),
                'dot11Configuration' => $this->parseBool($network[0]->getAttribute('Dot11Configuration')),
                'dynDNS' => $this->parseBool($network[0]->getAttribute('DynDNS')),
                'IPVersion6' => $this->parseBool($network[0]->getAttribute('IPVersion6')),
                'zeroConfiguration' => $this->parseBool($network[0]->getAttribute('ZeroConfiguration')),
                'IPFilter' => $this->parseBool($network[0]->getAttribute('IPFilter'))
            ];
        }

        $security = $node->getElementsByTagName('Security');
        if ($network->count() > 0) {
            $return['security'] = [
                "maxPasswordLength" => $this->parseInt($security[0]->getAttribute('MaxPasswordLength')),
                "maxUserNameLength" => $this->parseInt($security[0]->getAttribute('MaxUserNameLength')),
                "maxUsers" => $this->parseInt($security[0]->getAttribute('MaxUsers')),
                "RELToken" => $this->parseBool($security[0]->getAttribute('RELToken')),
                "httpDigest" => $this->parseBool($security[0]->getAttribute('HttpDigest')),
                "usernameToken" => $this->parseBool($security[0]->getAttribute('UsernameToken')),
                "kerberosToken" => $this->parseBool($security[0]->getAttribute('KerberosToken')),
                "SAMLToken" => $this->parseBool($security[0]->getAttribute('SAMLToken')),
                "X.509Token" => $this->parseBool($security[0]->getAttribute('X.509Token')),
                "remoteUserHandling" => $this->parseBool($security[0]->getAttribute('RemoteUserHandling')),
                "dot1X" => $this->parseBool($security[0]->getAttribute('Dot1X')),
                "defaultAccessPolicy" => $this->parseBool($security[0]->getAttribute('DefaultAccessPolicy')),
                "accessPolicyConfig" => $this->parseBool($security[0]->getAttribute('AccessPolicyConfig')),
                "onboardKeyGeneration" => $this->parseBool($security[0]->getAttribute('OnboardKeyGeneration')),
                "TLS1.2" => $this->parseBool($security[0]->getAttribute('TLS1.2')),
                "TLS1.1" => $this->parseBool($security[0]->getAttribute('TLS1.1')),
                "TLS1.0" => $this->parseBool($security[0]->getAttribute('TLS1.0'))
            ];
        }

        $system = $node->getElementsByTagName('System');
        if ($system->count() > 0) {
            $return['system'] = [
                "HTTPSupportInformation" => $this->parseBool($system[0]->getAttribute('HTTPSupportInformation')),
                "HTTPSystemLogging" => $this->parseBool($system[0]->getAttribute('HTTPSystemLogging')),
                "HttpSystemBackup" => $this->parseBool($system[0]->getAttribute('HttpSystemBackup')),
                "HttpFirmwareUpgrade" => $this->parseBool($system[0]->getAttribute('HttpFirmwareUpgrade')),
                "firmwareUpgrade" => $this->parseBool($system[0]->getAttribute('FirmwareUpgrade')),
                "systemLogging" => $this->parseBool($system[0]->getAttribute('SystemLogging')),
                "systemBackup" => $this->parseBool($system[0]->getAttribute('SystemBackup')),
                "remoteDiscovery" => $this->parseBool($system[0]->getAttribute('RemoteDiscovery')),
                "discoveryBye" => $this->parseBool($system[0]->getAttribute('DiscoveryBye')),
                "discoveryResolve" => $this->parseBool($system[0]->getAttribute('DiscoveryResolve')),
            ];
        }

        $profileCapabilities = $node->getElementsByTagName('ProfileCapabilities');
        if ($profileCapabilities->count() > 0) {
            $return['profileCapabilities'] = [
                "maximumNumberOfProfiles" => $this->parseInt($profileCapabilities[0]->getAttribute('MaximumNumberOfProfiles'))
            ];
        }

        $streamingCapabilities = $node->getElementsByTagName('StreamingCapabilities');
        if ($streamingCapabilities->count() > 0) {
            $return['streamingCapabilities'] = [
                "RTSPStreaming" => $this->parseBool($streamingCapabilities[0]->getAttribute('RTSPStreaming')),
                "RTPMulticast" => $this->parseBool($streamingCapabilities[0]->getAttribute('RTPMulticast')),
                "RTP_RTSP_TCP" => $this->parseBool($streamingCapabilities[0]->getAttribute('RTP_RTSP_TCP')),
                "nonAggregateControl" => $this->parseBool($streamingCapabilities[0]->getAttribute('NonAggregateControl')),
            ];
        }

        return $return;
    }

}