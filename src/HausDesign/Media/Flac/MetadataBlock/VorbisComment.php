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
 * @subpackage FLAC
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: VorbisComment.php 251 2011-06-13 15:41:51Z svollbehr $
 */

/**#@+ @ignore */
require_once 'HausDesign/Media/Flac/MetadataBlock.php';
require_once 'HausDesign/Media/Vorbis/Header/Comment.php';
/**#@-*/

/**
 * This class represents the vorbis comments metadata block. This block is for storing a list of human-readable
 * name/value pairs. This is the only officially supported tagging mechanism in FLAC. There may be only one
 * VORBIS_COMMENT block in a stream. In some external documentation, Vorbis comments are called FLAC tags to lessen
 * confusion.
 *
 * This class parses the vorbis comments using the {@link HausDesign_Media_Vorbis_Header_Comment} class. Any of its method
 * or fields can be used in the context of this class.
 *
 * @category   Zend
 * @package    HausDesign_Media
 * @subpackage FLAC
 * @author     Sven Vollbehr <sven@vollbehr.eu>
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: VorbisComment.php 251 2011-06-13 15:41:51Z svollbehr $
 */
final class HausDesign_Media_Flac_MetadataBlock_VorbisComment extends HausDesign_Media_Flac_MetadataBlock
{
    /** @var HausDesign_Media_Vorbis_Header_Comment */
    private $_impl;

    /**
     * Constructs the class with given parameters and parses object related data using the vorbis comment implementation
     * class {@link HausDesign_Media_Vorbis_Header_Comment}.
     *
     * @param HausDesign_Io_Reader $reader The reader object.
     */
    public function __construct($reader)
    {
        parent::__construct($reader);
        $this->_impl = new HausDesign_Media_Vorbis_Header_Comment($this->_reader, array('vorbisContext' => false));
    }

    /**
     * Forward all calls to the vorbis comment implementation class {@link HausDesign_Media_Vorbis_Header_Comment}.
     *
     * @param string $name The method name.
     * @param Array $arguments The method arguments.
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func(array($this, $name), $arguments);
        }
        try {
            return $this->_impl->$name($arguments);
        } catch (HausDesign_Media_Vorbis_Exception $e) {
            require_once 'HausDesign/Media/Flac/Exception.php';
            throw new HausDesign_Media_Flac_Exception($e->getMessage());
        }
    }

    /**
     * Forward all calls to the vorbis comment implementation class {@link HausDesign_Media_Vorbis_Header_Comment}.
     *
     * @param string $name The field name.
     * @return mixed
     */
    public function __get($name)
    {
        if (method_exists($this, 'get' . ucfirst($name))) {
            return call_user_func(array($this, 'get' . ucfirst($name)));
        }
        if (method_exists($this->_impl, 'get' . ucfirst($name))) {
            return call_user_func(array($this->_impl, 'get' . ucfirst($name)));
        }
        try {
            return $this->_impl->__get($name);
        } catch (HausDesign_Media_Vorbis_Exception $e) {
            require_once 'HausDesign/Media/Flac/Exception.php';
            throw new HausDesign_Media_Flac_Exception($e->getMessage());
        }
    }

    /**
     * Forward all calls to the vorbis comment implementation class {@link HausDesign_Media_Vorbis_Header_Comment}.
     *
     * @param string $name The field name.
     * @param string $name The field value.
     * @return mixed
     */
    public function __set($name, $value)
    {
        if (method_exists($this, 'set' . ucfirst($name))) {
            call_user_func(array($this, 'set' . ucfirst($name)), $value);
        } else {
            try {
                return $this->_impl->__set($name, $value);
            } catch (HausDesign_Media_Vorbis_Exception $e) {
                require_once 'HausDesign/Media/Flac/Exception.php';
                throw new HausDesign_Media_Flac_Exception($e->getMessage());
            }
        }
    }

    /**
     * Forward all calls to the vorbis comment implementation class {@link HausDesign_Media_Vorbis_Header_Comment}.
     *
     * @param string $name The field name.
     * @return boolean
     */
    public function __isset($name)
    {
        try {
            return $this->_impl->__isset($name);
        } catch (HausDesign_Media_Vorbis_Exception $e) {
            require_once 'HausDesign/Media/Flac/Exception.php';
            throw new HausDesign_Media_Flac_Exception($e->getMessage());
        }
    }

    /**
     * Forward all calls to the vorbis comment implementation class {@link HausDesign_Media_Vorbis_Header_Comment}.
     *
     * @param string $name The field name.
     */
    public function __unset($name)
    {
        try {
            $this->_impl->__unset($name);
        } catch (HausDesign_Media_Vorbis_Exception $e) {
            require_once 'HausDesign/Media/Flac/Exception.php';
            throw new HausDesign_Media_Flac_Exception($e->getMessage());
        }
    }
}
