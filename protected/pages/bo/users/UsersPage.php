<?php
use App\Exception\AppException;
use App\Models\UserRecord;
use App\Prado\DataGridBidirectionalSortTrait;
use App\Prado\Page;

/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 27/08/2016
 * Time: 15:57
 */
class UsersPage extends Page {

	use DataGridBidirectionalSortTrait;

	/**
	 * @param $param
	 */
	public function onLoad( $param ) {
		parent::onLoad( $param );

		if ( ! $this->IsPostBack ) {
			$this->loadWithSortAndBind();
		}
	}


	/**
	 * @param TDataGrid                      $sender
	 * @param TDataGridCommandEventParameter $param
	 *
	 * @throws AppException
	 * @throws TActiveRecordException
	 */
	public function onDeleteSelectedUserCommand( $sender, $param ) {
		$item     = $param->getItem();
		$username = $this->UsersDg->DataKeys[ $item->getItemIndex() ];

		/** @var UserRecord $user */
		$user = UserRecord::finder()->findByPk( $username );
		if ( $username == user()->Name ) {
			throw new AppException( 403, "Can't delete the current connected user" );
		}
		if ( $user->role == 2 ) {
			if ( ! UserRecord::checkSuperAdminInvariant( $username ) ) {
				throw new AppException( 400, "Can't delete all Super Admin." );
			}
		}
		$user->delete();
		$this->UsersDg->EditItemIndex = - 1;
		$this->loadWithSortAndBind();
	}

	/**
	 * @param TDataGrid                   $sender
	 * @param TDataGridItemEventParameter $param
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
	public function editSelectedUserCommand( $sender, $param ) {
		$this->UsersDg->EditItemIndex = $param->getItem()->getItemIndex();
		$this->loadWithSortAndBind();
	}

	/**
	 * @param TDataGrid                      $sender
	 * @param TDataGridCommandEventParameter $param
	 *
	 * @throws AppException
	 */
	public function saveSelectedUserCommand( $sender, $param ) {
		$item     = $param->getItem();
		$username = $this->UsersDg->DataKeys[ $item->getItemIndex() ];
		/** @var UserRecord $user */
		$user = UserRecord::finder()->findByPk( $username );

		if ( $item->RoleCol->DropDownList->SelectedValue != $user->role ) {
			if ( $username == user()->Name ) {
				throw new AppException( 403, "Can't delete the current connected user" );
			}

			if ( $user->role == 2 ) {
				if ( ! UserRecord::checkSuperAdminInvariant( $username ) ) {
					throw new AppException( 400, "Can't delete all Super Admin." );
				}
			}
		}


		$user->first_name = $item->FirstnameCol->TextBox->Text;
		$user->last_name  = $item->LastnameCol->TextBox->Text;
		$user->email      = $item->EmailCol->TextBox->Text;
		$user->role       = $item->RoleCol->DropDownList->SelectedValue;
		$user->save();
		$this->UsersDg->EditItemIndex = - 1;
		$this->loadWithSortAndBind();
	}

	/**
	 * @param TDataGrid                      $sender
	 * @param TDataGridCommandEventParameter $param
	 */
	public function cancelSelectedUserCommand( $sender, $param ) {
		$this->UsersDg->EditItemIndex = - 1;
		$this->loadWithSortAndBind();
	}

	/**
	 * @param string $sortColumn
	 * @param string $direction
	 */
	protected function loadDataAndBind( $sortColumn = '', $direction = 'asc' ) {
		$criteria = new TActiveRecordCriteria();
		if ( empty( $sortColumn ) ) {

			$criteria->OrdersBy['role']     = 'desc';
			$criteria->OrdersBy['username'] = 'asc';
		} else {
			$criteria->OrdersBy[ $sortColumn ] = $direction;
		}
		$this->UsersDg->DataSource = UserRecord::finder()->findAll( $criteria );
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

	/**
	 * @param TDataGrid                          $sender
	 * @param TDataGridSortCommandEventParameter $param
	 */
	public function onSortUsersCommand( $sender, $param ) {
		$this->loadWithSortAndBind( $param );
	}


	/**
	 * @param TDataGrid                    $sender
	 * @param TDataGridPagerEventParameter $param
	 */
	public function onPagerCreated( $sender, $param ) {
		$pager = $param->getPager();

		if ( $pager->getControls()->count() == 1 ) {
			$pager->setVisible( false );
		} else {
			$pager->getControls()->insertAt( 0, 'Pages : ' );
		}
	}

	/**
	 * @param TDataGrid                          $sender
	 * @param TDataGridPageChangedEventParameter $param
	 */
	public function OnPageIndexChanged( $sender, $param ) {
		$this->UsersDg->CurrentPageIndex = $param->getNewPageIndex();

		$this->loadWithSortAndBind();
	}

	/**
	 * @param TDataGridSortCommandEventParameter $param
	 */
	protected function loadWithSortAndBind( $param = null ) {
		$exp = $this->buildSortExp( $param );
		if ( ! empty( $exp ) ) {
			$this->loadDataAndBind( $exp[0], $exp[1] );
		} else {
			$this->loadDataAndBind();
		}
	}
}