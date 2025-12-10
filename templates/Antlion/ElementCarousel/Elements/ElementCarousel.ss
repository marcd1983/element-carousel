<% if $Title && $ShowTitle %>
    <% with $HeadingTag %>
        <div class="cell">
          <{$Me} class="element-title">$Up.Title.XML</{$Me}>
        </div>
    <% end_with %>
<% end_if %>
<div class="cell element-carousel">
  <div class="swiper" id="carousel-{$ID}" data-element-carousel>
    <div class="swiper-wrapper">
      <% loop $Slides %>
        <% include ElementCarouselSlide %>
      <% end_loop %>
    </div>

    <% if $Pagination %><div class="swiper-pagination" aria-label="Carousel pagination"></div><% end_if %>
    <% if $Navigation %>
      <button class="swiper-button-prev" aria-label="Previous slide"></button>
      <button class="swiper-button-next" aria-label="Next slide"></button>
    <% end_if %>
    <% if $Scrollbar %><div class="swiper-scrollbar"></div><% end_if %>
  </div>
</div>
<%-- <% require javascript('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js') %> --%>
<%-- <% require javascript('elements/element-carousel:client/js/swiper-bundle.min.js') %> --%>

<%-- <script>
document.addEventListener('DOMContentLoaded', function(){
  var el = document.getElementById('carousel-{$ID}');
  if (!el) return;
  var options = {$CarouselOptionsJSON.RAW};
  new Swiper(el, options);
});
</script> --%>