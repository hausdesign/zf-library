<?php
class HausDesign_View_Helper_FormWysiwyg extends Zend_View_Helper_FormTextarea
{
    public function formWysiwyg($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        if (isset($attribs['baseUrl'])) {
            unset($attribs['baseUrl']);
        	$_SESSION['CKFINDER_BASEURL'] = $attribs['baseUrl'];
        } else {
        	unset($_SESSION['CKFINDER_BASEURL']);
        }

        if (isset($attribs['baseDir'])) {
            unset($attribs['baseDir']);
        	$_SESSION['CKFINDER_BASEDIR'] = $attribs['baseDir'];
        } else {
        	unset($_SESSION['CKFINDER_BASEDIR']);
        }

        $this->_renderScript($name, $value, $attribs);

        return parent::formTextarea($name, $value, $attribs);
    }

    protected function _renderScript($name, $value, $attribs)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable
        if (array_key_exists('width', $attribs)) {
            $width = $attribs['width'];
        } else {
            $width = 300;
        }

        if (array_key_exists('height', $attribs)) {
            $height = $attribs['height'];
        } else {
            $height = 300;
        }

        $this->view->headScript()->appendFile($this->view->baseUrl('/scripts/ckeditor/ckeditor.js', false));
        $this->view->headScript()->appendFile($this->view->baseUrl('/scripts/ckeditor/adapters/jquery.js', false));
        $this->view->headScript()->appendFile($this->view->baseUrl('/scripts/ckfinder/ckfinder.js', false));
        $this->view->headScript()->captureStart();
?>
$(document).ready(function() {
    $('#<?php echo $name; ?>').ckeditor({
        toolbar: [
            ['Undo','Redo'],['Bold','Italic','Underline','Strike','-','Subscript','Superscript'], ['NumberedList', 'BulletedList'],
            ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
            ['Link', 'Image','Flash','SpecialChar','Table'],
            '/',
            ['Styles', 'Format'],['Maximize', 'ShowBlocks'],['Source']
        ],
        uiColor: '#CCCCCC',
        <?php
        if (array_key_exists('stylesheet', $attribs)) {
            $stylesheetName = $attribs['stylesheet'];
        } else {
            $stylesheetName = 'wysiwyg.css';
        }

        $stylesheet = $this->view->templateUrl('/styles/' . $stylesheetName);
        if (Zend_Registry::isRegistered('domain_site_language') && (($site = Zend_Registry::get('domain_site_language')) !== null)) {
            $domainSiteLanguage = Zend_Registry::get('domain_site_language');
            $site = $domainSiteLanguage->getSite();
            if ($site !== null) {
                $theme = $site->getTheme();
                if ($theme !== null) {
                    $stylesheet = $this->view->baseUrl('/templates/' .  $theme->theme_directory . '/styles/' . $stylesheetName, false);
                }
            }
        }
        ?>
        contentsCss: '<?php echo $stylesheet; ?>',
        forcePasteAsPlainText: true,
        width: '<?php echo $width; ?>',
        height: <?php echo $height; ?>,
        resize_minWidth: <?php echo $width; ?>,
        resize_maxWidth: <?php echo $width; ?>,
        stylesSet: [ {
            name: 'Alternative header',
            element: 'span',
            styles: {
                
            },
            attributes : {
                'class': 'alt'
            }
        }, {
            name: 'Highlight',
            element: 'span',
            styles: {
                
            },
            attributes : {
                'class': 'highlight'
            }
        } ],
        on: {
            instanceReady: function(ev) {
                //ev.editor.dataProcessor.writer.selfClosingEnd = '>';

                this.dataProcessor.writer.indentationChars = '    ';

                //this.dataProcessor.writer.setRules('h1', {indent:false, breakBeforeOpen: true, breakAfterOpen: false, breakBeforeClose: false, breakAfterClose: true});
                //this.dataProcessor.writer.setRules('h2', {indent:false, breakBeforeOpen: true, breakAfterOpen: false, breakBeforeClose: false, breakAfterClose: true});
                //this.dataProcessor.writer.setRules('h3', {indent:false, breakBeforeOpen: true, breakAfterOpen: false, breakBeforeClose: false, breakAfterClose: true});
                //this.dataProcessor.writer.setRules('h4', {indent:false, breakBeforeOpen: true, breakAfterOpen: false, breakBeforeClose: false, breakAfterClose: true});
                //this.dataProcessor.writer.setRules('div', {indent:true, breakBeforeOpen: true, breakAfterOpen: true, breakBeforeClose: true, breakAfterClose: true});
                //this.dataProcessor.writer.setRules('p', {indent:false, breakBeforeOpen: true, breakAfterOpen: true, breakBeforeClose: true, breakAfterClose: true});
                //this.dataProcessor.writer.setRules('td', {indent:true, breakBeforeOpen: true, breakAfterOpen: true, breakBeforeClose: true, breakAfterClose: true});
                //this.dataProcessor.writer.setRules('li', {indent:false, breakBeforeOpen: true, breakAfterOpen: false, breakBeforeClose: false, breakAfterClose: true});
            }
        }
    });

    var editor = $('#<?php echo $name; ?>').ckeditorGet();
    CKFinder.setupCKEditor(editor, '/scripts/ckfinder/');
});
<?php 
        $this->view->headScript()->captureEnd();
    }
}