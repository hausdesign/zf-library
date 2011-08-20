<?php
class HausDesign_View_Helper_ParseTwitterDate extends Zend_View_Helper_Abstract
{
    public function parseTwitterDate($a)
    {
        //get current timestampt
        $b = strtotime('now');

        //get timestamp when tweet created
        $c = strtotime($a);

        //get difference
        $d = $b - $c;

        $dDay = intval(date('d', $c));
        $dHour = intval(date('H', $c));
        $dMinute = intval(date('i', $c));
        $dSecond = intval(date('s', $c));

        //calculate different time values
        $minute = 60;
        $hour = $minute * 60;
        $day = $hour * 24;
        $week = $day * 7;

        if (is_numeric($d) && $d > 0) {
            //if less then 3 seconds
            if($d < 3) return 'right now';

            //if less then minute
            if($d < $minute) return floor($d) . ' seconds ago';

            //if less then 2 minutes
            if($d < $minute * 2) return 'about 1 minute ago';

            //if less then hour
            if($d < $hour) return floor($d / $minute) . ' minutes ago';

            //if less then 2 hours
            if($d < $hour * 2) return 'about 1 hour ago';

            //if less then day
            if($d < $day) return floor($d / $hour) . ' hours ago';

            //if more then day, but less then 2 days
            if($d > $day && $d < $day * 2) return 'yesterday';

            //if less then year
            if($d < $day * 365) return floor($d / $day) . ' days ago';

            //else return more than a year
            return 'over a year ago';
        }
    }
}