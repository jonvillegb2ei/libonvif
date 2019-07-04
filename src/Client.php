<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 17:41
 */

namespace LibOnvif;


use Carbon\Carbon;
use LibOnvif\Commands\GetProfiles;
use LibOnvif\Commands\GetSystemDateAndTime;
use LibOnvif\Commands\PTZ\GetConfigurations;
use LibOnvif\Contracts\ICommand;
use Requests;

class Client
{

    private $hostname;
    private $port;
    private $path;

    private $username;
    private $password;

    private $dateDiff;
    private $profiles = [];
    private $configuration = [];

    public function __construct (string $hostname, string $username = null, string $password = null, int $port = 80, string $path = '/onvif/device_service')
    {
        $this->hostname = $hostname;
        $this->port = $port;
        $this->path = $path;

        $this->username = $username;
        $this->password = $password;

        $this->retrieveTimeDiff();
        $this->retrieveProfiles();
        $this->retrieveConfiguration();
    }

    static public function fromUrl(string $url)
    {
        $parts = parse_url($url);
        return new self($parts["host"], is_numeric($parts["port"]) ? $parts["port"] : 80, $parts["path"]);
    }

    public function getUrl(): string
    {
        return sprintf("http://%s:%s%s", $this->hostname, $this->port, $this->path);
    }

    public function getToken()
    {
        return Security::generate($this->username, $this->password, Carbon::now()->addSeconds($this->dateDiff))->passwordDigest();
    }

    public function getProfileToken()
    {
        $keys = array_keys($this->profiles);
        return count($keys) > 0 ? $keys[0] : '';
    }

    public function getConfigurationToken()
    {
        $keys = array_keys($this->configuration);
        return count($this->configuration) > 0 ? $this->configuration[$keys[0]]['name'] : '';
    }

    public function getHeaders()
    {
        $headers = [];
        $headers['Content-Type'] = 'application/soap+xml';
        $headers['Charset'] = 'utf-8';

        return $headers;
    }

    private function createEnvelope(ICommand $command)
    {
        $return = (new Envelope());
        if (!is_null($this->username) and !is_null($this->password))
            $return->setSecurity(Security::generate($this->username, $this->password));
        if (is_null($command->getToken())) $command->setToken($this->getToken());
        if (is_null($command->getProfileToken())) $command->setProfileToken($this->getProfileToken());
        if (is_null($command->getConfigurationToken())) $command->setConfigurationToken($this->getConfigurationToken());
        $return->setBody($command);
        return $return;
    }

    public function send(ICommand $command)
    {
        $envelope = $this->createEnvelope($command);
        $response = Requests::post($this->getUrl(), $this->getHeaders(), $envelope->toString());
        return $command->setResponse($response->body)->parse($response->body);
    }

    private function retrieveTimeDiff ()
    {
        $data = $this->send(new GetSystemDateAndTime());
        $now = count($data['timeZones']) > 0 ? Carbon::now($data['timeZones'][0]) : Carbon::now();
        $this->dateDiff = $now->diffInSeconds($data['dates']['LocalDateTime']['date']);
    }

    private function retrieveProfiles ()
    {
        $this->profiles = $this->send(new GetProfiles());
    }

    private function retrieveConfiguration ()
    {
        $this->configuration = $this->send(new GetConfigurations());
    }

    /**
     * @return mixed
     */
    public function getUsername ()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return Client
     */
    public function setUsername ($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword ()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return Client
     */
    public function setPassword ($password)
    {
        $this->password = $password;
        return $this;
    }


}