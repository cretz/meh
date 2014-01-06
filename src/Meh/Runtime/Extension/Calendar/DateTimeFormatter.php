<?php
namespace Meh\Runtime\Extension\Calendar;

class DateTimeFormatter
{
    public function dayOfMonth(DateTime $date, $pad)
    {
    }

    public function dayOfMonthSuffix(DateTime $date)
    {
    }

    public function dayName(DateTime $date, $full)
    {
    }

    public function dayOfWeek(DateTime $date, $zeroBased)
    {
    }

    public function dayOfYear(DateTime $date)
    {
    }

    public function format(DateTime $date, $format)
    {
        $ret = '';
        $pendingEscape = false;
        for ($i = 0; $i < strlen($format); $i++) {
            if ($pendingEscape) {
                $pendingEscape = false;
                $ret .= $format[$i];
            } elseif ($format[$i] === '\\') {
                $pendingEscape = true;
            } else {
                $method = $this->methodAndParamsFromCharacter($format[$i]);
                if ($method === null) $ret .= $format[$i];
                else {
                    $callable = [$this, $method[0]];
                    $method[0] = $date;
                    $ret .= call_user_func_array($callable, $method);
                }
            }
        }
        return $ret;
    }

    public function methodAndParamsFromCharacter($char)
    {
        switch ($char) {
            // Day
            case 'd': return ['dayOfMonth', true];
            case 'D': return ['dayName', false];
            case 'j': return ['dayOfMonth', false];
            case 'l': return ['dayName', true];
            case 'N': return ['dayOfWeek', false];
            case 'S': return ['dayOfMonthSuffix'];
            case 'w': return ['dayOfWeek', false];
            case 'z': return ['dayOfYear'];
            // Week
            case 'W': return ['weekOfYear'];
            // Month
            case 'F': return ['monthName', true];
            case 'm': return ['monthOfYear', true];
            case 'M': return ['monthName', false];
            case 'n': return ['monthOfYear', false];
            case 't': return ['monthDayCount'];
            // Year
            case 'L': return ['yearIsLeap'];
            case 'o': return ['yearNumber', true, true];
            case 'Y': return ['yearNumber', false, true];
            case 'y': return ['yearNumber', false, false];
            // Time
            case 'a': return ['timeMeridiem', true];
            case 'A': return ['timeMeridiem', false];
            case 'B': return ['timeSwatch'];
            case 'g': return ['timeHour', false, false];
            case 'G': return ['timeHour', true, false];
            case 'h': return ['timeHour', false, true];
            case 'H': return ['timeHour', true, true];
            case 'i': return ['timeMinutes', true];
            case 's': return ['timeSeconds', true];
            case 'u': return ['timeMicroseconds', true];
            // Timezone
            case 'e': return ['timezoneIdentifier'];
            case 'I': return ['timezoneIsDst'];
            case 'O': return ['timezoneGmtDiff', false];
            case 'P': return ['timezoneGmtDiff', true];
            case 'T': return ['timezoneAbbreviation'];
            case 'Z': return ['timezoneOffsetSeconds'];
            // Full Date/Time
            case 'c': return ['format', DateTime::ISO8601];
            case 'r': return ['format', DateTime::RFC2822];
            case 'U': return ['unixTimestamp'];
            default: return null;
        }
    }

    public function monthDayCount(DateTime $date)
    {
    }

    public function monthName(DateTime $date, $full)
    {
    }

    public function monthOfYear(DateTime $date, $pad)
    {
    }

    public function timeHour(DateTime $date, $fullDay, $pad)
    {
    }

    public function timeMeridiem(DateTime $date, $lowercase)
    {
    }

    public function timeMicroseconds(DateTime $date, $pad)
    {
    }

    public function timeMinutes(DateTime $date, $pad)
    {
    }

    public function timeSeconds(DateTime $date, $pad)
    {
    }

    public function timeSwatch(DateTime $date)
    {
    }

    public function timezoneAbbreviation(DateTime $date)
    {
    }

    public function timezoneGmtDiff(DateTime $date, $withColon)
    {
    }

    public function timezoneIdentifier(DateTime $date)
    {
    }

    public function timezoneIsDst(DateTime $date)
    {
    }

    public function timezoneOffsetSeconds(DateTime $date)
    {
    }

    public function unixTimestamp(DateTime $date)
    {
    }

    public function weekOfYear(DateTime $date)
    {
    }

    public function yearIsLeap(DateTime $date)
    {
    }

    public function yearNumber(DateTime $date, $weekYear, $full)
    {
    }
}
