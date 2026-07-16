<?php

namespace Antlion\ElementCarousel\Elements;

use Antlion\ElementCarousel\Controllers\ElementCarouselController;
use Antlion\ElementCarousel\Models\CarouselSlide;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\FieldGroup;

class ElementCarousel extends BaseElement
{
  private static $table_name = 'ElementCarousel';
  private static $description = 'Carousel';
  private static $singular_name = 'carousel';
  private static $plural_name = 'carousels';
  private static $icon = 'font-icon-block-carousel';

  private static $controller_class = ElementCarouselController::class;

  private static $inline_editable = false;

  private static $db = [
    // 'Height'          => 'Enum("auto,short,medium,tall,full","tall")',
    'CardAppearance'  => 'Enum("Vertical, Horizontal, Hover, Gradient", "Vertical")',
    'Loop'            => 'Boolean',
    'Speed'           => 'Int',
    'SpaceBetween'    => 'Int',
    'SlidesPerView'   => 'Int',   // desktop
    'SlidesPerViewMd' => 'Int',   // tablet
    'SlidesPerViewSm' => 'Int',   // mobile
    'CenteredSlides'  => 'Boolean',
    'FreeMode'        => 'Boolean',
    'Pagination'      => 'Boolean',
    'Navigation'      => 'Boolean',
    'Scrollbar'       => 'Boolean',
    'MouseWheel'      => 'Boolean',
    'Autoplay'        => 'Boolean',
    'AutoplayDelay'   => 'Int',
    'Lazy'            => 'Boolean',
  ];

  public function populateDefaults()
    {
        $this->Speed         = 600;
        $this->SpaceBetween  = 20;
        $this->SlidesPerView        = 5;
        $this->SlidesPerViewMd      = 3;
        $this->SlidesPerViewSm      = 1;
        $this->Pagination    = true;
        $this->Navigation    = true;
        $this->Loop          = true;
        $this->Autoplay      = true;
        $this->AutoplayDelay = 5000;
        parent::populateDefaults();
    }

  private static $has_many = [
    'Slides' => CarouselSlide::class,
  ];

  private static $owns = [
        'Slides',
    ];

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();
        $gridConfig = GridFieldConfig_RelationEditor::create();
        $gridConfig->addComponent(new GridFieldOrderableRows('SortOrder'));
        $gridConfig->removeComponentsByType(GridFieldAddExistingAutocompleter::class);
        $slidesGrid = GridField::create(
            'Content',
            'Content',
            $this->Slides(),
            $gridConfig
        );
        $fields->addFieldToTab('Root.Main', $slidesGrid);
        $fields->removeByName ([
            'CardAppearance',
            'Loop',
            'SortOrder',  
            'ParentID', 
            'Theme', 
            'Align', 
            'OverlayOpacity', 
            'StartDate', 
            'EndDate',
            'Speed',
            'SpaceBetween',
            'SlidesPerView',
            'SlidesPerViewMd',
            'SlidesPerViewSm',
            'CenteredSlides',
            'FreeMode',
            'Pagination',
            'Navigation',
            'Scrollbar',
            'MouseWheel',
            'Autoplay',
            'AutoplayDelay',
            'Lazy',
            'Slides',
            
        ]);
    $fields->addFieldsToTab('Root.Main', [
      DropdownField::create('CardAppearance', 'Card Appearance', [
                'Vertical'   => 'Vertical — image above content',
                'Horizontal' => 'Horizontal — image beside content',
                'Hover'      => 'Hover — overlay on image',
                'Gradient'   => 'Gradient — image with gradient overlay',
            ]),
      ToggleCompositeField::create(
                'SliderSettings',
                'Slider Settings',
                [
                    FieldGroup::create(
                        'Slides Per View', 
                        NumericField::create('SlidesPerView',   'Slides per view (desktop)'),
                        NumericField::create('SlidesPerViewMd', 'Slides per view (tablet ≥ 640px)'),
                        NumericField::create('SlidesPerViewSm', 'Slides per view (mobile < 640px)'),
                    ),
                    NumericField::create('SpaceBetween', 'Space between slides (px)'),
                    CheckboxField::create('Loop', 'Loop'),
                    CheckboxField::create('Pagination', 'Pagination'),
                    CheckboxField::create('Navigation', 'Navigation (prev/next arrows)'),
                    CheckboxField::create('Scrollbar', 'Scrollbar'),
                    CheckboxField::create('MouseWheel', 'MouseWheel'),
                    CheckboxField::create('Lazy', 'Lazy images'),  
                    CheckboxField::create('CenteredSlides', 'Centered slides'),
                    CheckboxField::create('FreeMode', 'Free mode (drag slides)'),
                    
                    CheckboxField::create('Autoplay', 'Autoplay'),
                    NumericField::create('AutoplayDelay', 'Autoplay delay (ms)')
                        ->setDescription('Used only when Autoplay is enabled.'),
                    NumericField::create('Speed', 'Transition speed (ms)'),
                ]
            )->setStartClosed(false)
    ]);

    return $fields;
  }

  public function getType()
  {
    return _t(__class__ . '.BlockType', 'Carousel');
  }

//   public function getSimpleClassName()
//   {
//     return 'element-carousel';
//   }
    /**
     * Build a Swiper options array from the DB config.
     */
    public function getCarouselOptions(): array
    {
        $slidesPerViewSm = (int) ($this->SlidesPerViewSm ?: 1);
        $slidesPerViewMd = (int) ($this->SlidesPerViewMd ?: 2);
        $slidesPerViewLg = (int) ($this->SlidesPerView   ?: 3);
        $slideCount = $this->Slides()->count();

        // Swiper requires enough slides to fill the largest configured view, or loop mode
        // will warn in the console and silently disable/misbehave. Turn it off ourselves
        // instead so behaviour is predictable regardless of how many slides are added.
        $maxSlidesPerView = max($slidesPerViewSm, $slidesPerViewMd, $slidesPerViewLg);
        $loop = (bool) $this->Loop && $slideCount > $maxSlidesPerView;

        $o = [
            'effect'          => 'slide',
            'loop'            => $loop,
            'speed'           => (int)  ($this->Speed ?: 600),
            'spaceBetween' => (int)($this->SpaceBetween ?: 0),
            'centeredSlides' => (bool)$this->CenteredSlides,
            'breakpoints' => [
                0    => ['slidesPerView' => $slidesPerViewSm],
                640  => ['slidesPerView' => $slidesPerViewMd],
                1024 => ['slidesPerView' => $slidesPerViewLg],
            ],
        ];

        if ($this->SlidesPerView) {
            $o['slidesPerView'] = (int) $this->SlidesPerView;
        }

        if ($this->FreeMode) {
            $o['freeMode'] = (bool) $this->FreeMode;
        }

        if ($this->Pagination) {
            $o['pagination'] = [
                'el'        => '.swiper-pagination',
                'clickable' => true,
            ];
        }
        if ($this->Navigation) {
            $o['navigation'] = [
                'nextEl' => '.swiper-button-next',
                'prevEl' => '.swiper-button-prev',
            ];
        }
        if ($this->Scrollbar) {
            $o['scrollbar'] = [
                'el'   => '.swiper-scrollbar',
                'hide' => false,
            ];
        }
        if ($this->MouseWheel) {
            $o['mousewheel'] = (bool) $this->MouseWheel;
        }
        if ($this->Autoplay) {
            $o['autoplay'] = [
                'delay'               => (int)($this->AutoplayDelay ?: 5000),
                'disableOnInteraction'=> false,
                'pauseOnMouseEnter'   => true,
            ];
        }
        if ($this->Lazy) {
            $o['lazy'] = [
                'loadPrevNext' => true,
            ];
        }
        return $o;
    }

    /**
     * JSON for template injection.
     */
    public function getCarouselOptionsJSON(): string
    {
        return json_encode($this->getCarouselOptions(), JSON_UNESCAPED_SLASHES);
    }
}