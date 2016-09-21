<com:TRepeater ID="FlashMsgRep"
               OnItemDataBound="onFlashMsgRepItemDataBound">
  <prop:ItemTemplate>
    <div class="alert-flash-msg alert alert-dismissable alert-<%= $this->Data %>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
      <com:TRepeater ID="FlashMsgCatRep"
                     HeaderTemplate="<ul>">
        <prop:ItemTemplate>
          <li><%= $this->Data %></li>
        </prop:ItemTemplate>
      </com:TRepeater>
    </div>
  </prop:ItemTemplate>
  <prop:FooterTemplate>
    </ul>
    <com:TClientScript>
      $(".alert-flash-msg").fadeTo(2000, 500).slideUp(1000, function () {
      $(".alert-flash-msg").slideUp(1000);
      });
    </com:TClientScript>
  </prop:FooterTemplate>
</com:TRepeater>
