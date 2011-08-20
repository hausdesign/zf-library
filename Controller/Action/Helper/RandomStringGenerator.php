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
 * @package    HausDesign_Controller_Action_Helper
 * @copyright  Copyright (c) 2008 HausDesign (www.HausDesign.nl)
 * @license    http://www.HausDesign.nl/license/new-bsd     New BSD License
 * @version    $Id: Uri.php Tue Dec 25 21:53:29 EST 2007 21:53:29 forrest lyman $
 */

/**
 * Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * Helper for generating a random string
 * 
 * @category   HausDesign
 * @package    HausDesign_Controller_Action_Helper
 * @copyright  Copyright (c) 2008 HausDesign (www.HausDesign.nl)
 * @license    http://www.HausDesign.nl/license/new-bsd     New BSD License
 */
class HausDesign_Controller_Action_Helper_RandomStringGenerator extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Generate a random string
     *
     * @param integer $length
     * @return string
     */
    function generate($length, $use_lowercase = true, $use_uppercase = true, $use_numeric = true, $use_specials = false)
    {
           list($usec, $sec) = explode(' ', microtime());
           srand((float) $sec + ((float) $usec * 100000));

           $lowercase = 'abcdfghjkmnpqrstvwxyz';
           $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
           $numeric = '0123456789';
           $specials = '_!@#$%&*()-=+/';

           $validchars = '';
           if ($use_lowercase) $validchars .= $lowercase;
           if ($use_uppercase) $validchars .= $uppercase;
           if ($use_numeric) $validchars .= $numeric;
           if ($use_specials) $validchars .= $specials;

           $password  = '';
           $counter   = 0;

           while ($counter < $length) {
             $actChar = substr($validchars, rand(0, strlen($validchars)-1), 1);

             $password .= $actChar;
            $counter++;
           }

           return $password;
    }

    /**
     * Strategy pattern: call helper as broker method
     *
     * @param integer $length
     * @return string
     */
    public function direct($length, $use_lowercase = true, $use_uppercase = true, $use_numeric = true, $use_specials = false)
    {
        return $this->generate($length, $use_lowercase, $use_uppercase, $use_numeric, $use_specials);
    }
}