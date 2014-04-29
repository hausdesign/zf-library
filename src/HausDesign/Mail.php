<?php
class HausDesign_Mail extends Zend_Mail
{
    /**
     * Public constructor
     *
     * @param string $charset
     */
    public function __construct($charset = 'iso-8859-1')
    {
        // Call the constructor of the parent object
        parent::__construct($charset);
    }

    /**
     * Embed the images in the e-mail
     *
     * @return HausDesign_Mail
     */
    public function embedImages()
    {
        $imgNames = array();

        $string = $this->getBodyHtml();
        $string->encoding = Zend_MIME::ENCODING_8BIT;
        $string = $string->getContent();

        preg_match_all("/\< *[img][^\>]*src *= *[\"\']{0,1}([^\"\'\ >]*)/i", $string, $imgmatchesImg);
        if (is_array($imgmatchesImg[1])) {
            foreach ($imgmatchesImg[1] as $imgmatchImg) {
                $imgNames[] = $imgmatchImg;
            }
        }

        preg_match_all("/\< *[td][^\>]*background *= *[\"\']{0,1}([^\"\'\ >]*)/i", $string, $imgmatchesTd);
        if (is_array($imgmatchesTd[1])) {
            foreach ($imgmatchesTd[1] as $imgmatchTd) {
                $imgNames[] = $imgmatchTd;
            }
        }

        // Filter duplicate elements
        $imgNames = array_unique($imgNames);

        // Loop all images and replace them with embedded objects
        $i = 1;
        foreach ($imgNames as $value) {
            // Check if the image is a local image
            if (! preg_match('/^http:\/\/|^mailto/', $value)) {
                try {
                    // Try to open the file
                    $file = PUBLIC_PATH . $value;
                    $file = @file_get_contents($file);
                    
                    if ($file === false) {
                    
                    } else {
                        // Detect the extension of the file
                        preg_match("/\.([^\.]+)$/", $value, $extensionMatches);
                        $extension = $extensionMatches[1];
    
                        // Set the attachement encoding
                        switch ($extension) {
                            case 'gif':
                                $encoding = 'image/gif';
                                break;
            
                            case 'png':
                                $encoding = 'image/png';
                                break;
            
                            case 'jpg':
                            case 'jpeg':
                                $encoding = 'image/jpeg';
                        }
    
                        // Add the attachement to the email
                        $image = $this->createAttachment($file);
                        $image->type        = $encoding;
                        $image->id          = 'embedded-image-' . $i;
                        $image->filename    = 'embedded-image-' . $i . '.' . $extension;
                        $image->disposition = Zend_Mime::DISPOSITION_INLINE;
    
                        // Set the message MIME-type to multipart/related
                        $this->setType(Zend_Mime::MULTIPART_RELATED);
    
                        // str-replace
                        $string = str_replace($value, 'cid:embedded-image-' . $i, $string);
                    }
                } catch (Exception $exception) {
                    
                }
            }

            $i++;
        }

        $this->setBodyHtml($string);

        return $this;
    }

    /**
     * Replace placeholders in the bodytext
     *
     * @param array $array
     * @param bool $removeNonExists
     * @return HausDesign_Mail
     */
    public function replacePlaceholders(array $array, $removeNonExists = false)
    {
        $bodyText = $this->getBodyText();
        if (($bodyText) && (! is_null($bodyText))) {
            $bodyText->encoding = Zend_MIME::ENCODING_8BIT;
            $bodyText = $bodyText->getContent();

            $placeholdersUnique = array();

            // Get the placeholders in the body text
            preg_match_all("#\[(.+?)\]#i", $bodyText, $placeholders);
            if (is_array($placeholders[1])) {
                foreach ($placeholders[1] as $placeholder) {
                    $placeholdersUnique[] = $placeholder;
                }
            }

            foreach (array_unique($placeholdersUnique) as $placeholderUnique) {
                if (array_key_exists($placeholderUnique, $array)) {
                    $bodyText = str_replace('[' . $placeholderUnique . ']', $array[$placeholderUnique], $bodyText);
                } else {
                    if ($removeNonExists) {
                        $bodyText = str_replace('[' . $placeholderUnique . ']', '', $bodyText);
                    }
                }
            }
        
            $this->setBodyText($bodyText);
        }

        $bodyHtml = $this->getBodyHtml();
        if (($bodyHtml) && (! is_null($bodyHtml))) { 
            $bodyHtml->encoding = Zend_MIME::ENCODING_8BIT;
            $bodyHtml = $bodyHtml->getContent();

            $placeholdersUnique = array();

            // Get the placeholders in the body html
            preg_match_all("#\[(.+?)\]#i", $bodyHtml, $placeholders);
            if (is_array($placeholders[1])) {
                foreach ($placeholders[1] as $placeholder) {
                    $placeholdersUnique[] = $placeholder;
                }
            }

            foreach (array_unique($placeholdersUnique) as $placeholderUnique) {
                if (array_key_exists($placeholderUnique, $array)) {
                    $bodyHtml = str_replace('[' . $placeholderUnique . ']', $array[$placeholderUnique], $bodyHtml);
                } else {
                    if ($removeNonExists) {
                        $bodyHtml = str_replace('[' . $placeholderUnique . ']', '', $bodyHtml);
                    }
                }
            }
        
            $this->setBodyHtml($bodyHtml);
        }

        return $this;
    }

    /**
     * Sends this email using the given transport or a previously
     * set DefaultTransport or the internal mail function if no
     * default transport had been set.
     *
     * @param  Zend_Mail_Transport_Abstract $transport
     * @return Zend_Mail                    Provides fluent interface
     */
    public function send($transport = null)
    {
        $log = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('Log');

        foreach ($this->getRecipients() as $receiver) {
           $log->log('Send mail to: ' . $receiver, Zend_Log::INFO);
        }

    	parent::send($transport);
    }
}