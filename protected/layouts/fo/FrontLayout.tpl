<!DOCTYPE html>
<html lang="en">

<com:THead>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

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
  <com:TContentPlaceHolder ID="Main"/>
  <footer>
    <a href="<%= page_url('bo.Dashboard') %>">Espace d'administration</a>
    <com:TControl Visible="<%= !is_guest() %>">
      &nbsp;|&nbsp;
      Connecté : <%= user()->Fullname %> (
      <com:TLinkButton Text="Logout"
                       OnClick="logoutBtnClicked"
                       CausesValidation="false">
      </com:TLinkButton>
      )
    </com:TControl>
  </footer>
</com:TForm>
</body>

</html>