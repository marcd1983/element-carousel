<%-- <% require css('antlion/elemental-card:css/card.css') %> --%>
<% if $CardAppearance == 'Gradient' %>
    <% include GradientCard %>
<% else_if $CardAppearance == 'Hover' %>
    <% include HoverCard %>
<% else_if $CardAppearance == 'Horizontal' %>
    <% include HorizontalCard %>
<% else %>
    <% include VerticalCard %>
<% end_if %>
