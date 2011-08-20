<?php
class HausDesign_Controller_Plugin_Uploadify extends Zend_Controller_Plugin_Abstract
{
	/**
	* PreDispatch Hook.
	*
	* Checks to see if the current request has been made by the uploadify.swf file
	* if so restart up the php session and continue on
	*
	* @param Zend_Controller_Request_Abstract $request
	* @return> void
	*/
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$phpSessId = $request->getParam('PHPSESSID');

		if (! empty($phpSessId) && session_id() != $phpSessId) {
			session_destroy();
			session_id($phpSessId);
			session_start();
		}
	}
}