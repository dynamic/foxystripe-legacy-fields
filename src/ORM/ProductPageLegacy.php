<?php

namespace Dynamic\FoxyStripe\ORM;

use Dynamic\FoxyStripe\Model\ProductImage;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\HeaderField;
use SilverStripe\ORM\DataExtension;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class ProductPageLegacy extends DataExtension
{
    /**
     * @var array
     */
    private static $db = [
        'Featured' => 'Boolean',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'PreviewImage' => Image::class,
    ];

    /**
     * @var array
     */
    private static $has_many = [
        'ProductImages' => ProductImage::class,
    ];

    /**
     * @var array
     */
    private static $searchable_fields = [
        'Featured',
    ];

    /**
     * @param bool $includerelations
     *
     * @return array
     */
    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels();

        $labels['Featured.Nice'] = _t('ProductPage.NiceLabel', 'Featured');

        return $labels;
    }

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // allow extensions of ProductPage to override the PreviewImage field description
        $previewDescription = ($this->owner->config()->get('customPreviewDescription')) ?
            $this->config()->get('customPreviewDescription') :
            _t(
                'ProductPage.PreviewImageDescription',
                'Image used throughout site to represent this product'
            );

        // Product Images gridfield
        $config = GridFieldConfig_RelationEditor::create();
        $config->addComponent(new GridFieldOrderableRows('SortOrder'));
        $prodImagesField = GridField::create(
            'ProductImages',
            _t('ProductPage.ProductImages', 'Images'),
            $this->owner->ProductImages(),
            $config
        );

        // Images tab
        $fields->addFieldsToTab('Root.Images', [
            HeaderField::create('MainImageHD', _t('ProductPage.MainImageHD', 'Product Image'), 2),
            UploadField::create('PreviewImage', '')
                ->setDescription($previewDescription)
                ->setFolderName('Uploads/Products')
                ->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']),
            HeaderField::create('ProductImagesHD', _t('ProductPage.ProductImagesHD', 'Product Image Gallery'), 2),
            $prodImagesField
                ->setDescription(_t(
                    'ProductPage.ProductImagesDescription',
                    'Additional Product Images, shown in gallery on Product page'
                )),
        ]);

        $fields->addFieldsToTab('Root.Details', [
            CheckboxField::create('Featured')
                ->setTitle(_t('ProductPage.Featured', 'Featured Product')),
        ]);
    }
}
