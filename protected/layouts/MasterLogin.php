<?php

using( 'App.Code.Web.UI.NPage' );

/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 22/08/2016
 * Time: 13:10
 */
class MasterLogin extends NPage {

	/**
	 * @param TButton         $sender
	 * @param TEventParameter $param
	 */
	public function loginInBtnClicked( $sender, $param ) {
		if ( page()->getIsValid() ) {

			$url = auth()->getReturnUrl();
			if ( empty( $url ) ) {
				if ( user()->IsAdmin ) {
					$url = page_url( 'bo.Dashboard' );
				} else {
					$url = 'home';
				}
			}

			redirect_url( $url );
		}
	}

	/**
	 * @param TCustomValidator              $sender
	 * @param TServerValidateEventParameter $param
	 */
	public function validateUser( $sender, $param ) {

		$param->IsValid = auth()->login( $this->UsernameTxt->Text, $this->PasswordTxt->Text );
	}
}