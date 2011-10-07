<?php
class HausDesign_View_Helper_FormDateTime extends Zend_View_Helper_FormText
{
    /**
     * Generates a 'calendar' element.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param mixed $value The element value.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formDateTime($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable       

        if (array_key_exists('date_format', $attribs)) {
            $dateFormat = $attribs['date_format'];
            unset($attribs['date_format']);
        } else {
            $dateFormat = 'yy-mm-dd';
        }

        if (array_key_exists('time_format', $attribs)) {
            $timeFormat = $attribs['time_format'];
            unset($attribs['time_format']);
        } else {
            $timeFormat = 'hh:ii';
        }

        $locale = Zend_Registry::getInstance()->Zend_Locale;

        $this->view->headScript()->appendFile($this->view->baseUrl('/scripts/jquery-ui-plugins/timepicker/jquery-ui-timepicker-addon.js', false));
        $this->view->headLink()->appendStylesheet($this->view->baseUrl('/scripts/jquery-ui-plugins/timepicker/jquery-ui-timepicker-addon.css', false), 'screen');

        $this->view->headScript()->captureStart();
?>
$(document).ready(function() {
    $('#<?php echo $this->view->escape($id); ?>').datetimepicker({
        dateFormat: '<?php echo $dateFormat; ?>',
        timeFormat: '<?php echo $timeFormat; ?>'
    });
});
<?php
        $this->view->headScript()->captureEnd();

        $xhtml = '';
        $xhtml .= $this->formText($name, $value, $attribs, $options);

        return $xhtml;
    }
}