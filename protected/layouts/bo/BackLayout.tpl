<!DOCTYPE html>
<html lang="en">

<com:THead>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Bootstrap Core CSS -->
  <link href="<%= site_url('theme/bo/vendor/bootstrap/css/bootstrap.min.css') %>" rel="stylesheet">

  <!-- MetisMenu CSS -->
  <link href="<%= site_url('theme/bo/vendor/metisMenu/metisMenu.min.css') %>" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="<%= site_url('theme/bo/css/sb-admin-2.css') %>" rel="stylesheet">

  <!-- Custom Fonts -->
  <link href="<%= site_url('theme/bo/vendor/font-awesome/css/font-awesome.min.css') %>" rel="stylesheet" type="text/css">

  <link rel="shortcut icon" href="<%~ ../favicon.ico %>">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->

</com:THead>

<body>
<com:TForm>
  <div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<%= page_url('bo.Dashboard') %>">Nickel IT</a>
      </div>
      <!-- /.navbar-header -->

      <ul class="nav navbar-top-links navbar-right">
        <!-- /.dropdown -->
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-user fa-fw"></i> (<%= user()->Fullname %>) <i class="fa fa-caret-down"></i>
          </a>
          <ul class="dropdown-menu dropdown-user">
            <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
            </li>
            <li class="divider"></li>
            <li>
              <com:TLinkButton OnClick="logoutBtnClicked"
                               CausesValidation="false">
                <prop:Text>
                  <i class="fa fa-sign-out fa-fw"></i> Logout
                </prop:Text>
              </com:TLinkButton>
            </li>
          </ul>
          <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
      </ul>
      <!-- /.navbar-top-links -->

      <%include Application.layouts.bo.Menu %>
    </nav>

    <!-- Page Content -->
    <div id="page-wrapper">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <h1 class="page-header">
              <com:TContentPlaceHolder ID="PageHeader">
                <%= $this->Page->Title %>
              </com:TContentPlaceHolder>
            </h1>
            <com:TContentPlaceHolder ID="Main"/>
          </div>
          <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->

  </div>
  <!-- /#wrapper -->
</com:TForm>

<!-- jQuery -->
<script src="<%= site_url('theme/bo/vendor/jquery/jquery.min.js') %>"></script>

<!-- Bootstrap Core JavaScript -->
<script src="<%= site_url('theme/bo/vendor/bootstrap/js/bootstrap.min.js') %>"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="<%= site_url('theme/bo/vendor/metisMenu/metisMenu.min.js') %>"></script>

<!-- Custom Theme JavaScript -->
<script src="<%= site_url('theme/bo/js/sb-admin-2.js') %>"></script>

</body>

</html>