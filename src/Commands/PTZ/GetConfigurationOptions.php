<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 17:03
 */

namespace LibOnvif\Commands\PTZ;


use LibOnvif\Commands\Command;
use LibOnvif\Contracts\ICommand;

class GetConfigurationOptions extends Command implements ICommand
{

    public function toString (): string
    {
        $return = '<GetConfigurationOptions xmlns="http://www.onvif.org/ver20/ptz/wsdl">';
        if (!is_null($this->profileToken))
            $return .= sprintf('<ConfigurationToken>%s</ConfigurationToken>', $this->getConfigurationToken());
        $return .= '</GetConfigurationOptions>';
        return $return;
    }

}