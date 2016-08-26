<?php
use App\Prado\MasterPage;

/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 25/08/2016
 * Time: 17:15
 */
class MainLayout extends MasterPage {

	/**
	 * @param TButton         $sender
	 * @param TEventParameter $param
	 */
	public function searchBtnClicked( $sender, $param ) {

		$q = $this->SearchTxt->Text;

		redirect_page( 'home' );
	}

	/**
	 * @param TLinkButton     $sender
	 * @param TEventParameter $param
	 */
	public function logoutBtnClicked( $sender, $param ) {
		auth()->logout();

		redirect_url( 'login' );

	}
}