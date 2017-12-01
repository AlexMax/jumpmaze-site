<?php

namespace App\Auth;

use Cake\Auth\DigestAuthenticate;
use Cake\Core\Configure;
use Cake\Network\Request;

/**
 * Hardcoded digest authentication
 *
 * The username and password are stored in plaintext in a configuration
 * file.  Before you panic, there are a number of mitigating circumstances:
 *
 * - This website is intended to be administered by a single user or a
 *   very limited number of trusted people.
 * - Passwords are picked by the administrator and cannot be reset except
 *   by editing the configuration file.
 * - This website is likely sharing a server with a number of Zandronum
 *   servers, all of which have a remote console protected by a plaintext
 *   password set in a configuration file.
 * - Transport security is taken care of by the fact that this is digest
 *   authentication, not basic.
 * - We're talking about Jumpmaze records here, not social security numbers.
 */
class HardcodedAuthenticate extends DigestAuthenticate {

	/**
	 * Mostly copy-pasted from the original, but we look in our config for
	 * the username/password.
	 */
	public function getUser(Request $request) {
		$huser = Configure::read('Auth.username');
		$hpass = Configure::read('Auth.password');

		$password = self::password(
			$huser, $hpass,
			env('SERVER_NAME'));

		$digest = $this->_getDigest($request);
		if (empty($digest)) {
			return false;
		}

		$username = $digest['username'];
		if (empty($username) || $username !== $huser) {
			return false;
		}

		$hash = $this->generateResponseHash($digest, $password, $request->env('ORIGINAL_REQUEST_METHOD'));
		if ($digest['response'] === $hash) {
			return [true];
		}
		return false;
	}
}
