<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 17:03
 */
// TODO: not implemented
namespace LibOnvif\Commands;


use Carbon\Carbon;
use LibOnvif\Contracts\ICommand;

class SetSystemDateAndTime extends Command implements ICommand
{

    private $date;

    public function __construct (Carbon $date = null)
    {
        $this->date = $date ? $date : Carbon::now();
    }

    public function toString (): string
    {
        $return = '';
        return $return;
    }

}