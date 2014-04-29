<?php
class HausDesign_View_Helper_FormNestedmultiselect extends Zend_View_Helper_FormElement
{
    protected $_info = null;

    public function formNestedmultiselect($name, $value = null, $attribs = null, $options = null)
    {
        $this->_info = $this->_getInfo($name, $value, $attribs, $options);
        $output = '';
        $output .= '<div class="nested-multicheckbox">';
        $output .= '<div class="nested-multicheckbox-search"><input type="text" /></div>';
        $output .= $this->_renderBranch($this->_info['options']);
        $output .= '</div>';
        return $output;
    }

    /**
     * Render the branch
     *
     * @param array $nodes
     * @param int $level
     * @return string
     */
    protected function _renderBranch($nodes, $level = 0)
    {
        $id = $this->_info['id'];
        $attribs = $this->_info['attribs'];

        $output = '<ul ' . $this->_htmlAttribs($attribs) . '>';

        foreach ($nodes as $node_id => $node) {
            $node_control_id = $id . '-' . $node_id;
            $node_control_name = $this->_info['name'];

            if (is_array($this->_info['value'])) {
                $checked = in_array($node_id, $this->_info['value']) ? 'checked="checked"' : '';
            } else {
                $checked = '';
            }

            $output .= '<li>';
            //$output .= '<label for="' . $node_control_id . '">';
            $output .= '<label>';
            $output .= '<input ' . $checked . ' value="' . $node_id . '" id="' . $node_control_id . '" name="' . $node_control_name . '" type="checkbox" />';
            $output .= ' ' . (is_array($node) ? $node['title'] : $node) . '';
            $output .= '</label>';
            if (is_array($node) && !empty($node['children'])) {
                $level = $level + 1;
                $output .= $this->_renderBranch($node['children'], $level);
            }
            $output .= '</li>';
        }
        $output .= '</ul>';

        return $output;
    }
}