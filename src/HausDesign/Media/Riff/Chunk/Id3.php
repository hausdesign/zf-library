<?php
/**
 * @category   Zend
 * @package    HausDesign_Media
 * @subpackage Riff
 * @copyright  Copyright (c) 2011 Sven Vollbehr
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @version    $Id: Id3.php 258 2012-01-26 05:33:46Z svollbehr $
 */

/**#@+ @ignore */
require_once 'HausDesign/Media/Riff/Chunk.php';
require_once 'HausDesign/Media/Id3v2.php';
/**#@-*/

/**
 * The <i>ID3 Tag</i> chunk contains an {@link HausDesign_Media_Id3v2 ID3v2} tag.
 *
 * @category   Zend
 * @package    HausDesign_Media
 * @subpackage Riff
 * @author     Sven Vollbehr <sven@vollbehr.eu>
 * @copyright  Copyright (c) 2011 Sven Vollbehr
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @version    $Id: Id3.php 258 2012-01-26 05:33:46Z svollbehr $
 */
final class HausDesign_Media_Riff_Chunk_Id3 extends HausDesign_Media_Riff_Chunk
{
    /** @var HausDesign_Media_Id3v2 */
    private $_tag;

    /**
     * Constructs the class with given parameters and options.
     *
     * @param HausDesign_Io_Reader $reader  The reader object.
     */
    public function __construct($reader)
    {
        parent::__construct($reader);
        $this->_tag = new HausDesign_Media_Id3v2($this->_reader, array('readonly' => true));
    }

    /**
     * Returns the {@link HausDesign_Media_Id3v2 Id3v2} tag class instance.
     *
     * @return string
     */
    public function getTag()
    {
        return $this->_tag;
    }

    /**
     * Sets the {@link HausDesign_Media_Id3v2 Id3v2} tag class instance.
     *
     * @param HausDesign_Media_Id3v2 $tag The tag instance.
     */
    public function setTag($tag)
    {
        $this->_tag = $tag;
    }
}
