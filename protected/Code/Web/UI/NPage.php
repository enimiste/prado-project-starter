<?php

using( 'System.Web.UI.TPage' );

class NPage extends TPage {

	/**
	 * @param string $msg
	 */
	public function error( $msg ) {
		$this->sessionFlashBag( 'danger', $msg );
	}

	/**
	 * @param string $msg
	 */
	public function info( $msg ) {
		$this->sessionFlashBag( 'info', $msg );
	}

	/**
	 * @param string $msg
	 */
	public function success( $msg ) {
		$this->sessionFlashBag( 'success', $msg );
	}

	/**
	 * @param string $msg
	 */
	public function warning( $msg ) {
		$this->sessionFlashBag( 'warning', $msg );
	}

	/**
	 * @param string  $cat
	 * @param  string $msg
	 *
	 * @return array
	 *
	 */
	protected function sessionFlashBag( $cat, $msg ) {
		$flash = [ ];
		if ( session()->contains( '_flash' ) ) {
			$flash = session()['_flash'];
		}

		if ( ! array_key_exists( $cat, $flash ) ) {
			$flash[ $cat ] = [ ];
		}

		$flash[ $cat ][] = $msg;

		session()['_flash'] = $flash;
	}

}