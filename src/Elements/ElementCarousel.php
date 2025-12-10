<?php

namespace Antlion\ElementCarousel\Elements;

use Antlion\ElementCarousel\Controllers\ElementCarouselController;
use Antlion\ElementCarousel\Models\CarouselSlide;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
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
        $this->owner->Speed         = 600;
        $this->owner->SpaceBetween  = 20;
        $this->SlidesPerView        = 5;
        $this->SlidesPerViewMd      = 3;
        $this->SlidesPerViewSm      = 1;
        $this->owner->Pagination    = true;
        $this->owner->Navigation    = true;
        $this->owner->Loop          = true;
        $this->owner->Autoplay      = true;
        $this->owner->AutoplayDelay = 5000;
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
        $slidesGrid = GridField::create(
            'Content',
            'Content',
            $this->owner->Slides(),
            $gridConfig
        );
        $fields->addFieldToTab('Root.Main', $slidesGrid);
        $fields->removeByName ([
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
        $o = [
            'effect'          => 'slide',
            'loop'            => (bool) $this->owner->Loop,
            'speed'           => (int)  ($this->owner->Speed ?: 600),
            'spaceBetween' => (int)($this->SpaceBetween ?: 0),
            'centeredSlides' => (bool)$this->owner->CenteredSlides,
            'breakpoints' => [
                0    => ['slidesPerView' => (int)($this->SlidesPerViewSm ?: 1)],
                640  => ['slidesPerView' => (int)($this->SlidesPerViewMd ?: 2)],
                1024 => ['slidesPerView' => (int)($this->SlidesPerView   ?: 3)],
            ],
        ];

        if ($this->owner->SlidesPerView) {
            $o['slidesPerView'] = (int) $this->owner->SlidesPerView;
        }
        
        if ($this->owner->FreeMode) {
            $o['freeMode'] = (bool) $this->owner->FreeMode;
        }

        if ($this->owner->Pagination) {
            $o['pagination'] = [
                'el'        => '.swiper-pagination',
                'clickable' => true,
            ];
        }
        if ($this->owner->Navigation) {
            $o['navigation'] = [
                'nextEl' => '.swiper-button-next',
                'prevEl' => '.swiper-button-prev',
            ];
        }
        if ($this->owner->Scrollbar) {
            $o['scrollbar'] = [
                'el'   => '.swiper-scrollbar',
                'hide' => false,
            ];
        }
        if ($this->owner->MouseWheel) {
            $o['mousewheel'] = (bool) $this->owner->MouseWheel;
        }
        if ($this->owner->Autoplay) {
            $o['autoplay'] = [
                'delay'               => (int)($this->owner->AutoplayDelay ?: 5000),
                'disableOnInteraction'=> false,
                'pauseOnMouseEnter'   => true,
            ];
        }
        if ($this->owner->Lazy) {
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