<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 17:03
 */

namespace LibOnvif\Commands;


use LibOnvif\Contracts\ICommand;

class SystemReboot extends Command implements ICommand
{

    public function toString (): string
    {
        return '<SystemReboot xmlns="http://www.onvif.org/ver10/device/wsdl"/>';
    }

}