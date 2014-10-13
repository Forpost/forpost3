<?php

/**
 * Created by JetBrains PhpStorm.
 * User: CoolKid
 * Date: 16.10.13
 * Time: 22:00
 * To change this template use File | Settings | File Templates.
 */
class FUser
{
    protected $properties = array();

    protected function setAuthCookie()
    {
        $cookie_hash = $this->genCookieHash();
        $sql = "UPDATE `fpst_users` SET cookie_hash = ? WHERE user_id = ?";
        DB::prepare($sql)->execute(array($cookie_hash, $this->properties['user_id']));
        Lib::setCookie('FORPOST_USER', $this->properties['user_id']);
        Lib::setCookie('FORPOST_HASH', $cookie_hash);
    }

    protected function delAuthCookie()
    {
        $sql = "UPDATE `fpst_users` SET cookie_hash = null WHERE user_id = ?";
        DB::prepare($sql)->execute(array($this->properties['user_id']));
        Lib::setCookie('FORPOST_USER');
        Lib::setCookie('FORPOST_HASH');
    }

    public function isAuth()
    {
        return Session::get('user.auth');
    }

    private function genCookieHash()
    {
        return Lib::genRandID();
    }

    public function authByCookie($id, $hash)
    {
        $sql = "SELECT * FROM `fpst_users` WHERE user_id = ? AND cookie_hash = ?";
        $data = DB::prepare($sql)->execute(array($id, $hash))->fetchAssoc();

        if (false !== $data) {
            Session::add('user.auth', true);
            Session::add('user.id', $id);
            Session::add('user.properties', $data[0]);
            $this->properties = $data[0];

            return true;
        }

        return false;
    }

    public function authByUsernamePassword($username, $password, $remember_auth = false)
    {
        $sql = "SELECT * FROM `fpst_users`
              WHERE username = ?
              OR (email = ? AND is_email_confirmed = 'Y')
              OR (cell_phone = ? AND is_phone_confirmed = 'Y')
              AND is_soc_auth = 'N'";
        $data = DB::prepare($sql)->execute(array($username, $username, $username))->fetchAssoc();

        if (false !== $data && password_verify($password, $data[0]['pass_hash'])) {
            Session::add('user.auth', true);
            Session::add('user.id', $data[0]['user_id']);
            Session::add('user.properties', $data[0]);
            $this->properties = $data[0];

            if ($remember_auth) {
                $this->setAuthCookie();
            } else {
                $this->delAuthCookie();
            }

            return true;
        }

        return false;
    }

    public function logout()
    {
        Session::flush();
        $this->delAuthCookie();
        session_regenerate_id(true);
    }

    public function getProperties()
    {
        return $this->isAuth() ? Session::get('user.properties') : false;
    }

    public function getFullName()
    {
        if ($this->isAuth()) {
            $properties = $this->getProperties();

            return $properties['surname'] . ' ' . $properties['name'] . ' ' . $properties['patronymic'];
        }

        return false;
    }

    public function getLogin()
    {
        if ($this->isAuth()) {
            $properties = $this->getProperties();

            return $properties['username'];
        }

        return false;
    }

    public function getUserID()
    {
        if ($this->isAuth()) {
            $properties = $this->getProperties();

            return $properties['user_id'];
        }

        return false;
    }

    public function addUser(
        $username,
        $password,
        $email = null,
        $cellphone = null,
        $name = null,
        $surname = null,
        $patronymic = null
    ) {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO fpst_users (username, pass_hash, email, cell_phone, name, surname, patronymic)
                VALUES (:username, :password, :email, :cellphone, :name, :surname, :patronymic)";

        return DB::prepare($sql)
            ->execute(
                array(
                    'username'   => $username,
                    'password'   => $password_hash,
                    'email'      => $email,
                    'cellphone'  => $cellphone,
                    'name'       => $name,
                    'surname'    => $surname,
                    'patronymic' => $patronymic,
                )
            )
            ->affectedRows();
    }

}
