<?php
/**
 * Summary File Session
 *
 * Description File Session
 *
 * ILYA CMS Created by ILYA-IDEA Company.
 * @author Ali Mansoori
 * Date: 11/29/2018
 * Time: 12:18 PM
 * @version 1.0.0
 * @copyright Copyright (c) 2017-2018, ILYA-IDEA Company
 */
namespace Lib\Flash;

use Lib\DataTables\DataTable;
use Lib\Forms\Form;
use Phalcon\Mvc\Model\Message;

class Session extends \Phalcon\Flash\Session
{
    public function message( $type, $message, $prefix = null )
    {
        $messages = [];
        if(is_array($message))
        {
            foreach($message as $msg)
            {
                if($msg instanceof Message)
                    $messages[] = $msg->getMessage();
                elseif(is_string($msg))
                    $messages[] = $msg;
                else
                    continue;
            }
        }

        if(!empty($messages))
            $message = $messages;

        $messages = $this->_getSessionMessages(false);

        if(!is_array($messages))
            $messages = [];

        if(!isset($messages[$type]))
            $messages[$type] = [];

        if(is_string($prefix) && $prefix !== null)
            $messages[$type][$prefix] = $message;
        else
            $messages[$type][] = $message;

        $this->_setSessionMessages($messages);

    }

    public function error( $message, $contentClass = null )
    {
        $this->message('error', $message, $contentClass);
    }

    public function success( $message, $contentClass = null )
    {
        $this->message('success', $message, $contentClass);
    }

    public function notice( $message, $contentClass = null )
    {
        $this->message('notice', $message, $contentClass);
    }

    public function warning( $message, $contentClass = null )
    {
        $this->message('warning', $message, $contentClass);
    }

    public function clear()
    {
        parent::clear(); // TODO: Change the autogenerated stub

        $this->getDI()->getShared('session')->remove('_flashMessages');
    }
}