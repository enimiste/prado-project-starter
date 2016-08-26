<?php
use App\Prado\Page;

/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 22/08/2016
 * Time: 13:10
 */
class LoginPage extends Page {

	/**
	 * @param TButton         $sender
	 * @param TEventParameter $param
	 */
	public function signInBtnClicked( $sender, $param ) {
		if ( page()->getIsValid() ) {

			$url = auth()->getReturnUrl();
			if ( empty( $url ) ) {
				$url = 'dashboard';
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