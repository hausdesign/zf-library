<?php
class HausDesign_Controller_Plugin_Session extends Zend_Controller_Plugin_Abstract
{
	/**
	* PreDispatch Hook.
	*
	* Checks to see if the current request has been by the current session
	* if so restart up the php session and continue on
	*
	* @param Zend_Controller_Request_Abstract $request
	* @return void
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