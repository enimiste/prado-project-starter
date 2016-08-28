<?php
use App\Models\UserRecord;
use App\Prado\Page;

/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 27/08/2016
 * Time: 15:57
 */
class UsersPage extends Page {
	/**
	 * @param $param
	 */
	public function onLoad( $param ) {
		parent::onLoad( $param );

		if ( ! $this->IsPostBack ) {
			$this->loadDataAndBind();
		}
	}


	/**
	 * @param TDataGrid                      $sender
	 * @param TDataGridCommandEventParameter $param
	 */
	public function onDeleteSelectedUserCommand( $sender, $param ) {
		$item     = $param->getItem();
		$username = $this->UsersDg->DataKeys[ $item->getItemIndex() ];

		UserRecord::finder()->deleteByPk( $username );
		$this->UsersDg->EditItemIndex = - 1;
		$this->loadDataAndBind();
	}

	/**
	 * @param TDataGrid                      $sender
	 * @param TDataGridCommandEventParameter $param
	 */
	public function userItemCreated( $sender, $param ) {
		$item = $param->getItem();

		if ( $item->getItemType() == TListItemType::EditItem ) {
			$item->FirstnameCol->TextBox->Columns = 13;
			$item->LastnameCol->TextBox->Columns  = 13;
		}

		if ( in_array( $item->getItemType(), [ TListItemType::EditItem, TListItemType::Item, TListItemType::AlternatingItem ] ) ) {
			$item->DeleteCol->Button->Attributes->onclick = "return confirm('Are you sure ?')";
		}
	}

	/**
	 * @param TDataGrid                      $sender
	 * @param TDataGridCommandEventParameter $param
	 */
	public function editSelectedUserBtnClicked( $sender, $param ) {
		$this->UsersDg->EditItemIndex = $param->getItem()->getItemIndex();
		$this->loadDataAndBind();
	}

	/**
	 * @param TDataGrid                      $sender
	 * @param TDataGridCommandEventParameter $param
	 */
	public function saveSelectedUserBtnClicked( $sender, $param ) {
		$item     = $param->getItem();
		$username = $this->UsersDg->DataKeys[ $item->getItemIndex() ];
		/** @var UserRecord $user */
		$user             = UserRecord::finder()->findByPk( $username );
		$user->first_name = $item->FirstnameCol->TextBox->Text;
		$user->last_name  = $item->LastnameCol->TextBox->Text;
		$user->email      = $item->EmailCol->TextBox->Text;
		$user->role       = $item->RoleCol->DropDownList->SelectedValue;
		$user->save();
		$this->UsersDg->EditItemIndex = - 1;
		$this->loadDataAndBind();
	}

	/**
	 * @param TDataGrid                      $sender
	 * @param TDataGridCommandEventParameter $param
	 */
	public function cancelSelectedUserBtnClicked( $sender, $param ) {
		$this->UsersDg->EditItemIndex = - 1;
		$this->loadDataAndBind();
	}

	protected function loadDataAndBind() {
		$criteria                       = new TActiveRecordCriteria();
		$criteria->OrdersBy['role']     = 'desc';
		$criteria->OrdersBy['username'] = 'asc';
		$this->UsersDg->DataSource      = UserRecord::finder()->findAll( $criteria );
		$this->UsersDg->databind();
	}

	/**
	 * @param TButton         $sender
	 * @param TEventParameter $param
	 */
	public function saveNewUserBtnClicked( $sender, $param ) {
		$user = new UserRecord();

		$user->username   = $this->UsernameTxt->Text;
		$user->first_name = $this->FirstnameTxt->Text;
		$user->last_name  = $this->LastnameTxt->Text;
		$user->email      = $this->EmailTxt->Text;
		$user->password   = $this->PasswordTxt->Text;
		$user->role       = $this->RolesDdl->SelectedValue;
		$user->save();

		redirect_page( 'bo.users.UsersPage' );
	}

	/**
	 * @param TCustomValidator              $sender
	 * @param TServerValidateEventParameter $param
	 */
	public function validateUsername( $sender, $param ) {
		$param->IsValid = UserRecord::finder()->findByPk( $param->Value ) === null;
	}
}