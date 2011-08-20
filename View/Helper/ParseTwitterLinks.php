<?php
class HausDesign_View_Helper_ParseTwitterLinks extends Zend_View_Helper_Abstract
{
    public function parseTwitterLinks($value, $rel = null)
    {
        // Parse links (http(s)://.../)
        $value = preg_replace('/(https{0,1}:\/\/[\w\-\.\/#?&=]*)/', '<a href="$1"' . (($rel !== null) ? ' rel="' . $rel . '"' : '') . '>$1</a>', $value);

        // Parse replies (@...)
        $value = preg_replace('/@(\w+)/', '@<a href="http://twitter.com/$1" class="at" rel="external">$1</a>', $value);

        // Parse hash tags (#...)
        $value = preg_replace('/\s#(\w+)/', ' <a href="http://twitter.com/#!/search?q=%23$1" class="hashtag" rel="external">#$1</a>', $value);  

        return $value;
    }
}