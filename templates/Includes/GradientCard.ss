
<a class="card gradient-card $AlignmentClass" style="color:{$TextColor};<% if $BackgroundColor %>background-color:#{$BackgroundColor};<% end_if %>" <% if $Links.Exists %>href="{$Links.First.URL}" <% if $Links.First.OpenInNew %>target="_blank" rel="noopener noreferrer"<% end_if %><% end_if %>>
    <div aria-hidden="true" class="gradient-card-bg">
        <img alt="$Image.Title.ATT" src="$Image.URL" />
        <div class="gradient-card-gradient"></div>
    </div>
    <div class="card-section gradient-card-section">
        <h3 class="gradient-card-title">$Title</h3>
        <% if $Content %>$Content<% end_if %>
        <% if $Links.Exists %><span class="card-cta button $Links.First.CssClass $Links.First.ExtraClass">$Links.First.Title.XML</span><% end_if %>
    </div>
</a>


