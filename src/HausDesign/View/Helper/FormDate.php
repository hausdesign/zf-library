<?php
class HausDesign_View_Helper_FormDate extends Zend_View_Helper_FormText
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
    public function formDate($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable       

        $calendarFormat = 'yy-mm-dd 00:00:00';
        if (array_key_exists('calendar_format', $attribs)) {
            $calendarFormat = $attribs['calendar_format'];
            unset($attribs['calendar_format']);
        }

        $yearRange = '';
        if (array_key_exists('year_range', $attribs)) {
            $yearRange = $attribs['year_range'];
            unset($attribs['year_range']);
        }

        $minDate = '';
        if (array_key_exists('min_date', $attribs)) {
            $minDate = $attribs['min_date'];
            unset($attribs['min_date']);
        }

        $maxDate = '';
        if (array_key_exists('max_date', $attribs)) {
            $maxDate = $attribs['max_date'];
            unset($attribs['max_date']);
        }

        $locale = Zend_Registry::getInstance()->Zend_Locale;

        $this->view->headScript()->appendFile($this->view->baseUrl('/scripts/jquery-ui/locales/jquery.ui.datepicker-' . (string) $locale->getLanguage() . '.js', false));

        $this->view->headScript()->captureStart();
?>
$(document).ready(function() {
    $('#<?php echo $this->view->escape($id); ?>').datepicker({
        'dateFormat' : '<?php echo $calendarFormat; ?>',
<?php if ($yearRange != ''):?>
        'yearRange': '<?php echo $yearRange; ?>',
<?php endif; ?>
        'changeMonth': true,
        'changeYear' : true,
        'showAnim'   : 'fadeIn',
        'constrainInput' : true,
<?php if ($minDate != ''):?>
        'minDate': '<?php echo $minDate; ?>',
<?php endif; ?>
<?php if ($maxDate != ''):?>
        'maxDate': '<?php echo $maxDate; ?>',
<?php endif; ?>
        'autoSize'   : true,
        'showTime'   : true,
        'duration'   : ''
    });
});
<?php
        $this->view->headScript()->captureEnd();

        $xhtml = '';
        $xhtml .= $this->formText($name, $value, $attribs, $options);

        return $xhtml;
    }
}