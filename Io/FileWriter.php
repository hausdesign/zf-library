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
 * @package    HausDesign_Io
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: FileWriter.php 177 2010-03-09 13:13:34Z svollbehr $
 */

/**#@+ @ignore */
require_once 'HausDesign/Io/Writer.php';
/**#@-*/

/**
 * The HausDesign_Io_FileWriter represents a character stream whose source is
 * a file.
 *
 * @category   Zend
 * @package    HausDesign_Io
 * @author     Sven Vollbehr <sven@vollbehr.eu>
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: FileWriter.php 177 2010-03-09 13:13:34Z svollbehr $
 */
class HausDesign_Io_FileWriter extends HausDesign_Io_Writer
{
    /**
     * Constructs the HausDesign_Io_FileWriter class with given path to the file. By
     * default the file is opened in write mode without altering its content
     * (ie r+b mode if the file exists, and wb mode if not).
     *
     * @param string $filename The path to the file.
     * @throws HausDesign_Io_Exception if the file cannot be written
     */
    public function __construct($filename, $mode = null)
    {
        if ($mode === null)
            $mode = file_exists($filename) ? 'r+b' : 'wb';
        if (($fd = fopen($filename, $mode)) === false) {
            require_once('HausDesign/Io/Exception.php');
            throw new HausDesign_Io_Exception
                ('Unable to open file for writing: ' . $filename);
        }
        parent::__construct($fd);
    }

    /**
     * Closes the file descriptor.
     */
    public function __destruct()
    {
        $this->close();
    }
}
