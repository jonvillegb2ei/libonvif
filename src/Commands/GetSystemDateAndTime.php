<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 17:03
 */

namespace LibOnvif\Commands;


use Carbon\Carbon;
use DOMDocument;
use LibOnvif\Contracts\ICommand;

class GetSystemDateAndTime extends Command implements ICommand
{

    public function toString (): string
    {
        return '<GetSystemDateAndTime xmlns="http://www.onvif.org/ver10/device/wsdl"/>';
    }

    private function parseDates(DOMDocument $domDocument)
    {
        $datesNodes = ['UTCDateTime', 'LocalDateTime'];
        $datePartsNodes = ['Year', 'Month', 'Day', 'Hour', 'Minute', 'Second'];
        $dates = [];
        foreach ($datesNodes as $datesNodeName) {
            $dateTimeNodes = $domDocument->getElementsByTagName($datesNodeName);
            if ($dateTimeNodes->count() > 0) {
                $dates[$datesNodeName] = [];
                foreach ($datePartsNodes as $datePartsNodeName) {
                    $nodes = $dateTimeNodes[0]->getElementsByTagName($datePartsNodeName);
                    $dates[$datesNodeName][strtolower($datePartsNodeName)] = $nodes[0]->textContent;
                }
                $dates[$datesNodeName]['date'] = Carbon::create($dates[$datesNodeName]['year'], $dates[$datesNodeName]['month'], $dates[$datesNodeName]['day'], $dates[$datesNodeName]['hour'], $dates[$datesNodeName]['minute'], $dates[$datesNodeName]['second']);
            }
        }
        $dates['UTCDateTime']['date']->timezone('UTC');
        return $dates;
    }

    private function parseTimezone(DOMDocument $domDocument)
    {
        $timeZones = $domDocument->getElementsByTagName('TimeZone');
        $tz = [];
        if ($timeZones->count() > 0) {
            foreach ($timeZones[0]->getElementsByTagName('TZ') as $node) {
                $tz[] = $node->textContent;
            }
        }
        return $tz;
    }

    private function parseDateTimeType(DOMDocument $domDocument)
    {
        $dateTimeType = $domDocument->getElementsByTagName('DateTimeType');
        if ($dateTimeType->count() > 0) return $dateTimeType[0]->textContent;
        else return null;
    }

    private function parseDaylightSavings(DOMDocument $domDocument)
    {
        $daylightSavings = $domDocument->getElementsByTagName('DaylightSavings');
        if ($daylightSavings->count() > 0) return strtolower($daylightSavings[0]->textContent) == 'true';
        else return null;
    }


    public function parse (string $response)
    {
        $domDocument = new DOMDocument();
        $domDocument->loadXML($response);

        $dates = $this->parseDates($domDocument);
        $tz = $this->parseTimezone($domDocument);
        $dateTimeType = $this->parseDateTimeType($domDocument);
        $daylightSavings = $this->parseDaylightSavings($domDocument);

        if (array_key_exists('LocalDateTime', $dates) and count($tz) > 0) {
            $dates['LocalDateTime']['date']->timezone($tz[0]);
        }

        return [
            'dates' => $dates,
            'dateTimeType' => $dateTimeType,
            'daylightSavings' => $daylightSavings,
            'timeZones' => $tz
        ];
    }

}