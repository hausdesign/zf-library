<?php
class HausDesign_View_Helper_FormTreeView extends Zend_View_Helper_FormElement
{
	protected $_info = null;

	protected function _renderBranch($nodes)
	{
		$output = '<ul id="' . $this->_info['id'] . '"'.$this->_htmlAttribs($this->_info['attribs']) . '>';
			$id = $this->_info['id'];

			foreach ($nodes as $node_id=>$node) {
				$node_control_id = $id . '-' . $node_id;
				$node_control_name = $this->_info['name'];

				if (is_array($this->_info['value'])) {
					$checked = in_array($node_id, $this->_info['value']) ? 'checked="checked"' : '';
				} else {
					$checked = '';
				}

				$output .= '<li>';
					$output .= '<input ' . $checked . ' value="' . $node_id . '" id="' . $node_control_id . '" name="' . $node_control_name . '" type="checkbox" />';
					$output .= '<label for="' . $node_control_id . '">' . $node['title'] . '</label>';
					if (!empty($node['children'])) {
						$output	.= $this->_renderBranch($node['children']);
					}
				$output .= '</li>';
			}
		$output .= '</ul>';

		return $output;
	}

	public function formTreeView ($name, $value = null, $attribs = null, $options = null)
	{
		$this->_info = $this->_getInfo($name, $value, $attribs, $options);
		return $this->_renderBranch($this->_info['options']);
	}
}