<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 16:06
 */

namespace LibOnvif;


use LibOnvif\Contracts\ICommand;

class Envelope
{

    private $security;
    private $body;


    public function __construct (Security $security = null, ICommand $body = null)
    {
        $this->security = $security;
        $this->body = $body;
    }

    public function getSecurityHeader()
    {
        $return = '<Security s:mustUnderstand="1" xmlns="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">';
        $return .= '<UsernameToken>';
        $return .= '<Username>' . $this->security->getUsername() . '</Username>';
        $return .= '<Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">' . $this->security->passwordDigest() . '</Password>';
        $return .= '<Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">' . $this->security->getNonce()->toString() . '</Nonce>';
        $return .= '<Created xmlns="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">' . $this->security->getTimestamp() . '</Created>';
        $return .= '</UsernameToken>';
        $return .= '</Security>';
        return $return;
    }

    public function getHeader()
    {
        $return = '<s:Header>';
        if (!is_null($this->security)) {
            $return .= $this->getSecurityHeader();
        }
        $return.= '</s:Header>';
        return $return;
    }

    public function __toString (): string
    {
        return $this->toString();
    }

    public function toString (): string
    {
        $return = '<s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope" xmlns:a="http://www.w3.org/2005/08/addressing">';
        $return .= $this->getHeader();
        if (!is_null($this->body)) {
            $return .= '<s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">';
            $return .= $this->body->toString();
            $return .= '</s:Body>';
        }
        $return.= '</s:Envelope>';
        return $return;
    }

    /**
     * @return Security
     */
    public function getSecurity (): Security
    {
        return $this->security;
    }

    /**
     * @param Security $security
     * @return Envelope
     */
    public function setSecurity (Security $security): Envelope
    {
        $this->security = $security;
        return $this;
    }

    /**
     * @param ICommand $body
     * @return Envelope
     */
    public function setBody (ICommand $body): Envelope
    {
        $this->body = $body;
        return $this;
    }

}