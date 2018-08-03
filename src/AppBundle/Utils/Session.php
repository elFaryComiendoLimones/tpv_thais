<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 03/08/2018
 * Time: 9:45
 */

namespace AppBundle\Utils;


class Session
{
    function __construct($name = null) {
        if (session_status() === PHP_SESSION_NONE) {
            if ($name !== null) {
                session_name($name);
            }
            session_start();
        }
    }

    function close() {
        $this->delete('__user');
    }

    function delete($name) {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
        return $this;
    }

    function destroy() {
        session_destroy();
    }

    function get($name) {
        $r = null;
        if (isset($_SESSION[$name])) {
            $r = $_SESSION[$name];
        }
        return $r;
    }

    function getUser() {
        return $this->get("__user");
    }

    function isLogged() {
        return $this->getUser() !== null;
    }

    // Bloqueo de sesión
    function isBlocked($block = null){
        if ($block == null){
            return $this->get("__blocked");
        }else{
            return $this->set("__blocked", $block);
        }
    }

    //start alias

    function login($user) {
        return $this->setUser($user);
    }

    function logout() {
        $this->close();
    }

    //end alias

    function set($name, $value) {
        $_SESSION[$name] = $value;
        return $this;
    }

    function setUser($user) {
        session_regenerate_id();
        return $this->set("__user", $user);
    }
}