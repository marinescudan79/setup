<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 04:05:25
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-21 14:24:51
 */

namespace Application\Utils\Form;

use Zend\Form\Form;

class FormLayer extends Form
{
    public function getMessages($elementName = null)
    {
        if (null === $elementName) {
            $messages = array();

            foreach ($this->byName as $name => $element) {

                $name = $element->getLabel() !== null ? $element->getLabel() : $name;

                $messageSet = $element->getMessages();
                if (!is_array($messageSet)
                    && !$messageSet instanceof Traversable
                    || empty($messageSet)) {
                    continue;
                }
                $messages[$name] = $messageSet;
            }
            return $messages;
        }

        if (!$this->has($elementName)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Invalid element name "%s" provided to %s',
                $elementName,
                __METHOD__
            ));
        }

        $element = $this->get($elementName);
        return $element->getMessages();
    }

    /**
    * Disables all form elements except submit and the ones in the $elements array
    *
    * params array $elements | elements that will not be disabled
    */
    public function disableAll(array $elements = array())
    {
        if (sizeof($elements) > 0) {
            foreach ($this->byName as $name => $element) {
                if (!in_array($name, $elements)) {
                    $element->setAttribute('disabled', true);
                }
            }
        }
    }
}
