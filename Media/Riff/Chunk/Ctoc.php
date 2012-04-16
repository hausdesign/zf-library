<?php
/**
 * @category   Zend
 * @package    HausDesign_Media
 * @subpackage Riff
 * @copyright  Copyright (c) 2011 Sven Vollbehr
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @version    $Id: Ctoc.php 257 2012-01-26 05:30:58Z svollbehr $
 */

/**#@+ @ignore */
require_once 'HausDesign/Media/Riff/Chunk.php';
/**#@-*/

/**
 * The <i>Compound File Table of Contents</i> chunk functions mainly as an index, allowing direct access to elements
 * within a compound file. The CTOC chunk also contains information about the attributes of the entire file and of each
 * media element within the file.
 *
 * To provide the maximum flexibility for defining compound file formats, the CTOC chunk can be customized at several
 * levels. The CTOC chunk contains fields whose length and usage is defined by other CTOC fields. This parameterization
 * adds complexity, but it provides flexibility to file format designers and allows applications to correctly read data
 * without necessarily knowing the specific file format definition.
 *
 * @category   Zend
 * @package    HausDesign_Media
 * @subpackage Riff
 * @author     Sven Vollbehr <sven@vollbehr.eu>
 * @copyright  Copyright (c) 2011 Sven Vollbehr
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @version    $Id: Ctoc.php 257 2012-01-26 05:30:58Z svollbehr $
 * @todo       Implementation
 */
final class HausDesign_Media_Riff_Chunk_Ctoc extends HausDesign_Media_Riff_Chunk
{
    /**
     * Constructs the class with given parameters and options.
     *
     * @param HausDesign_Io_Reader $reader  The reader object.
     */
    public function __construct($reader)
    {
        parent::__construct($reader);
        require_once('HausDesign/Media/Riff/Exception.php');
        throw new HausDesign_Media_Riff_Exception('Not yet implemented');
    }
}
