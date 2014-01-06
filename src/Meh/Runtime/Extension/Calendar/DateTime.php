<?php
namespace Meh\Runtime\Extension\Calendar;

class DateTime implements DateTimeInterface
{
    const ATOM = 'Y-m-d\TH:i:sP';
    const COOKIE = 'l, d-M-y H:i:s T';
    const ISO8601 = 'Y-m-d\TH:i:sO';
    const RFC822 = 'D, d M y H:i:s O';
    const RFC850 = 'l, d-M-y H:i:s T';
    const RFC1036 = 'D, d M y H:i:s O';
    const RFC1123 = 'D, d M Y H:i:s O';
    const RFC2822 = 'D, d M Y H:i:s O';
    const RFC3339 = 'Y-m-d\TH:i:sP';
    const RSS = 'D, d M Y H:i:s O';
    const W3C = 'Y-m-d\TH:i:sP';

    private $timestamp;
    private $microseconds;
    private $pieces;

    public function __construct($time = 'now', DateTimeZone $timezone = null)
    {
        
    }

    public function __getPieces()
    {
        if ($this->pieces === null) {
            $this->pieces = [];
            // TODO
        }
        return $this->pieces;
    }
}
