<div class="navbar-default sidebar" role="navigation">
  <div class="sidebar-nav navbar-collapse">
    <ul class="nav" id="side-menu">
      <li class="sidebar-search">
        <div class="input-group custom-search-form">
          <com:TRequiredFieldValidator ControlToValidate="SearchTxt"
                                       ValidationGroup="master_search"
                                       Display="Dynamic"/>
          <com:TTextBox ID="SearchTxt"
                        CssClass="form-control"
                        ValidationGroup="master_search"
                        Attributes.placeholder="Search..."/>
                                <span class="input-group-btn">
                                    <com:TButton CssClass="btn btn-default"
                                                 ButtonTag="Button"
                                                 OnClick="searchBtnClicked"
                                                 ValidationGroup="master_search"
                                    >
                                        <i class="fa fa-search"></i>
                                    </com:TButton>
                                </span>
        </div>
        <!-- /input-group -->
      </li>
      <li>
        <a href="<%= page_url('bo.Dashboard') %>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
      </li>
      <com:TControl Visible="<%= user()->IsSuperAdmin %>">
        <li>
          <a href="<%= page_url('bo.users.UsersPage') %>"><i class="fa fa-users fa-fw"></i> Users</a>
          <!-- /.nav-second-level -->
        </li>
      </com:TControl>
      <com:TControl Visible="<%= user()->IsAdmin %>">
        <li>
          <a href="<%= page_url('bo.site_infos.SiteInfosPage') %>"><i class="fa fa-th-list fa-fw"></i> Site Infos</a>
          <!-- /.nav-second-level -->
        </li>
      </com:TControl>
      <li>
        <a href="<%= page_url('fo.Home') %>"><i class="fa fa-windows fa-fw"></i> Web Site</a>
      </li>
    </ul>
  </div>
  <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->