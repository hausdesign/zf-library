<?php
class HausDesign_View_Helper_Makeclickable extends Zend_View_Helper_Abstract
{
    public function makeclickable($value, $link = null)
    {
        if ($link !== null) {
            $value = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t<]*)#ise", "'\\1<a href=\"\\2\" rel=\"external\">" . $link . "</a>'", $value);
            $value = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://\\2\" rel=\"external\">" . $link . "</a>'", $value);
            $value = preg_replace("#(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\" rel=\"external\">\\2@\\3</a>", $value);
        } else {
            $value = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t<]*)#ise", "'\\1<a href=\"\\2\" rel=\"external\">\\2</a>'", $value);
            $value = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://\\2\" rel=\"external\">\\2</a>'", $value);
            $value = preg_replace("#(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\" rel=\"external\">\\2@\\3</a>", $value);
        }

        return $value;
    }
}