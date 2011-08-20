<?php
class HausDesign_Controller_Action_Crud extends HausDesign_Controller_Action
{
    /**
     * Model
     * 
     * @var Zend_Db_Table
     */
    protected $_model;

    /**
     * Item
     * 
     * @var Zend_Db_Table_Row
     */
    protected $_item;

    /**
     * Form
     * 
     * @var HausDesign_Form
     */
    protected $_form;
    
    protected $_redirectUrlCancel = '';
    protected $_redirectUrlAdd = '';
    protected $_redirectUrlEdit = '';
    protected $_redirectUrlDelete = '';

    /**
     * Initialize
     */
    public function init()
    {
        parent::init();

    	// Load the request object
    	$request = $this->getRequest();

        // set the meta title
        $this->view->headTitle(ucfirst($this->view->translate('title' . $this->_moduleName)), Zend_View_Helper_Placeholder_Container_Abstract::PREPEND);
        $this->view->headTitle(ucfirst($this->view->translate('title' . $this->_translationName)), Zend_View_Helper_Placeholder_Container_Abstract::PREPEND);

        // Load the log
        $this->_log = $this->getInvokeArg('bootstrap')->getResource('Log');

    	// Load the item
    	$this->_loadItem();

    	// Parse the item to the view
        $this->view->item = $this->_item;

        // Set the redirect url's
        $this->_redirectUrlCancel = '/' . $request->getModuleName() . '/item/';
        $this->_redirectUrlAdd = '/' . $request->getModuleName() . '/item/';
        $this->_redirectUrlEdit = '/' . $request->getModuleName() . '/item/';
        $this->_redirectUrlDelete = '/' . $request->getModuleName() . '/item/';
    }

    protected function _loadItem()
    {
        // Load the item
        $this->_item = null;
        $itemId = $this->getRequest()->getParam('id');
        if ($itemId !== null) {
            $this->_item = $this->_model->find($itemId)->current();
        }
    }

    /**
     * Add action
     */
    public function addAction()
    {
        // Get the request object
        $request = $this->getRequest();

        $populateWithDefaultValues = true;

        // Check if a form is sent
        if ($request->isPost()) {
            // Get the post data
            $postData = $request->getPost();
	        $postData = $this->_parsePostData($postData);

            if ((isset($postData['form_class'])) && ($postData['form_class'] == get_class($this->_form))) {
            	$populateWithDefaultValues = false;

            	// Populate the post data to the form
	            $this->_form->populate($postData);

	            if (isset($this->_form->cancel) && $this->_form->cancel->isChecked()) {
	                // Cancel button is pressed
	                $this->_redirect($this->_redirectUrlCancel);
	            } elseif ($this->validateForm($postData)) {
	                // Form is sent
	                try {
	                    // Parse the form data
	                    $item = $this->_getItem('add');

                        // Actions before the query
                        $this->_beforeQuery('edit');

	                    if (array_key_exists('form_class', $item)) {
                            unset($item['form_class']);
	                    }

	                    if (array_key_exists('form_name', $item)) {
                            unset($item['form_name']);
	                    }

	                    // Insert the item
	                    $item = $this->_model->insert($item);

	                    if (! is_array($item)) {
	                    	$item = array($item);
	                    }

                        // Find the added item
	    				switch(count($item)) {
	        				case 0:  break;
	        				case 1: $this->_item = $this->_model->find($item[0])->current(); break;
	        				case 2: $this->_item = $this->_model->find($item[0], $item[1])->current(); break;
	        				case 3: $this->_item = $this->_model->find($item[0], $item[1], $item[2])->current(); break;
	        				case 4: $this->_item = $this->_model->find($item[0], $item[1], $item[2], $item[3])->current(); break;
	        				case 5: $this->_item = $this->_model->find($item[0], $item[1], $item[2], $item[3], $item[4])->current(); break;
	        				case 6: $this->_item = $this->_model->find($item[0], $item[1], $item[2], $item[3], $item[4], $item[5])->current(); break;
	        				case 7: $this->_item = $this->_model->find($item[0], $item[1], $item[2], $item[3], $item[4], $item[5], $item[6])->current(); break;
	    				}

	                    // Actions after the query
	                    $this->_afterQuery('add');

	                    // Set the form description
	                    $this->_form->setDescription($this->view->translate('textSavedItemSuccesfull'));

                        // Add the succesfull message to the flashmessenger
	                    $this->_flashMessenger->addMessage($this->view->translate('textSavedItemSuccesfull'));

	                    // Redirect
                        if (($this->_redirectUrlAdd !== null) && ($this->_redirectUrlAdd != '')) {
                            $this->_redirect($this->_redirectUrlAdd);
                        }
	                }  catch (Exception $exception) {
	                    $this->_form->addErrorMessage($exception->getMessage());
	                }
	            }
            }
        }

        if ($populateWithDefaultValues) {
            // Initialize the form with default values
            $this->_form->populate($this->_getDefaultFormValues());
        }

        // Set the class "error" to subforms with errors
        $this->_form->setSubFormErrorClass('error');

        // Parse the form to the view
        $this->view->form = $this->_form;
    }

    /**
     * Edit action
     */
    public function editAction()
    {
        // Get the request object
        $request = $this->getRequest();

        $populateWithDefaultValues = true;

        // Check if a form is sent
        if ($request->isPost()) {
            $postData = $request->getPost();
            $postData = $this->_parsePostData($postData);

            if ((isset($postData['form_class'])) && ($postData['form_class'] == get_class($this->_form))) {
           		$populateWithDefaultValues = false;

            	//Populate the post data to the form
	            $this->_form->populate($postData);

	            if ($this->_form->cancel->isChecked()) {
	                // Cancel button is pressed
	                $this->_redirect($this->_redirectUrlCancel);
	            } elseif (($this->_form->delete !== null) && ($this->_form->delete->isChecked())) {
	                // Delete button is pressed
	                $this->_redirect($this->_redirectUrlDelete);
	            } elseif ($this->validateForm($postData)) {
	                // Form is sent
	                try {
	                    // Parse the form data
	                    $item = $this->_getItem('edit');

                        // Actions before the query
                        $this->_beforeQuery('edit');

	                    if (array_key_exists('form_class', $item)) {
                            unset($item['form_class']);
	                    }

	                    if (array_key_exists('form_name', $item)) {
                            unset($item['form_name']);
	                    }

	                    // Save the item
	                    $this->_item->setFromArray($item);
						
	                    $this->_item->save();

	                    // Actions after the query
	                    $this->_afterQuery('edit');

	                    // Set the form description
	                    $this->_form->setDescription($this->view->translate('textSavedItemSuccesfull'));

                        // Add the succesfull message to the flashmessenger
	                    $this->_flashMessenger->addMessage($this->view->translate('textSavedItemSuccesfull'));

	                    // Redirect
                        if (($this->_redirectUrlEdit !== null) && ($this->_redirectUrlEdit != '')) {
                            $this->_redirect($this->_redirectUrlEdit);
                        }
	                } catch (Exception $exception) {
	                    $this->_form->addErrorMessage($exception->getMessage());
	                }
	            }
            }
        }

        if ($populateWithDefaultValues) {
            // Initialize the form with the item values
            $this->_form->populate($this->_getDefaultFormValues());
        }

        // Set the class "error" to subforms with errors
        $this->_form->setSubFormErrorClass('error');

        // Parse the form to the view
        $this->view->form = $this->_form;
    }

    /**
     * Delete action
     */
    public function deleteAction()
    {
        // Get the request object
        $request = $this->getRequest();

        $return = array();

        if ($request->isXmlHttpRequest()) {
            // Disable the layout and view
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
        }

        try {
            // Check if the delete action is confirmed 
            $confirm = intval($request->getParam('confirm', 0));

            if ($confirm === 1) {
                // Actions before the query
                $this->_beforeQuery('delete');

                // Delete the item
                $this->_item->delete();

                // Actions after the query
                $this->_afterQuery('delete');

                $return['result'] = true;

                if (! $request->isXmlHttpRequest()) {
                    // Redirect to the index
                    if (($this->_redirectUrlDelete !== null) && ($this->_redirectUrlDelete != '')) {
                        $this->_redirect($this->_redirectUrlDelete);
                    }
                }
            } else {
                if ($request->isXmlHttpRequest()) {
                    throw new Zend_Controller_Action_Exception('the action is not confirmed');
                }
            }
        } catch (Exception $exception) {
            $this->view->error = $exception->getMessage();

            $return['result'] = false;
            $return['error'] = $exception->getMessage();
        }

        if ($request->isXmlHttpRequest()) {
            $this->_helper->json($return);
        } 
    }

    /**
     * 
     * @param array $postData
     * 
     * @return array
     */
    protected function _parsePostData($postData)
    {
    	return $postData;
    }

    /**
     * 
     * Enter description here ...
     */
    protected function validateForm($postData)
    {
    	 return $this->_form->isValid($postData);
    }

    /**
     * Get the item to insert into the database
     * 
     * @param string $type
     * 
     * @return array
     */
    public function _getItem($type = '', $flatten = true)
    {
        $formValues = $this->_form->getValues();

        $return = $formValues;

        if ($flatten) {
            $return = array();
            foreach(new RecursiveIteratorIterator(new RecursiveArrayIterator($formValues)) as $k => $v){
                $return[$k] = $this->_checkNull($v);
            }
        }

        return $return;
    }

    /**
     * Executed before the save/edit/delete database operation
     * 
     * @param string $type
     */
    protected function _beforeQuery($type = '')
    {
        
    }

    /**
     * Executed after the save/edit/delete database operation
     * 
     * @param string $type
     */
    protected function _afterQuery($type = '')
    {
        
    }

    /**
     * Get the default form values
     */
    protected function _getDefaultFormValues()
    {
        $return = array();

        if ($this->_item !== null) {
            $return = $this->_item->toArray();
        }

        return $return;
    }

    /**
     * 
     */
    protected function _checkNull($value)
    {
    	if ($value == '') {
    		return null;
    	} else {
    		return $value;
    	}
    }
}