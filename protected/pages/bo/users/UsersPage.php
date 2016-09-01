<?php
using( 'App.Code.Exceptions.NAppException' );
using( 'App.Code.Models.UserRecord' );
using( 'App.Code.Web.UI.NDataGridBidirectionalSortTrait' );
using( 'App.Code.Web.UI.NPage' );


/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 27/08/2016
 * Time: 15:57
 */
class UsersPage extends NPage {

	use NDataGridBidirectionalSortTrait;

	/**
	 * @param $param
	 */
	public function onLoad( $param ) {
		parent::onLoad( $param );

		if ( ! $this->IsPostBack ) {
			$this->loadWithSortAndBind();

			$this->hidePwdUpdatePanel();
		}
	}


	/**
	 * @param TDataGrid                      $sender
	 * @param TDataGridCommandEventParameter $param
	 *
	 * @throws NAppException
	 * @throws TActiveRecordException
	 */
	public function onDeleteSelectedUserCommand( $sender, $param ) {
		$item     = $param->getItem();
		$username = $this->UsersDg->DataKeys[ $item->getItemIndex() ];

		/** @var UserRecord $user */
		$user = UserRecord::finder()->findByPk( $username );
		if ( $username == user()->Name ) {
			$this->error( "Can't delete the current connected user" );

			return;
		}
		if ( $user->role == 2 ) {
			if ( ! UserRecord::checkSuperAdminInvariant( $username ) ) {
				$this->error( "Can't delete all Super Admin." );

				return;
			}
		}
		$deleted = $user->delete();
		if ( $deleted ) {
			$this->UsersDg->EditItemIndex = - 1;
			$this->loadWithSortAndBind();
			$this->success( 'User deleted successfully' );
		} else {
			$this->error( 'The user not deleted' );
		}
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
	 * @throws NAppException
	 */
	public function saveSelectedUserCommand( $sender, $param ) {
		$item     = $param->getItem();
		$username = $this->UsersDg->DataKeys[ $item->getItemIndex() ];
		/** @var UserRecord $user */
		$user = UserRecord::finder()->findByPk( $username );

		if ( $item->RoleCol->DropDownList->SelectedValue != $user->role ) {
			if ( $username == user()->Name ) {
				$this->error( "Can't delete the current connected user" );

				return;
			}

			if ( $user->role == 2 ) {
				if ( ! UserRecord::checkSuperAdminInvariant( $username ) ) {
					$this->error( "Can't delete all Super Admin." );

					return;
				}
			}
		}


		$user->first_name = $item->FirstnameCol->TextBox->Text;
		$user->last_name  = $item->LastnameCol->TextBox->Text;
		$user->email      = $item->EmailCol->TextBox->Text;
		$user->role       = $item->RoleCol->DropDownList->SelectedValue;
		$saved            = $user->save();

		if ( $saved ) {
			$this->success( 'Changes saved successfully' );
			$this->UsersDg->EditItemIndex = - 1;
			$this->loadWithSortAndBind();
		} else {
			$this->warning( 'No changes saved' );
		}
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
		if ( $this->IsValid ) {
			$user = new UserRecord();

			$user->username   = $this->UsernameTxt->Text;
			$user->first_name = $this->FirstnameTxt->Text;
			$user->last_name  = $this->LastnameTxt->Text;
			$user->email      = $this->EmailTxt->Text;
			$user->password   = bcrypt( $this->PasswordTxt->Text );
			$user->role       = $this->RolesDdl->SelectedValue;
			$added            = $user->save();

			if ( $added ) {
				$this->info( 'New user added successfully' );
				redirect_page( 'bo.users.UsersPage' );
			} else {
				$this->error( 'User not added' );
			}
		}
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

	/**
	 * @param TDataGrid                      $sender
	 * @param TDataGridCommandEventParameter $param
	 */
	public function onActionSelectedUserCommand( $sender, $param ) {
		if ( $param->getCommandName() == 'password' ) {
			$this->PwdUpdatePanel->Visible  = true;
			$item                           = $param->getItem();
			$this->UsernameToUpdatePassword = $this->UsersDg->DataKeys[ $item->getItemIndex() ];
		}
	}

	/**
	 * @param TButton         $sender
	 * @param TEventParameter $param
	 */
	public function cancelPwdUpdateBtnClicked( $sender, $param ) {
		$this->hidePwdUpdatePanel();
	}

	/**
	 * @param TButton         $sender
	 * @param TEventParameter $param
	 */
	public function savePasswordChangesBtnClicked( $sender, $param ) {
		if ( $this->IsValid ) {
			$pwd      = $this->NewPasswordTxt->Text;
			$username = $this->UsernameToUpdatePassword;
			/** @var UserRecord $user */
			$user           = UserRecord::finder()->findByPk( $username );
			$user->password = bcrypt( $pwd );
			$saved          = $user->save();
			if ( $saved ) {
				$this->success( $username . ' Password has been update successfully' );
				$this->hidePwdUpdatePanel();
				redirect_page( 'bo.users.UsersPage' );
			} else {
				$this->error( 'No changes saved' );
			}
		}
	}

	/**
	 * @return string
	 */
	public function getUsernameToUpdatePassword() {
		return $this->getViewState( 'UsernameToUpdatePassword', null );
	}

	/**
	 * @param string $username
	 */
	public function setUsernameToUpdatePassword( $username ) {
		$this->setViewState( 'UsernameToUpdatePassword', $username );
	}

	protected function hidePwdUpdatePanel() {
		$this->PwdUpdatePanel->Visible  = false;
		$this->UsernameToUpdatePassword = null;
	}

	/**
	 * @param TButton         $sender
	 * @param TEventParameter $param
	 */
	public function cancelAddNewUserBtnClicked( $sender, $param ) {
		redirect_page( 'bo.users.UsersPage' );
	}
}