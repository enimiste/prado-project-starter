<?php

using( 'App.Code.Web.UI.NTemplateControl' );

/**
 * Class NMasterPage
 * @package App.Code.Web.UI
 */
abstract class NMasterPage extends NTemplateControl {


	/**
	 * NB :
	 * Empty messages and empty categories are removed from the returned array
	 *
	 * @return array of array ['danger'=>[],'info'=>[],'success'=>[],'warning'=>[]]
	 */
	public function getFlashMessages() {
		if ( ! session()->contains( '_flash' ) ) {
			return [ ];
		}
		$bags               = session()['_flash'];
		session()['_flash'] = [ ];

		return array_filter( array_map( function ( array $cat ) {
			return array_filter( $cat,
				function ( $msg ) {
					$msg = trim( $msg );

					return mb_strlen( $msg ) > 0;//remove empty messages
				} );
		},
			$bags ),
			function ( array $bag ) {
				return ! empty( $bag );//remove empty categories
			} );
	}

	/**
	 * @return bool
	 */
	public function getHasFlashMessages() {
		return ! empty( session()['_flash'] );
	}
}