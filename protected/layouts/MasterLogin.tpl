<!DOCTYPE html>
<html lang="en">

<com:THead Title="Login">

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

  <link rel="shortcut icon" href="<%~ favicon.ico %>">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->

</com:THead>

<body>

<div class="container">
  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <div class="login-panel panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Please Sign In</h3>
        </div>
        <div class="panel-body">
          <com:TForm Attributes.role="form">
            <fieldset>
              <div class="form-group">
                <com:TRequiredFieldValidator ControlToValidate="UsernameTxt"
                                             ErrorMessage="Required"
                                             Display="Dynamic"/>
                <com:TTextBox ID="UsernameTxt"
                              CssClass="form-control">
                  <prop:Attributes autofocus="" placeholder="Your username"/>
                </com:TTextBox>
              </div>
              <div class="form-group">
                <com:TRequiredFieldValidator ControlToValidate="PasswordTxt"
                                             ErrorMessage="Required"
                                             Display="Dynamic"/>
                <com:TCustomValidator ControlToValidate="PasswordTxt"
                                      ErrorMessage="Invalid passwod"
                                      Display="Dynamic"
                                      OnServerValidate="validateUser"/>
                <com:TTextBox ID="PasswordTxt" TextMode="Password"
                              CssClass="form-control"
                              Attributes.placeholder="Type here your passsword"/>
                <com:TKeyboard ForControl="PasswordTxt"/>
              </div>
              <div class="checkbox">
                <com:TLabel ForControl="RememberMeChck">
                  <com:TCheckBox ID="RememberMeChck" Value="remember-me"/>
                  Remember Me
                </com:TLabel>
              </div>
              <com:TButton
                      Text="Login"
                      CssClass="btn btn-lg btn-success btn-block"
                      OnClick="loginInBtnClicked"/>
            </fieldset>
          </com:TForm>
        </div>
        <div class="panel-footer">
          <a href="<%= page_url('fo.Home') %>"><i class="fa fa-long-arrow-left"></i>&nbsp;Vers Site Web</a>
        </div>
      </div>
    </div>
  </div>
</div>

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