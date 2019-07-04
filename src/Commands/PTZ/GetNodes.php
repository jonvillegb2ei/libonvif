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

class GetNodes extends Command implements ICommand
{

    public function toString (): string
    {
        $return = '<GetNodes xmlns="http://www.onvif.org/ver20/ptz/wsdl" />';
        return $return;
    }

}