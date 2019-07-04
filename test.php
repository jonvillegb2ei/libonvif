<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 16:20
 */


include('vendor/autoload.php');

use Carbon\Carbon;
use Laravie\Parser\Xml\Document;
use Laravie\Parser\Xml\Reader;
use LibOnvif\Client;
use LibOnvif\Commands\GetCapabilities;
use LibOnvif\Commands\GetDeviceInformation;
use LibOnvif\Commands\GetHostname;
use LibOnvif\Commands\GetProfiles;
use LibOnvif\Commands\GetServiceCapabilities;
use LibOnvif\Commands\GetServices;
use LibOnvif\Commands\GetSnapshotUri;
use LibOnvif\Commands\GetStreamUri;
use LibOnvif\Commands\GetSystemDateAndTime;
use LibOnvif\Commands\PTZ\AbsoluteMove;
use LibOnvif\Commands\PTZ\ContinuousMove;
use LibOnvif\Commands\PTZ\GetConfigurations;
use LibOnvif\Commands\PTZ\GetNodes;
use LibOnvif\Commands\PTZ\GetPresets;
use LibOnvif\Commands\PTZ\GetStatus;
use LibOnvif\Commands\PTZ\GotoPreset;
use LibOnvif\Commands\PTZ\RemovePreset;
use LibOnvif\Commands\PTZ\SetHomePosition;
use LibOnvif\Commands\PTZ\SetPreset;
use LibOnvif\Commands\PTZ\Stop;
use LibOnvif\Commands\SystemReboot;
use LibOnvif\Envelope;
use LibOnvif\Nonce;
use LibOnvif\Security;


$credentials = file_get_contents('/tmp/ipcamera.txt');
list($login, $password) = explode("\n", $credentials);

//$nonce = Nonce::generate(16);
//
//var_dump($nonce);
//var_dump($nonce->toString());
//var_dump($nonce->getBuffer());

//$security = Security::generate($login, $password);


//$envelope = (new Envelope())
//    ->setSecurity($security)
//    ->setBody(new GetSystemDateAndTime());


$client = new Client('192.168.50.13', $login, $password);

//var_dump($client->send(new GetNodes()));
//var_dump($client->send(new GetConfigurations()));
//var_dump($client->send(new SetHomePosition()));
//var_dump($client->send(new GetServices()));


$services = $client->send(new GetServices());
//var_dump($services["ptz_service"]);



var_dump($client->send(new GetCapabilities()));

//var_dump($client->send(new GetDeviceInformation()));
//var_dump($client->send(new GetPresets()));
//var_dump($client->send(new RemovePreset('1')));
//var_dump($client->send(new GetStatus()));
//var_dump($client->send(new GetStreamUri()));

//$cde = new GetCapabilities();
//var_dump($client->send($cde));
//var_dump($cde->getResponse());
//var_dump($client->send(new GetSnapshotUri()));
//var_dump($client->send(new GetStreamUri()));
//var_dump($client->getConfigurationToken());


//$presetResult = $client->send(new SetPreset('TestPreset4'));
//
//
//if ($client->send(new ContinuousMove(0.2,0.2,0))) {
//    sleep(1);
//    $client->send(new Stop());
//}
//
//sleep(1);
//
//$result = $client->send(new GotoPreset($presetResult['token']));
//
//sleep(1);
//var_dump($client->send(new RemovePreset($presetResult['token'])));

//var_dump($client->send($envelope));
