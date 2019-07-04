<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 17:03
 */

namespace LibOnvif\Commands;


use LibOnvif\Contracts\ICommand;

class SetScopes extends Command implements ICommand
{
    private $scopes;

    public function __construct (array $scope = null)
    {
        $this->scopes = $scope ? $scope : [];
    }

    public function toString (): string
    {
        $return = '<SetScopes xmlns="http://www.onvif.org/ver10/device/wsdl">';
        foreach($this->scopes as $scope) {
            $return .= sprintf('<Scopes>%s</Scopes>', $scope);
        }
        $return .= '</SetScopes>';
        return $return;
    }

    /**
     * @return array
     */
    public function getScopes (): array
    {
        return $this->scopes;
    }

    /**
     * @param array $scopes
     * @return SetScopes
     */
    public function setScopes (array $scopes): SetScopes
    {
        $this->scopes = $scopes;
        return $this;
    }
}