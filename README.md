# ElementCarousel

Elemental Carousel block for SilverStripe.

## Install

```bash
composer require antlion/element-carousel
```

## Requirements

PHP 8.1+

SilverStripe ^5 || ^6

dnadesign/silverstripe-elemental ^5 || ^6

## What it does

Adds a Carousel Elemental block you can place on pages.

Uses Swiper.js Slider library `https://swiperjs.com/`

## Usage

Ensure your project uses Elemental on the page type(s) you want.

Template/CSS/JS are intentionally minimal—style or enhance as needed in your theme.

### Add to PageController.php
Ships with Swiper.js assets, but best if you add the asset requirements to PageController

```bash
Requirements::javascript('themes/'your-theme'/js/swiper-bundle.min.js');
Requirements::css('themes/'your-theme'/css/swiper-bundle.min.css');
```

## Templating (optional)

Override the default templates in your project theme by copying and editing:

```bash
templates/Antlion/ElementCarousel/Elements/ElementCarousel.ss

templates/Antlion/ElementCarousel/Includes/ElementCarouselSlide.ss
```

Flush after changing templates by appending ?flush=all to a site URL, e.g.:

```bash
https://yoursite.com/?flush=all
```