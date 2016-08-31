<?php
using( 'App.Code.Web.UI.NMasterPage' );

/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 25/08/2016
 * Time: 17:15
 */
class FrontLayout extends NMasterPage {

	/**
	 * @param TLinkButton     $sender
	 * @param TEventParameter $param
	 */
	public function logoutBtnClicked( $sender, $param ) {
		auth()->logout();

		redirect_page( 'bo.users.LoginPage' );

	}
}