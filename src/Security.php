<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 16:12
 */

namespace LibOnvif;


use Carbon\Carbon;

class Security
{
    private $username;
    private $nonce;
    private $date;
    private $password;


    public function __construct (Nonce $nonce, Carbon $date, string $username, string $password)
    {
        $this->nonce = $nonce;
        $this->date = $date;
        $this->username = $username;
        $this->password = $password;
    }

    static public function generate(string $username, string $password, Carbon $timestamp = null, int $nonceSize = 16): Security
    {
        $nonce = Nonce::generate($nonceSize);
        $date = is_null($timestamp) ? Carbon::now() : $timestamp;
        return new self($nonce, $date, $username, $password);
    }

    public function __toString ()
    {
        return $this->toString();
    }

    public function toString ()
    {
        return sha1($this->nonce->getBuffer() . $this->date->format('c') . $this->password, true);
    }

    public function passwordDigest()
    {
        return base64_encode($this->toString());
    }

    /**
     * @return mixed
     */
    public function getUsername ()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername ($username)
    {
        $this->username = $username;
    }

    /**
     * @return Nonce
     */
    public function getNonce (): Nonce
    {
        return $this->nonce;
    }

    /**
     * @return Carbon
     */
    public function getDate (): Carbon
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getTimestamp (): string
    {
        return $this->date->format('c');
    }

    /**
     * @return string
     */
    public function getPassword (): string
    {
        return $this->password;
    }
}