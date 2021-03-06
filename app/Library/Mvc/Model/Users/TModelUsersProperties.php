<?php
/**
 * Summary File TModelUsersProperties
 *
 * Description File TModelUsersProperties
 *
 * ILYA CMS Created by ILYA-IDEA Company.
 * @author Ali Mansoori
 * Date: 12/24/2018
 * Time: 6:08 PM
 * @version 1.0.0
 * @copyright Copyright (c) 2017-2018, ILYA-IDEA Company
 */

namespace Lib\Mvc\Model\Users;


trait TModelUsersProperties
{
    protected $id;
    protected $created_at;
    protected $create_ip;
    protected $email;
    protected $username;
    protected $password;
    protected $avatar_id;
    protected $avatar_width;
    protected $avatar_height;

    /**
     * @return mixed
     */
    public function getAvatarId()
    {
        return $this->avatar_id;
    }

    /**
     * @param mixed $avatar_id
     */
    public function setAvatarId($avatar_id)
    {
        $this->avatar_id = $avatar_id;
    }

    /**
     * @return mixed
     */
    public function getAvatarWidth()
    {
        return $this->avatar_width;
    }

    /**
     * @param mixed $avatar_width
     */
    public function setAvatarWidth($avatar_width)
    {
        $this->avatar_width = $avatar_width;
    }

    /**
     * @return mixed
     */
    public function getAvatarHeight()
    {
        return $this->avatar_height;
    }

    /**
     * @param mixed $avatar_height
     */
    public function setAvatarHeight($avatar_height)
    {
        $this->avatar_height = $avatar_height;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt( $created_at )
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getCreateIp()
    {
        return $this->create_ip;
    }

    /**
     * @param mixed $create_ip
     */
    public function setCreateIp( $create_ip )
    {
        $this->create_ip = $create_ip;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail( $email )
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername( $username )
    {
        $this->username = $username;
    }

    public function setPassword( $password )
    {
        $this->password = $this->getDI()->get('crypt')->encryptBase64(
            $password,
            $this->getDI()->get('crypt')->getKey()
        );
    }

    public function getPassword()
    {
        return $this->getDI()->get('crypt')->decryptBase64(
            $this->password,
            $this->getDI()->get('crypt')->getKey()
        );
    }
}