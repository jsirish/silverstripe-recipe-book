<?php

namespace Dynamic\RecipeBook\Page;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\NumericField;
use Symbiote\GridFieldExtensions\GridFieldAddExistingSearchButton;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class RecipeLanding extends \Page
{
    /**
     * @var array
     */
    private static $db = [
        'PerPage' => 'Int',
    ];

    /**
     * @var array
     */
    private static $many_many = [
        'FeaturedCategories' => RecipeCategoryPage::class,
    ];

    /**
     * @var array
     */
    private static $many_many_extraFields = [
        'FeaturedCategories' => [
            'SortOrder' => 'Int',
        ],
    ];

    /**
     * @var array
     */
    private static $defaults = [
        'PerPage' => 9,
    ];

    /**
     * @var string
     */
    private static $singular_name = 'Recipe Landing Page';

    /**
     * @var string
     */
    private static $plural_name = 'Recipe Landing Pages';

    /**
     * @var array
     */
    private static $allowed_children = [
        RecipeCategoryPage::class,
    ];

    /**
     * @var string
     */
    private static $table_name = 'RecipeLanding';

    /**
     * @return \SilverStripe\Forms\FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            if ($this->ID) {
                $config = GridFieldConfig_RelationEditor::create()
                    ->addComponent(new GridFieldOrderableRows('SortOrder'))
                    ->removeComponentsByType(GridFieldAddExistingAutocompleter::class)
                    ->removeComponentsByType(GridFieldAddNewButton::class)
                    ->addComponent(new GridFieldAddExistingSearchButton());
                $cats = $this->FeaturedCategories()->sort('SortOrder');
                $catsField = GridField::create(
                    'FeaturedCategories',
                    'Featured Categories',
                    $cats,
                    $config
                );

                $fields->addFieldsToTab('Root.Featured', array(
                    $catsField,
                ));
            }

            $fields->addFieldsToTab('Root.Browse', [
                NumericField::create('PerPage', 'Categories per page'),
            ]);
        });

        return parent::getCMSFields();
    }

    /**
     * @return mixed
     */
    public function getFeaturedList()
    {
        return $this->FeaturedCategories()->sort('SortOrder');
    }
}
