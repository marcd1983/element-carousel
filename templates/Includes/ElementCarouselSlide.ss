
<div class="element-carousel__slide swiper-slide">
<div <% if $title || $Content %>class="card"<% end_if %>>
  <% if $Image %>
    <% if $Top.Lazy %>
      <picture>
        <source media="(min-width:1024px)" data-srcset="<% if function_exists('FocusFill') %>$Image.FocusFill(600,600).URL<% else %>$Image.Fill(600,600).URL<% end_if %>">
        <source media="(max-width:1023px)" data-srcset="<% if function_exists('FocusFill') %>$Image.FocusFill(600,600).URL<% else %>$Image.Fill(600,600).URL<% end_if %>">
        <img class="swiper-lazy"
             data-src="<% if function_exists('FocusFill') %>$Image.FocusFill(600,600).URL<% else %>$Image.ScaleMaxWidth(600).URL<% end_if %>"
             alt="$Image.Title.ATT" width="600" height="600"
             style="width:100%;height:auto;">
      </picture>
      <div class="swiper-lazy-preloader"></div>
    <% else %>
      <picture>
        <source media="(min-width:1024px)" srcset="<% if function_exists('FocusFill') %>$Image.FocusFill(600,600).URL<% else %>$Image.Fill(600,600).URL<% end_if %>">
        <source media="(max-width:1023px)" srcset="<% if function_exists('FocusFill') %>$Image.FocusFill(600,600).URL<% else %>$Image.Fill(600,600).URL<% end_if %>">
        <img src="<% if function_exists('FocusFill') %>$Image.FocusFill(600,600).URL<% else %>$Image.ScaleMaxWidth(600).URL<% end_if %>"
             alt="$Image.Title.ATT" width="600" height="600"
             style="width:100%;height:auto;">
      </picture>
    <% end_if %>
  <% end_if %>
  <% if $title || $Content %>
  <div class="card-section">
    <% if $Title %><h3 class="card-title">$Title</h3><% end_if %>
    $Content
      <% if $Links.Exists %>
        <div class="button-group <% if $Align == 'center' %>align-center<% else_if $Align == 'right' %>align-right<% else %>align-left<% end_if %>">
          <% loop $Links %>
          <a class="button $CssClass" href="$URL" <% if $OpenInNew %>target="_blank" rel="noopener noreferrer"<% end_if %>>$Title.XML</a>
          <% end_loop %>
        </div>
      <% end_if %>    
  </div>
<% end_if %>
</div>
</div>
