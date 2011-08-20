<?php
class HausDesign_Filter_SecToHms implements Zend_Filter_Interface
{
    public function filter($value)
    {
        // start with a blank string
        $hms = '';
        
        // do the hours first: there are 3600 seconds in an hour, so if we divide
        // the total number of seconds by 3600 and throw away the remainder, we're
        // left with the number of hours in those seconds
        $hours = intval(intval($value) / 3600); 
    
        // add hours to $hms (with a leading 0 if asked for)
        $hms .= str_pad($hours, 2, '0', STR_PAD_LEFT). ':';

        // dividing the total seconds by 60 will give us the number of minutes
        // in total, but we're interested in *minutes past the hour* and to get
        // this, we have to divide by 60 again and then use the remainder
        $minutes = intval(($value / 60) % 60); 
    
        // add minutes to $hms (with a leading 0 if needed)
        $hms .= str_pad($minutes, 2, '0', STR_PAD_LEFT). ':';
    
        // seconds past the minute are found by dividing the total number of seconds
        // by 60 and using the remainder
        $seconds = intval($value % 60); 
    
        // add seconds to $hms (with a leading 0 if needed)
        $hms .= str_pad($seconds, 2, '0', STR_PAD_LEFT);
    
        // done!
        return $hms;
    }
}