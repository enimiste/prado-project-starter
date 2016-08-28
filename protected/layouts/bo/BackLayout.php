<?php
use App\Prado\MasterPage;

/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 25/08/2016
 * Time: 17:15
 */
class BackLayout extends MasterPage {
	/** @var  array */
	protected $_flashs;

	/**
	 * @param $param
	 */
	public function onPreRender( $param ) {
		parent::onPreRender( $param );

		if ( $this->HasFlashMessages ) {
			$this->_flashs                 = $this->FlashMessages;
			$this->FlashMsgRep->DataSource = array_keys( $this->_flashs );
			$this->FlashMsgRep->dataBind();
		}
	}


	/**
	 * @param TButton         $sender
	 * @param TEventParameter $param
	 */
	public function searchBtnClicked( $sender, $param ) {

		$q = $this->SearchTxt->Text;

		redirect_page( 'bo.Dashboard' );
	}

	/**
	 * @param TLinkButton     $sender
	 * @param TEventParameter $param
	 */
	public function logoutBtnClicked( $sender, $param ) {
		auth()->logout();

		redirect_page( 'bo.users.LoginPage' );

	}

	/**
	 * @param TRepeater                   $sender
	 * @param TRepeaterItemEventParameter $param
	 */
	public function onFlashMsgRepItemDataBound( $sender, $param ) {
		/** @var TRepeaterItem $item */
		$item = $param->getItem();

		if ( in_array( $item->getItemType(), [ TListItemType::Item, TListItemType::AlternatingItem ] ) ) {
			$item->FlashMsgCatRep->DataSource = $this->_flashs[ $item->Data ];
			$item->FlashMsgCatRep->dataBind();
		}
	}
}