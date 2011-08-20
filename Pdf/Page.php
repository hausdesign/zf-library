<?php 
class HausDesign_Pdf_Page extends Zend_Pdf_Page
{
    /**
     * Align text at left of provided coordinates
     * 
     * @var string
     */
    const TEXT_ALIGN_LEFT = 'left';
    
    /**
     * Align text at right of provided coordinates
     * 
     * @var string
     */
    const TEXT_ALIGN_RIGHT = 'right';
    
    /**
     * Center-text horizontally within provided coordinates
     * 
     * @var string
     */
    const TEXT_ALIGN_CENTER = 'center';
    
    /**
     * Extension of basic draw-text function to allow it to vertically center text
     *
     * @param string $text
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $position
     * @param string $encoding
     * @return Zend_Pdf_Page
     */
    public function drawTextAlignment($text, $x1, $y1, $x2 = null, $position = self::TEXT_ALIGN_LEFT, $encoding = null)
    {
        $bottom = $y1; // could do the same for vertical-centering
        switch ($position) {
            case self::TEXT_ALIGN_LEFT:
                $left = $x1;
                break;

            case self::TEXT_ALIGN_RIGHT:
            	if (null === $x2) {
                    throw new Exception ("Cannot right-align text horizontally, x2 is not provided");
                }
                $textWidth = self::getTextWidth($text);
                $left = $x2 - $textWidth;
                break;

            case self::TEXT_ALIGN_CENTER:
                if (null === $x2) {
                    throw new Exception ("Cannot center text horizontally, x2 is not provided");
                }
                $textWidth = self::getTextWidth($text);
                $left = $x1 + (($x2 - $x1) - $textWidth) /2;
                break;

            default:
                throw new Exception ('Invalid position value "' . $position . '"');
        }

        // display multi-line text
        $this->drawText($text, $left, $y1, $encoding);

        return $this;
    }

    /**
     * Draw text inside a box using word wrap
     * 
     * @param string $text
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $position
     * @param float $lineHeight
     * @param string $encoding
     * 
     * @return integer bottomPosition
     */
    public function drawTextBox($text, $x1, $y1, $x2 = null, $position = self::TEXT_ALIGN_LEFT, $lineHeight = 1.1, $encoding = null)
    {
        $lines = explode(PHP_EOL, $text);

        $bottom = $y1;
        $lineHeight = $this->getFontSize() * $lineHeight;
        foreach( $lines as $line ){
            preg_match_all('/([^\s]*\s*)/i', $line, $matches);

            $words = $matches[1];

            $lineText = '';
            $lineWidth = 0;
            foreach( $words as $word ){
                $wordWidth = self::getTextWidth($word, $this);

                if ($lineWidth+$wordWidth < $x2 - $x1) {
                    $lineText .= $word;
                    $lineWidth += $wordWidth;
                } else {
                    self::drawTextAlignment($lineText, $x1, $bottom, $x2, $position, $encoding);
                    $bottom -= $lineHeight;
                    $lineText = $word;
                    $lineWidth = $wordWidth;
                }
            }

            self::drawTextAlignment($lineText, $x1, $bottom, $x2, $position, $encoding);

            $bottom -= $lineHeight;
        }

        return $bottom;
    }

    /**
     * Return length of generated string in points
     *
     * @param string $text
     * @return double
     */
    public function getTextWidth($text, $encoding = null)
    {
        $font = $this->getFont();
        $fontSize = $this->getFontSize();

        if (!$font instanceof Zend_Pdf_Resource_Font) {
            throw new Exception('Invalid resource passed');
        }

        if ($fontSize === null) {
        	throw new Exception('The fontsize is unknown');
        }

        $drawingText = iconv('', $encoding, $text);
        $characters = array();
        for ($i = 0; $i < strlen($drawingText); $i++) {
            $characters [] = ord($drawingText[$i]);
        }

        $glyphs = $font->glyphNumbersForCharacters($characters);
        $widths = $font->widthsForGlyphs($glyphs);

        $textWidth = (array_sum ($widths) / $font->getUnitsPerEm()) * $fontSize;

        return $textWidth;
    }
}