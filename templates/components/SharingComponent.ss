<% if $EnabledButtons %>
  <ul class="buttons $ButtonLayout">
    <% loop $EnabledButtons %>
      <li class="button">$Me</li>
    <% end_loop %>
  </ul>
<% end_if %>
