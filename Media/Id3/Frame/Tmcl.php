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
 * @package    HausDesign_Media
 * @subpackage ID3
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com) 
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Tmcl.php 177 2010-03-09 13:13:34Z svollbehr $
 * @since      ID3v2.4.0
 */

/**#@+ @ignore */
require_once 'HausDesign/Media/Id3/TextFrame.php';
/**#@-*/

/**
 * The <i>Musician credits list</i> is intended as a mapping between instruments
 * and the musician that played it. Every odd field is an instrument and every
 * even is an artist or a comma delimited list of artists.
 *
 * @todo       Implement better support
 * @category   Zend
 * @package    HausDesign_Media
 * @subpackage ID3
 * @author     Sven Vollbehr <sven@vollbehr.eu>
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com) 
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Tmcl.php 177 2010-03-09 13:13:34Z svollbehr $
 * @since      ID3v2.4.0
 */
final class HausDesign_Media_Id3_Frame_Tmcl extends HausDesign_Media_Id3_TextFrame
{}
