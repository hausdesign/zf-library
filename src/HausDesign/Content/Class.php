<?php
/**
 * HausDesign
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.HausDesign.nl/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@HausDesign.nl so we can send you a copy immediately.
 *
 * @category   HausDesign
 * @package    HausDesign_Content
 * @copyright  Copyright (c) 2009 HausDesign (www.HausDesign.nl)
 * @license    http://www.HausDesign.nl/license/new-bsd     New BSD License
 * @version    $Id: Application.php 229 2009-07-21 22:33:00Z koen $
 */

/**
 * Implements a Content Class of the HausDesign CMS.
 *
 * This object implements a database content class. It allows for overriding to implement other
 * types of database content classes. Such as a versioned database design.
 *
 * @category   HausDesign
 * @package    HausDesign_Content
 * @copyright  Copyright (c) 2009 HausDesign (www.HausDesign.nl)
 * @license    http://www.HausDesign.nl/license/new-bsd     New BSD License
 */
abstract class HausDesign_Content_Class extends Zend_Db_Table_Abstract
{
}