<div class="card hoz-card $AlignmentClass" style="color:{$TextColor};<% if $BackgroundColor %>background-color:#{$BackgroundColor};<% end_if %>">
    <div class="grid-x">
        <div class="cell small-12 medium-5 large-5">
            <% if $Image %>
                <div class="zoom-container">
                    <% if ElementLink %><a href="{$ElementLink.URL}" class="card-img-link" <% if $OpenInNew %>target="_blank"<% end_if %>><% end_if %>
                        <img src="$Image.FocusFill(600,600).URL" class="zoom" alt="$Image.Title.ATT" loading="lazy">
                    <% if ElementLink %></a><% end_if %>
                </div>
            <% end_if %>
        </div>
        <div class="cell small-12 medium-7 large-7">
            <div class="card-section">
                <%-- <% if $Title && $ShowTitle %><$TitleTag class="element__title $TitleSizeClass">$Title</$TitleTag><% end_if %> --%>
                <% if $Title %><h3 class="card-title element__title $TitleSizeClass">$Title</h3><% end_if %>
                <% if $Content %>$Content<% end_if %>                    
                <% if $Links.Exists %>
                    <div class="button-group">
                    <% loop $Links %>
                    <a class="button $CssClass" href="$URL" <% if $OpenInNew %>target="_blank" rel="noopener noreferrer"<% end_if %>>$Title.XML</a>
                    <% end_loop %>
                    </div>
                <% end_if %> 
            </div>
        </div>
    </div>
</div>