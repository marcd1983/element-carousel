<?php

namespace Antlion\ElementCarousel\Models;

use Antlion\ElementCarousel\Elements\ElementCarousel;
use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\LinkField\Form\MultiLinkField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;


class CarouselSlide extends DataObject
{
  private static $singular_name = 'carousel slide';
  private static $plural_name = 'carousel slides';
  private static $description = 'A slide for Carousel element';
  private static $table_name = 'CarouselSlide';
  private static $icon = 'font-icon-block-layout';

  private static $db = [
    'Title' => 'Varchar(255)',
    'Content'        => 'HTMLText',
    // 'StartDate'       => 'Date',
    // 'EndDate'         => 'Date',
    'SortOrder'      => 'Int',
  ];

  private static $has_one = [
    'Carousel' => ElementCarousel::class,
    'Image' => Image::class,
    
  ];
  
  private static $has_many = [
     'Links' => Link::class . '.Owner',
  ];

  private static $owns = [
    'Image',
    'Links' 
  ];

  private static $default_sort = 'SortOrder';

   private static $summary_fields = [
        'Image.CMSThumbnail' => 'Image',
        'Title'              => 'Title',
  ];
  public function getCMSFields()
  {
    
    $fields = parent::getCMSFields();
    $fields->removeByName([
      'CarouselID', 
      'SortOrder',
      'LinkID',
      'Link'
      
    ]);
    $fields->addFieldsToTab('Root.Main', [
      TextField::create('Title', 'Title')->setMaxLength(255),
      HTMLEditorField::create('Content', 'Content')->setRows(8),
      UploadField::create('Image', 'Slide image')
                ->setAllowedFileCategories('image/supported')
                ->setFolderName('Uploads/Elements/Carousel'),
    ]);

    if ($this->isInDB()) {
    $fields->addFieldToTab('Root.Main', MultiLinkField::create('Links', 'Button Links'));
    } else {
        $fields->addFieldToTab('Root.Main',
            \SilverStripe\Forms\LiteralField::create('LinksNote',
                '<p class="message notice">Save this slide to add links.</p>')
        );
    }
   
    return $fields;
  }

}