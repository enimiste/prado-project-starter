<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 25/08/2016
 * Time: 17:56
 */
use App\Models\UserRecord;

class AppUser extends \TDbUser {

	protected $fullname;

	/**
	 * Validates if username and password are correct entries.
	 * Usually, this is accomplished by checking if the user database
	 * contains this (username, password) pair.
	 * You may use {@link getDbConnection DbConnection} to deal with database.
	 *
	 * @param string $username (case-sensitive)
	 * @param string $password
	 *
	 * @return boolean whether the validation succeeds
	 */
	public function validateUser( $username, $password ) {
		return UserRecord::finder()->findBy_username_AND_password( $username, $password ) != null;
	}

	/**
	 * Creates a new user instance given the username.
	 * This method usually needs to retrieve necessary user information
	 * (e.g. role, name, rank, etc.) from the user database according to
	 * the specified username. The newly created user instance should be
	 * initialized with these information.
	 *
	 * If the username is invalid (not found in the user database), null
	 * should be returned.
	 *
	 * You may use {@link getDbConnection DbConnection} to deal with database.
	 *
	 * @param string $username (case-sensitive)
	 *
	 * @return \TDbUser the newly created and initialized user instance
	 */
	public function createUser( $username ) {
		$user = UserRecord::finder()->findByPk( $username );
		if ( $user instanceof UserRecord ) {
			$buser           = new self( $this->Manager );
			$buser->Name     = $username;
			$buser->Fullname = $user->first_name . ' ' . $user->last_name;
			$buser->Roles    = $this->getRolesCodes()[ $user->role ];
			$buser->IsGuest  = false;

			return $buser;
		}

		return null;
	}

	/**
	 * @return array
	 */
	public function getRolesCodes() {
		return [
			0 => 'user',
			1 => 'admin',
			2 => 'sadmin',
		];
	}

	/**
	 * @return bool
	 */
	public function getIsAdmin() {
		return $this->isInRole( 'admin' );
	}

	/**
	 * @return bool
	 */
	public function getIsSuperAdmin() {
		return $this->isInRole( 'sadmin' );
	}

	/**
	 * @return mixed
	 */
	public function getFullname() {
		return $this->getState( 'Fullname', '' );
	}

	/**
	 * @param mixed $value
	 */
	public function setFullname( $value ) {
		$this->setState( 'Fullname', $value, '' );
	}
}