<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: HtmlTag.php 20096 2010-01-06 02:05:09Z bkarwin $
 */

/**
 * @see Zend_View_Helper_HtmlElement
 */
require_once 'Zend/View/Helper/HtmlElement.php';

/**
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class HausDesign_View_Helper_HtmlTag extends Zend_View_Helper_HtmlElement
{
    CONST TAG_OPEN = 'open';
    CONST TAG_CLOSE = 'close';
    CONST TAG_BOTH = 'both';
    CONST TAG_ONLY = 'only';

    /**
     * Output a (X)HTML tag
     * 
     * @param string $tag The tag name 
     * @param array  $attribs Attribs for the tag tag
     * @param string $content Alternative content for tag
     * @param string $type
     * 
     * @return string
     */
    public function htmlTag($tag, array $attribs = array(), $content = null, $type = self::TAG_BOTH)
    {
        $closingBracket = $this->getClosingBracket();

        // Content
        if (is_array($content)) {
            $content = implode(self::EOL, $content);
        }

        $xhtml = '';

        // Tag header
        switch ($type) {
            case self::TAG_OPEN:
                $xhtml .= '<' . $tag . '' . $this->_htmlAttribs($attribs) . '>';
                $xhtml .= $content;
                break;

            case self::TAG_CLOSE:
                $xhtml .= '</' . $tag . $closingBracket;
                break;

            case self::TAG_BOTH:
                $xhtml .= '<' . $tag . '' . $this->_htmlAttribs($attribs) . '>';
                $xhtml .= $content;
                $xhtml .= '</' . $tag . $closingBracket;
                break;

            case self::TAG_ONLY:
                $xhtml .= '<' . $tag . '' . $this->_htmlAttribs($attribs) . $closingBracket;
                break;
        }

        return $xhtml;
    }
}
