<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Social\Items
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-social
 */

namespace SilverWare\Social\Items;

use SilverStripe\CMS\Model\ErrorPage;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverWare\Forms\FieldSection;
use SilverWare\Navigation\Model\BarItem;
use SilverWare\Social\Model\SharingIcon;
use Page;

/**
 * An extension of the bar item class for a sharing item.
 *
 * @package SilverWare\Social\Items
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-social
 */
class SharingItem extends BarItem
{
    /**
     * Human-readable singular name.
     *
     * @var string
     * @config
     */
    private static $singular_name = 'Sharing Item';
    
    /**
     * Human-readable plural name.
     *
     * @var string
     * @config
     */
    private static $plural_name = 'Sharing Items';
    
    /**
     * Description of this object.
     *
     * @var string
     * @config
     */
    private static $description = 'A bar item to show a series of sharing icons';
    
    /**
     * Defines the table name to use for this object.
     *
     * @var string
     * @config
     */
    private static $table_name = 'SilverWare_SharingItem';
    
    /**
     * Defines an ancestor class to hide from the admin interface.
     *
     * @var string
     * @config
     */
    private static $hide_ancestor = BarItem::class;
    
    /**
     * Maps field names to field types for this object.
     *
     * @var array
     * @config
     */
    private static $db = [
        'IconSize' => 'Int',
        'Placement' => 'Varchar(16)',
        'ShowTitles' => 'Boolean'
    ];
    
    /**
     * Defines the default values for the fields of this object.
     *
     * @var array
     * @config
     */
    private static $defaults = [
        'IconSize' => 32,
        'ShowTitles' => 0
    ];
    
    /**
     * Defines the allowed children for this object.
     *
     * @var array|string
     * @config
     */
    private static $allowed_children = [
        SharingIcon::class
    ];
    
    /**
     * Defines the page classes to disable the object by default.
     *
     * @var array
     * @config
     */
    private static $disabled_on = [
        ErrorPage::class
    ];
    
    /**
     * Answers a list of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Define Placeholder:
        
        $placeholder = _t(__CLASS__ . '.DROPDOWNDEFAULT', '(default)');
        
        // Create Style Fields:
        
        $fields->addFieldsToTab(
            'Root.Style',
            [
                FieldSection::create(
                    'IconStyle',
                    $this->fieldLabel('IconStyle'),
                    [
                        DropdownField::create(
                            'IconSize',
                            $this->fieldLabel('IconSize'),
                            SharingIcon::singleton()->getIconSizeOptions()
                        ),
                        DropdownField::create(
                            'Placement',
                            $this->fieldLabel('Placement'),
                            SharingIcon::singleton()->getPlacementOptions()
                        )->setEmptyString(' ')->setAttribute('data-placeholder', $placeholder)
                    ]
                )
            ]
        );
        
        // Create Options Fields:
        
        $fields->addFieldsToTab(
            'Root.Options',
            [
                FieldSection::create(
                    'IconOptions',
                    $this->fieldLabel('IconOptions'),
                    [
                        CheckboxField::create(
                            'ShowTitles',
                            $this->fieldLabel('ShowTitles')
                        )
                    ]
                )
            ]
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers the labels for the fields of the receiver.
     *
     * @param boolean $includerelations Include labels for relations.
     *
     * @return array
     */
    public function fieldLabels($includerelations = true)
    {
        // Obtain Field Labels (from parent):
        
        $labels = parent::fieldLabels($includerelations);
        
        // Define Field Labels:
        
        $labels['IconSize'] = _t(__CLASS__ . '.ICONSIZEINPIXELS', 'Icon size (in pixels)');
        $labels['Placement'] = _t(__CLASS__ . '.POPOVERPLACEMENT', 'Popover placement');
        $labels['ShowTitles'] = _t(__CLASS__ . '.SHOWPOPOVERTITLES', 'Show popover titles');
        $labels['IconStyle'] = $labels['IconOptions'] = _t(__CLASS__ . '.ICON', 'Icon');
        
        // Answer Field Labels:
        
        return $labels;
    }
    
    /**
     * Answers an array of list class names for the HTML template.
     *
     * @return array
     */
    public function getListClassNames()
    {
        $classes = [
            'icons',
            'show-icons',
            'hide-text'
        ];
        
        $this->extend('updateListClassNames', $classes);
        
        return $classes;
    }
    
    /**
     * Answers an array of list item class names for the HTML template.
     *
     * @return array
     */
    public function getListItemClassNames()
    {
        $classes = ['icon'];
        
        $this->extend('updateListItemClassNames', $classes);
        
        return $classes;
    }
    
    /**
     * Answers a list of all icons within the receiver.
     *
     * @return DataList
     */
    public function getIcons()
    {
        return $this->getAllChildren();
    }
    
    /**
     * Answers a list of the enabled icons within the receiver.
     *
     * @return ArrayList
     */
    public function getEnabledIcons()
    {
        return $this->getIcons()->filterByCallback(function ($icon) {
            return $icon->isEnabled();
        });
    }
    
    /**
     * Answers true if the object is disabled within the template.
     *
     * @return boolean
     */
    public function isDisabled()
    {
        // Disable (if no enabled icons):
        
        if (!$this->getEnabledIcons()->exists()) {
            return true;
        }
        
        // Disable (if no current page):
        
        if (!($page = $this->getCurrentPage(Page::class))) {
            return true;
        }
        
        // Disable (if disabled for page class):
        
        if (in_array(get_class($page), $this->config()->disabled_on)) {
            return true;
        }
        
        // Enable (if sharing is not disabled for page):
        
        return !$page->isSharingDisabled() ? parent::isDisabled() : true;
    }
}
