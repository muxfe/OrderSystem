<?php
/**
 * 登录验证
 * @copyright (c) Emlog All Rights Reserved
 */

class LoginAuth{

	const LOGIN_ERROR_USER = -1;
	const LOGIN_ERROR_PASSWD = -2;
	const LOGIN_ERROR_AUTHCODE = -3;

	/**
	 *验证用户是否处于登陆状态
	 */
	public static function isLogin(){
		global $userData;
		$auth_cookie = '';
		if(isset($_COOKIE[AUTH_COOKIE_NAME])){
			$auth_cookie = $_COOKIE[AUTH_COOKIE_NAME];
		}else if(isset($_POST[AUTH_COOKIE_NAME])){
			$auth_cookie = $_POST[AUTH_COOKIE_NAME];
		}else{
			return false;
		}

		if(($userData = self::validateAuthCookie($auth_cookie)) === fasle){
			return false;
		}else{
			return true;
		}/**/
	}

	/**
	 *验证密码/用户
	 *
	 *@param string $username
	 *@param string $password
     *@param string $imgcode 验证码
	 */ 
    public static function checkUser($username, $password, $imgcode) {
        session_start();
        if (trim($username) == '' || trim($password) == '') {
            return false;
        } else {
            /*验证码
            $sessionCode = isset($_SESSION['code']) ? $_SESSION['code'] : '';
            if ($imgcode != $sessionCode) {
                return self::LOGIN_ERROR_AUTHCODE;
            }
            */
            $userData = self::getUserDataByLogin($username);
            if (false === $userData) {
                return self::LOGIN_ERROR_USER;
            }
            $hash = $userData['password'];
            if (true === self::checkPassword($password, $hash)){
                return true;
            } else{
                return self::LOGIN_ERROR_PASSWD;
            }
        }
    }

	/**
	 *登陆页面
	 */
	public static function loginPage($errorCode = NULL){
		$ckcode = "<span>验证码</span>
		<div class=\"val\"><input name=\"imgcode\" id=\"imgcode\" type=\"text\" />
		<img src=\"./include/lib/checkcode.php\" align=\"absmiddle\"></div> ";
		$error_msg = '';
        $ckcode = ''; //取消验证码
		if($errorCode){
			switch($errorCode){
				case self::LOGIN_ERROR_AUTHCODE:
					$error_msg = '验证码错误，请重新输入';
					break;
				case self::LOGIN_ERROR_USER:
					$error_msg = '用户名错误，请重新输入';
					break;
				case self::LOGIN_ERROR_PASSWD:
					$error_msg = '密码错误，请重新输入';
					break;
			}
		}
		require_once TEMPLATE_PATH.'login.php';
        exit;
	}

	/**
	 *通过登陆名查询管理员信息
	 *
	 *@param string $userLogin User's username
	 *@return bool | object False on failure, User DB row object.
	 */
	public static function getUserDataByLogin($userLogin){
		$DB = MySql::getInstance(); 
		if(empty($userLogin)){
			return false;
		}
		$userData = false;
		if(!$userData = $DB->once_fetch_array("SELECT * FROM ".DB_PREFIX."user WHERE username = '$userLogin'")){
			return false;
		}
		$userData['username'] = htmlspecialchars($userData['username']);
		return $userData;
	}

	/**
	 *密码验证
	 *
	 *@param string $password Plaintext user's password.
	 *@param string $hash Hash of the user's password to check against.
	 *@return bool False, if the $password does not match the hashed password.
	 */
	public static function checkPassword($password, $hash){
		global $jc_hasher;
		if(empty($jc_hasher)){
			$jc_hasher = new PasswordHash(8, true);
		}
		$check = $jc_hasher->CheckPassword($password, $hash);
		return $check;
	}

	/**
	 *写用于登陆验证的Cookie
     *
     *@param int $user_id User ID
     *@param bool $remember Whether to remember the user or not.
	 */
	public static function setAuthCookie($user_login, $ispersis = false){
		if($ispersis){
			$expiration = time() + 3600 * 24 * 30 * 12;
		}else{
			$expiration = null;
		}
		$auth_cookie_name = AUTH_COOKIE_NAME;
		$auth_cookie = self::generateAuthCookie($user_login, $expiration);
		setcookie($auth_cookie_name, $auth_cookie, $expiration, '/');
	}

    /**
     * 生成登录验证cookie
     *
     * @param int $user_id user login
     * @param int $expiration Cookie expiration in seconds
     * @return string Authentication cookie contents
     */
    public static function generateAuthCookie($user_login, $expiration){
    	$key = self::jcHash($user_login.'|'.$expiration);
    	$hash = hash_hmac('md5', $user_login.'|'.$expiration, $key);

    	$cookie = $user_login.'|'.$expiration.'|'.$hash;

    	return $cookie;
    }

    /**
     * Get hash of given string.
     *
     * @param string $data Plain text to hash
     * @return string Hash of $data
     */
    private static function jcHash($data) {
        $key = AUTH_KEY;
        return hash_hmac('md5', $data, $key);
    }


    /**
     * 验证cookie
     * Validates authentication cookie.
     *
     * @param string $cookie Optional. If used, will validate contents instead of cookie's
     * @return bool|int False if invalid cookie, User ID if valid.
     */
    private static function validateAuthCookie($cookie = '') {
        if (empty($cookie)) {
            return false;
        }

        $cookie_elements = explode('|', $cookie);
        if (count($cookie_elements) != 3) {
            return false;
        }

        list($username, $expiration, $hmac) = $cookie_elements;

        if (!empty($expiration) && $expiration < time()) {
            return false;
        }

        $key = self::jcHash($username . '|' . $expiration);
        $hash = hash_hmac('md5', $username . '|' . $expiration, $key);

        if ($hmac != $hash) {
            return false;
        }

        $user = self::getUserDataByLogin($username);
        if (!$user) {
            return false;
        }
        return $user;
    }

    /**
     * 生成token，防御CSRF攻击
     */
    public static function genToken() {
        $token_cookie_name = 'JC_TOKENCOOKIE_' . md5(substr(AUTH_KEY, 16, 32) . UID);
        if (isset($_COOKIE[$token_cookie_name])) {
            return $_COOKIE[$token_cookie_name];
        } else {
            $token = md5(getRandStr(16));
            setcookie($token_cookie_name, $token, 0, '/');
            return $token;
        }
    }

    /**
     * 检查token，防御CSRF攻击
     */
    public static function checkToken(){
        $token = isset($_REQUEST['token']) ? addslashes($_REQUEST['token']) : '';
        if ($token != self::genToken()) {
            jcMsg('权限不足，token error');
        }
    }

}




