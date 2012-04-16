<?php
class HausDesign_Pdf extends Zend_Pdf
{
    /**
     * Load PDF document from a file
     *
     * @param string $source
     * @param integer $revision
     * @return HausDesign_Pdf
     */
    public static function load($source = null, $revision = null)
    {
        return new HausDesign_Pdf($source, $revision, true);
    }

    /**
     * Load pages recursively
     *
     * @param Zend_Pdf_Element_Reference $pages
     * @param array|null $attributes
     */
    protected function _loadPages(Zend_Pdf_Element_Reference $pages, $attributes = array())
    {
        if ($pages->getType() != Zend_Pdf_Element::TYPE_DICTIONARY) {
            require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception('Wrong argument');
        }

        foreach ($pages->getKeys() as $property) {
            if (in_array($property, self::$_inheritableAttributes)) {
                $attributes[$property] = $pages->$property;
                $pages->$property = null;
            }
        }

        foreach ($pages->Kids->items as $child) {
            if ($child->Type->value == 'Pages') {
                $this->_loadPages($child, $attributes);
            } else if ($child->Type->value == 'Page') {
                foreach (self::$_inheritableAttributes as $property) {
                    if ($child->$property === null && array_key_exists($property, $attributes)) {
                        /**
                         * Important note.
                         * If any attribute or dependant object is an indirect object, then it's still
                         * shared between pages.
                         */
                        if ($attributes[$property] instanceof Zend_Pdf_Element_Object  ||
                                $attributes[$property] instanceof Zend_Pdf_Element_Reference) {
                            $child->$property = $attributes[$property];
                        } else {
                            $child->$property = $this->_objFactory->newObject($attributes[$property]);
                        }
                    }
                }

                $this->pages[] = new HausDesign_Pdf_Page($child, $this->_objFactory);
            }
        }
    }
}