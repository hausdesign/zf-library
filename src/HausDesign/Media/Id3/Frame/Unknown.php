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
 * @version    $Id: Unknown.php 177 2010-03-09 13:13:34Z svollbehr $
 */

/**#@+ @ignore */
require_once 'HausDesign/Media/Id3/Frame.php';
/**#@-*/

/**
 * This class represents a frame not known to the library.
 *
 * @category   Zend
 * @package    HausDesign_Media
 * @subpackage ID3
 * @author     Sven Vollbehr <sven@vollbehr.eu>
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com) 
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Unknown.php 177 2010-03-09 13:13:34Z svollbehr $
 */
final class HausDesign_Media_Id3_Frame_Unknown extends HausDesign_Media_Id3_Frame
{
    /**
     * Writes the frame raw data without the header.
     *
     * @param HausDesign_Io_Writer $writer The writer object.
     * @return void
     */
    protected function _writeData($writer)
    {}
}