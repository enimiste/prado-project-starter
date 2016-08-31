<?php

using( 'System.Security.TUserManager' );

/**
 * TUserManager class
 *
 * TUserManager manages a static list of users {@link TUser}.
 * The user information is specified via module configuration using the following XML syntax,
 * <code>
 * <module id="users" class="System.Security.TUserManager" PasswordMode="Clear">
 *   <user name="Joe" password="demo" />
 *   <user name="John" password="demo" />
 *   <role name="Administrator" users="John" />
 *   <role name="Writer" users="Joe,John" />
 * </module>
 * </code>
 *
 * PHP configuration style:
 * <code>
 * array(
 *   'users' => array(
 *      'class' => 'System.Security.TUserManager',
 *      'properties' => array(
 *         'PasswordMode' => 'Clear',
 *       ),
 *       'users' => array(
 *          array('name'=>'Joe','password'=>'demo'),
 *          array('name'=>'John','password'=>'demo'),
 *       ),
 *       'roles' => array(
 *          array('name'=>'Administrator','users'=>'John'),
 *          array('name'=>'Writer','users'=>'Joe,John'),
 *       ),
 *    ),
 * )
 * </code>
 *
 * In addition, user information can also be loaded from an external file
 * specified by {@link setUserFile UserFile} property. Note, the property
 * only accepts a file path in namespace format. The user file format is
 * similar to the above sample.
 *
 * The user passwords may be specified as clear text, SH1 or MD5 hashed by setting
 * {@link setPasswordMode PasswordMode} as <b>Clear</b>, <b>SHA1</b>, <b>MD5</b> or <b>BCRYPT</b>.
 * The default name for a guest user is <b>Guest</b>. It may be changed
 * by setting {@link setGuestName GuestName} property.
 *
 * TUserManager may be used together with {@link TAuthManager} which manages
 * how users are authenticated and authorized in a Prado application.
 *
 * @author  Nouni El bachir <nouni.elbachir@gmail.com>
 * @packege App.Code.Security
 */
class NUserManager extends \TUserManager {
	/**
	 * @var NUserManagerPasswordMode password mode
	 */
	protected $_passwordMode = NUserManagerPasswordMode::BCRYPT;

	public function setPasswordMode( $value ) {
		$this->_passwordMode = TPropertyValue::ensureEnum( $value, 'NUserManagerPasswordMode' );
	}

	/**
	 * Validates if the username and password are correct.
	 *
	 * @param string $username user name
	 * @param string $password clear password
	 *
	 * @return boolean true if validation is successful, false otherwise.
	 */
	public function validateUser( $username, $password ) {
		if ( $this->_passwordMode === NUserManagerPasswordMode::BCRYPT ) {
			$password = md5( $password );
			$username = strtolower( $username );

			return ( isset( $this->getUsers()[ $username ] ) && $this->getUsers()[ $username ] === $password );
		} else {
			parent::validateUser( $username, $password );
		}

	}


}

/**
 * TUserManagerPasswordMode class.
 * TUserManagerPasswordMode defines the enumerable type for the possible modes
 * that user passwords can be specified for a {@link TUserManager}.
 *
 * The following enumerable values are defined:
 * - Clear: the password is in plain text
 * - MD5: the password is recorded as the MD5 hash value of the original password
 * - SHA1: the password is recorded as the SHA1 hash value of the original password
 * - BCRYPT: the password is recorded as the bcrypt() hash value of the original password
 *
 * @author  Nouni El bachir <nouni.elbachir@gmail.com>
 * @package App.Code.Security
 */
class NUserManagerPasswordMode extends TUserManagerPasswordMode {

	const BCRYPT = 'BCRYPT';
}