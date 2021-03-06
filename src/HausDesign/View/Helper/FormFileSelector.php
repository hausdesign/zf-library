<?php
class HausDesign_View_Helper_FormFileSelector extends Zend_View_Helper_FormText
{
    public function formFileSelector($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        if (isset($attribs['baseUrl'])) {
            $_SESSION['CKFINDER_BASEURL'] = $attribs['baseUrl'];
            unset($attribs['baseUrl']);
        } else {
            unset($_SESSION['CKFINDER_BASEURL']);
        }

        if (isset($attribs['baseDir'])) {
            $_SESSION['CKFINDER_BASEDIR'] = $attribs['baseDir'];
            unset($attribs['baseDir']);
        } else {
            unset($_SESSION['CKFINDER_BASEDIR']);
        }

        $this->view->headLink()->prependStylesheet($this->view->baseUrl('/scripts/jquery-plugins/fancybox/jquery.fancybox.css', false), 'screen');
        $this->view->headScript()->prependFile($this->view->baseUrl('/scripts/jquery-plugins/mousewheel/jquery.mousewheel.js', false));
        $this->view->headScript()->prependFile($this->view->baseUrl('/scripts/jquery-plugins/fancybox/jquery.fancybox.pack.js', false));
        //$this->view->headScript()->appendFile($this->view->baseUrl('/scripts/ckfinder/ckfinder.js', false));

        $jquery = $this->view->jQuery();
        $jquery->enable()
               ->uiEnable();

        $elementId = $this->view->escape(str_replace('-', '_', $id));
        $callBackFunction = 'setFieldValue' . md5($elementId);
        $idEscaped = $this->view->escape($id);
        $url = $this->view->baseUrl('/scripts/ckfinder/ckfinder.html?action=js&func=' . $callBackFunction .  '', false);
        
$js = <<<TOKEN
var finder_{$elementId}_dialog;

function $callBackFunction(value)
{
    value = decodeURIComponent((value + '').replace(/\+/g, '%20'));
    value = value.replace('system\/file\/download\/\?file=', '');

    $('#$idEscaped').val(value);
    $.fancybox.close();
}
TOKEN;

$jsOnload = <<<TOKEN
$('#finder_{$id}_button').button().click(function() {
    finder_{$elementId}_dialog = $.fancybox({
        'width'          : '75%',
        'height'         : '75%',
        'title'          : '',
        'autoScale'      : false,
        'transitionIn'   : 'none',
        'transitionOut'  : 'none',
        'centerOnScroll' : true,
        'type'           : 'iframe',
        'href'           : '$url'
    });

    return false;
});
TOKEN;

        $jquery->addJavascript($js);
        $jquery->addOnLoad($jsOnload);

        $xhtml = '';

        if (array_key_exists('class', $attribs)) {
			if ($attribs['class'] != '') {
				$attribs['class'] .= ' ';
			}
        } else {
        	$attribs['class'] = '';
        }

		$attribs['class'] .= 'file-selector';

        $xhtml .= parent::formText($name, $value, $attribs) . ' ';
        $xhtml .= '<a id="finder_' . $this->view->escape($id) . '_button" href="#">...</a>';

        return $xhtml;
    }
}