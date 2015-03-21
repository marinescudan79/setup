<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Mvc\Controller\Plugin\FlashMessenger as FlashMessenger;

/**
 * @author Razvan Cojocaru
 * @company My1Outsourcing
 */
class FlashMessage extends AbstractHelper
{
    /**
     * @var FlashMessenger
     */
    protected $flashMessenger;
    protected $className;

    public function setFlashMessenger(FlashMessenger $flashMessenger)
    {
        $this->flashMessenger = $flashMessenger;
    }

    public function __invoke($includeCurrentMessages = false, $className = false)
    {
        if ($className) {
            $this->setClass($className);
        }

        $messages = array(
            FlashMessenger::NAMESPACE_ERROR => array('alert-danger'),
            FlashMessenger::NAMESPACE_SUCCESS => array(),
            FlashMessenger::NAMESPACE_INFO => array(),
            FlashMessenger::NAMESPACE_DEFAULT => array()
        );

        foreach ($messages as $ns => &$m) {
            $m = $this->flashMessenger->getCurrentMessagesFromNamespace($ns);

            $this->flashMessenger->clearCurrentMessagesFromNamespace($ns);
        }

        $messageString = '';

        foreach ($messages as $namespace => $messages) {
            if (count($messages)) {
                foreach ($messages as $message) {

                    $html = '';
                    if (is_array($message)) {

                        $html .= $this->recursiveGetHtml($message);

                    } else {
                        $html .= $message;
                    }

                    $namespace = $namespace == 'error' ? 'danger' : $namespace;
                    $messageString .= '<div class="alert alert-' . $namespace . $this->className . '  fade in"><a class="close" data-dismiss="alert" href="#">&times;</a>' . $html . '</div>';
                }
            }
        }

        return $messageString;
    }

    private function recursiveGetHtml($message, $parentKey = false)
    {
        $html = '';
        foreach ($message as $key => $arrMessage) {

            if (is_array($arrMessage)) {
                $html .= $this->recursiveGetHtml($arrMessage, $key);
            } elseif (is_int($key)) {
                $html .= $arrMessage .  '<br />';
            } else {
                $html .= '<b>' . $parentKey . '</b> : ' . $arrMessage .  '<br />';
            }

        }

        return $html;
    }


    // used to add an extra css class  to the alert div container
    public function setClass($cssClass)
    {
        $this->className = ' ' . $cssClass;
    }
}
