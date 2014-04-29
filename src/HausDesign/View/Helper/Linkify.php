<?php
class HausDesign_View_Helper_Linkify extends Zend_View_Helper_Abstract
{
    /**
     * Turn all URLs in clickable links.
     *
     * @param string $value
     * @param array $protocols http/https, ftp, mail, twitter
     * @param array $attributes
     * @param string $mode normal or all
     * 
     * @return string
     */
    public function linkify($value, $protocols = array('http', 'mail'), array $attributes = array(), $mode = 'normal')
    {
        // Link attributes
        $attr = '';
        foreach ($attributes as $key => $val) {
            $attr = ' ' . $key . '="' . htmlentities($val) . '"';
        }

        $links = array();

        // Extract existing links and tags
        $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) { return '<' . array_push($links, $match[1]) . '>'; }, $value);

        // Extract text links for each protocol
        foreach ((array)$protocols as $protocol) {
            switch ($protocol) {
                case 'http':
                case 'https':
                    $value = preg_replace_callback($mode != 'all' ? '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i' : '~([^\s<]+\.[^\s<]+)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { if ($match[1]) $protocol = $match[1]; $link = $match[2] ?: $match[3]; return '<' . array_push($links, '<a' . $attr . ' href="' . $protocol . '://' . $link . '">' . $link . '</a>') . '>'; }, $value);
                    break;

                case 'mail':
                    $value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attr) { return '<' . array_push($links, '<a' . $attr . ' href="mailto:' . $match[1] . '">' . $match[1] . '</a>') . '>'; }, $value);
                    break;

                case 'twitter':
                    $value = preg_replace_callback('~(?<!\w)[@#](\w++)~', function ($match) use (&$links, $attr) { return '<' . array_push($links, '<a' . $attr . ' href="https://twitter.com/' . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1] . '">' . $match[0] . '</a>') . '>'; }, $value);
                    break;

                default:
                    $value = preg_replace_callback($mode != 'all' ? '~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i' : '~([^\s<]+)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { return '<' . array_push($links, '<a' . $attr . ' href="' . $protocol . '://' . $match[1] . '">' . $match[1] . '</a>') . '>'; }, $value);
                    break;
            }
        }

        // Insert all link
        return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) { return $links[$match[1] - 1]; }, $value);
    }
}