<?php
/**
 * A class to simplify the use of global cookies throughout a website.
 *
 * @package Resources
 * @category PHP
 * @link http://3ft9.com/snippet-cookie-class-for-php/ The original developer
 */
class Cookie {
	const SESSION = null;
	const ONE_DAY = 86400;
	const ONE_WEEK = 604800;
	const ONE_MONTH = 2592000;
	const ONE_YEAR = 31536000;
	const INFINITE = -1;

	/**
	 * Returns true if there is a cookie with this name.
	 *
	 * @param string $name
	 * @return bool
	 */
	static public function exists($name) {
		return isset($_COOKIE[$name]);
	}

	/**
	 * Returns true if there no cookie with this name or it's empty, or 0,
	 * or a few other things. Check http://php.net/empty for a full list.
	 *
	 * @param string $name
	 * @return bool
	 */
	public static function isEmpty($name) {
		return empty($_COOKIE[$name]);
	}

	/**
	 * Get the value of the given cookie. If the cookie does not exist the value
	 * of $default will be returned.
	 *
	 * @param string $name
	 * @param string $default
	 * @return mixed
	 */
	public static function get($name, $default = '') {
		return (isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default);
	}

	/**
	 * Set a cookie. Silently does nothing if headers have already been sent.
	 *
	 * @param string $name
	 * @param string $value
	 * @param mixed $expiry
	 * @param string $path
	 * @param string $domain
	 * @return bool
	 */
	static public function set($name, $value, $expiry = self::ONE_YEAR, $path = '/', $domain = false) {
		$retval = false;

		if (!headers_sent()) {
			if ($domain === false) {
				$domain = $_SERVER['HTTP_HOST'];
			}

			if ($expiry === -1) {
				$expiry = INFINITE;
			} elseif (is_numeric($expiry)) {
				$expiry += time();
			} else {
				$expiry = strtotime($expiry);
			}

			$retval = @setcookie($name, $value, $expiry, $path, $domain);

			if ($retval) {
				$_COOKIE[$name] = $value;
			}

			return $retval;
		}
	}

	/**
	 * Delete a cookie.
	 *
	 * @param string $name
	 * @param string $path
	 * @param string $domain
	 * @param bool $remove_from_global Set to true to remove this cookie from this request.
	 * @return bool
	 */
	static public function delete($name, $path = '/', $domain = false, $remove_from_global = false) {
		$retval = false;

		if (!headers_sent()) {
			if ($domain === false) {
				$domain = $_SERVER['HTTP_HOST'];
			}

			$retval = setcookie($name, '', time() - 3600, $path, $domain);

			if ($remove_from_global) {
				unset($_COOKIE[$name]);
			}

			return $retval;
		}
	}
}