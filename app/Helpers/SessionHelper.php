<?php
namespace App\Helpers;

/**
* SESSION辅助类
*/
class SessionHelper
{
    /**
     * Starts the session.
     */
    public static function open()
    {
        if (self::isActive()) {
            return;
        }

        @session_start();

        if (!self::isActive()) {
            $error = error_get_last();
            $message = isset($error['message']) ? $error['message'] : 'start session failed.';

            throw new ErrorException($message);
        }
    }

    /**
     * Returns the session variable value with the session variable name.
     * If the session variable does not exist, the `$defaultValue` will be returned.
     * @param string $key the session variable name
     * @param mixed $defaultValue the default value to be returned when the session variable does not exist.
     * @return mixed the session variable value, or $defaultValue if the session variable does not exist.
     */
    public static function get($key, $defaultValue = null)
    {
        self::open();

        return isset($_SESSION[$key]) ? $_SESSION[$key] : $defaultValue;
    }

    /**
     * Adds a session variable.
     * If the specified name already exists, the old value will be overwritten.
     * @param string $key session variable name
     * @param mixed $value session variable value
     */
    public static function set($key, $value)
    {
        self::open();

        $_SESSION[$key] = $value;
    }

    /**
     * Removes a session variable.
     * @param string $key the name of the session variable to be removed
     * @return mixed the removed value, null if no such session variable.
     */
    public static function remove($key)
    {
        self::open();

        if (!isset($_SESSION[$key])) {
            return null;
        }

        $value = $_SESSION[$key];
        unset($_SESSION[$key]);

        return $value;
    }

    /**
     * Removes all session variables
     */
    public static function removeAll()
    {
        self::open();

        foreach (array_keys($_SESSION) as $key) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * @param mixed $key session variable name
     * @return bool whether there is the named session variable
     */
    public static function has($key)
    {
        self::open();

        return isset($_SESSION[$key]);
    }

    /**
     * Ends the current session and store session data.
     */
    public static function close()
    {
        if (self::isActive()) {
            @session_write_close();
        }
    }

    /**
     * Frees all session variables and destroys all data registered to a session.
     */
    public static function destroy()
    {
        session_unset();
        session_destroy();
    }

    /**
     * @param int $value the number of seconds after which data will be seen as 'garbage' and cleaned up
     */
    public static function setTimeout($seconds)
    {
        ini_set('session.gc_maxlifetime', $seconds);
    }

    /**
     * @return bool whether the session has started
     */
    public static function isActive()
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }
}
?>