<?php

using( 'App.Code.Web.UI.NPage' );
using( 'App.Code.Models.SiteInfoRecord' );
using( 'App.Code.Web.UI.NDataGridBidirectionalSortTrait' );

/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 01/09/2016
 * Time: 01:53
 */
class SiteInfosPage extends NPage {
	use NDataGridBidirectionalSortTrait;

	public function onLoad( $param ) {
		parent::onLoad( $param );

		if ( ! page()->getIsPostBack() ) {
			$this->loadWithSortAndBind();
		}
	}


	/**
	 * @param TCustomValidator              $sender
	 * @param TServerValidateEventParameter $param
	 */
	public function validateSettingName( $sender, $param ) {
		$param->IsValid = SiteInfoRecord::finder()->findByPk( $this->NameTxt->Text ) === null;
	}

	/**
	 * @param TButton         $sender
	 * @param TEventParameter $param
	 *
	 * @throws NAppException
	 * @throws TActiveRecordException
	 * @throws TInvalidOperationException
	 */
	public function saveNewSettingBtnClicked( $sender, $param ) {
		if ( page()->getIsValid() ) {

			if ( ! $this->checkValue( $this->ValueTxt->SafeText ) ) {
				return;
			}

			$setting = new SiteInfoRecord();

			$setting->name      = $this->NameTxt->Text;
			$setting->value     = $this->ValueTxt->SafeText;
			$setting->editable  = $this->EditableChck->Checked;
			$setting->deletable = $this->DeletableChck->Checked;
			$setting->readable  = $this->ReadableChck->Checked;

			$added = $setting->save();
			if ( $added ) {
				$this->success( 'New Setting added' );
				redirect_page( 'bo.site_infos.SiteInfosPage' );
			} else {
				$this->error( 'Error while adding the new setting' );
			}
		}
	}

	/**
	 * @param $value
	 *
	 * @return bool
	 */
	public function checkValue( $value ) {
		if ( mb_strlen( $value ) == 0 ) {
			$this->error( 'Empty or unsafe text' );

			return false;
		}

		return true;
	}

	/**
	 * @param TButton         $sender
	 * @param TEventParameter $param
	 */
	public function cancelAddNewSettingBtnClicked( $sender, $param ) {
		redirect_page( 'bo.site_infos.SiteInfosPage' );
	}

	/* DataGrid event handlers */
	/**
	 * @param TDataGrid                      $sender
	 * @param TDataGridCommandEventParameter $param
	 *
	 * @throws TActiveRecordException
	 */
	public function onDeleteSelectedSettingCommand( $sender, $param ) {
		$item = $param->getItem();
		$name = $this->SettingsDg->DataKeys[ $item->getItemIndex() ];

		/** @var SiteInfoRecord $setting */
		$setting = SiteInfoRecord::finder()->findByPk( $name );
		if ( ! user()->IsSuperAdmin && ! $setting->canDelete ) {
			$this->error( "Permission : Can't delete the setting" );

			return;
		}
		$deleted = $setting->delete();
		if ( $deleted ) {
			$this->SettingsDg->EditItemIndex = - 1;
			$this->loadWithSortAndBind();
			$this->success( 'Setting deleted successfully' );
		} else {
			$this->error( 'The setting not deleted' );
		}
	}

	/**
	 * @param TDataGrid                   $sender
	 * @param TDataGridItemEventParameter $param
	 */
	public function settingItemCreated( $sender, $param ) {
		$item = $param->getItem();

		if ( in_array( $item->getItemType(), [ TListItemType::EditItem, TListItemType::Item, TListItemType::AlternatingItem ] ) ) {
			$item->DeleteCol->Button->Attributes->onclick = "return confirm('Are you sure ?')";
		}
	}

	/**
	 * @param TDataGrid                      $sender
	 * @param TDataGridCommandEventParameter $param
	 */
	public function editSelectedSettingCommand( $sender, $param ) {
		$this->SettingsDg->EditItemIndex = $param->getItem()->getItemIndex();
		$this->loadWithSortAndBind();
	}

	/**
	 * @param TDataGrid                      $sender
	 * @param TDataGridCommandEventParameter $param
	 *
	 * @throws TActiveRecordException
	 */
	public function saveSelectedSettingCommand( $sender, $param ) {
		$item = $param->getItem();

		if ( ! $this->checkValue( $item->ValueCol->TextBox->SafeText ) ) {
			return;
		}

		$name = $this->SettingsDg->DataKeys[ $item->getItemIndex() ];
		/** @var SiteInfoRecord $setting */
		$setting = SiteInfoRecord::finder()->findByPk( $name );


		$setting->value = $item->ValueCol->TextBox->SafeText;
		if ( user()->isSuperAdmin ) {
			$setting->editable  = $item->EditableCol->CheckBox->Checked;
			$setting->deletable = $item->DeletableCol->CheckBox->Checked;
			$setting->readable  = $item->ReadableCol->CheckBox->Checked;
		}
		$saved = $setting->save();

		if ( $saved ) {
			$this->success( 'Changes saved successfully' );
			$this->SettingsDg->EditItemIndex = - 1;
			$this->loadWithSortAndBind();
		} else {
			$this->warning( 'No changes saved' );
		}
	}

	/**
	 * @param TDataGrid                      $sender
	 * @param TDataGridCommandEventParameter $param
	 */
	public function cancelSelectedSettingCommand( $sender, $param ) {
		$this->SettingsDg->EditItemIndex = - 1;
		$this->loadWithSortAndBind();
	}

	/**
	 * @param TDataGrid                          $sender
	 * @param TDataGridSortCommandEventParameter $param
	 */
	public function onSortSettingsCommand( $sender, $param ) {
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
	 * @param TDataGrid                   $sender
	 * @param TDataGridItemEventParameter $param
	 */
	public function settingItemDataBound( $sender, $param ) {
		$item = $param->getItem();

		if ( in_array( $item->getItemType(), [ TListItemType::Item, TListItemType::AlternatingItem ] ) ) {
			$item->EditCmdCol->EditButton->Visible = user()->IsSuperAdmin || $item->EditableCol->CheckBox->Checked;
		}
		if ( in_array( $item->getItemType(), [ TListItemType::EditItem, TListItemType::Item, TListItemType::AlternatingItem ] ) ) {
			$item->DeleteCol->Button->Visible = user()->IsSuperAdmin || $item->DeletableCol->CheckBox->Checked;
		}
	}

	/**
	 * @param TDataGridSortCommandEventParameter $param
	 */
	private function loadWithSortAndBind( $param = null ) {
		$exp = $this->buildSortExp( $param );
		if ( ! empty( $exp ) ) {
			$this->loadDataAndBind( $exp[0], $exp[1] );
		} else {
			$this->loadDataAndBind();
		}
	}

	/**
	 * @param string $sortColumn
	 * @param string $direction
	 */
	protected function loadDataAndBind( $sortColumn = '', $direction = 'asc' ) {
		$criteria = new TActiveRecordCriteria();
		if ( empty( $sortColumn ) ) {
			$criteria->OrdersBy['name'] = 'asc';
		} else {
			$criteria->OrdersBy[ $sortColumn ] = $direction;
		}
		if ( ! user()->IsSuperAdmin ) {
			$criteria->Condition               = 'readable = :readable ';
			$criteria->Parameters[':readable'] = true;
		}
		$this->SettingsDg->DataSource = SiteInfoRecord::finder()->findAll( $criteria );
		$this->SettingsDg->databind();
	}
	/* End : DataGrid event handlers */


}