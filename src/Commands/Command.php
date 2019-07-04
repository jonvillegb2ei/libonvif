<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 28/06/19
 * Time: 09:40
 */

namespace LibOnvif\Commands;


use DOMElement;
use DOMNodeList;
use LibOnvif\Contracts\ICommand;

class Command implements ICommand
{

    protected $token;
    protected $profileToken;
    protected $configurationToken;
    protected $response;

    protected function getFirstElementContent(DOMNodeList $elements)
    {
        return $elements->count() > 0 ? $elements[0]->textContent : null;
    }

    public function extractElements(DOMElement $node, string $name, array $elements)
    {
        $return = [];
        $nodes = $node->getElementsByTagName($name);
        if ($nodes->count() > 0) {
            foreach($elements as $name => $element) {
                $return[$name] = $this->getFirstElementContent($nodes[0]->getElementsByTagName($element));
            }
        }
        return $return;
    }

    public function toString (): string
    {
        return '';
    }

    public function __toString (): string
    {
        return $this->toString();
    }


    public function parse (string $response)
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadXML($response);
        $className = explode("\\", get_class($this));
        $className = $className[count($className)-1];
        $stop = $domDocument->getElementsByTagName($className . 'Response');
        return $stop->count() > 0;
    }

    protected function parseInt($value)
    {
        try {
            return intval($value);
        } catch(\Exception $e) {
            return $value;
        }
    }

    protected function parseBool($value)
    {
        try {
            return strtolower($value) == 'true' || $value === '1';
        } catch(\Exception $e) {
            return $value;
        }
    }

    protected function parseFloat($value)
    {
        try {
            return floatval($value);
        } catch(\Exception $e) {
            return $value;
        }
    }

    /**
     * @return string
     */
    public function getToken (): ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return ICommand
     */
    public function setToken (string $token = null): ICommand
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getProfileToken (): ?string
    {
        return $this->profileToken;
    }

    /**
     * @param string $profileToken
     * @return ICommand
     */
    public function setProfileToken (string $profileToken = null): ICommand
    {
        $this->profileToken = $profileToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfigurationToken (): ?string
    {
        return $this->configurationToken;
    }

    /**
     * @param string $configurationToken
     * @return ICommand
     */
    public function setConfigurationToken (string $configurationToken = null): ICommand
    {
        $this->configurationToken = $configurationToken;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse (): string
    {
        return $this->response;
    }

    /**
     * @param string $response
     * @return ICommand
     */
    public function setResponse (string $response): ICommand
    {
        $this->response = $response;
        return $this;
    }



}