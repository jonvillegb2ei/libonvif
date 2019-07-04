<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 17:03
 */

namespace LibOnvif\Contracts;


interface ICommand
{
    public function toString(): string;
    public function __toString (): string;
    public function parse(string $response);
    public function getToken (): ?string;
    public function setToken (string $token = null): ICommand;
    public function getProfileToken (): ?string;
    public function setProfileToken (string $profileToken = null): ICommand;
    public function getConfigurationToken (): ?string;
    public function setConfigurationToken (string $configurationToken = null): ICommand;
    public function setResponse(string $response): ICommand;
    public function getResponse(): string;
}