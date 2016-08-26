<div class="navbar-default sidebar" role="navigation">
  <div class="sidebar-nav navbar-collapse">
    <ul class="nav" id="side-menu">
      <li class="sidebar-search">
        <div class="input-group custom-search-form">
          <com:TTextBox ID="SearchTxt"
                        CssClass="form-control"
                        Attributes.placeholder="Search..."/>
                                <span class="input-group-btn">
                                    <com:TButton CssClass="btn btn-default"
                                                 ButtonTag="Button"
                                                 OnClick="searchBtnClicked"
                                                 CausesValidation="false"
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
      <li>
        <a href="#"><i class="fa fa-users fa-fw"></i> Users<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li>
            <a href="#">New User</a>
          </li>
          <li>
            <a href="#">Edit User</a>
          </li>
          <li>
            <a href="#">All Users</a>
          </li>
        </ul>
        <!-- /.nav-second-level -->
      </li>
      <li>
        <a href="<%= page_url('fo.Home') %>"><i class="fa fa-windows fa-fw"></i> Web Site</a>
      </li>
    </ul>
  </div>
  <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->