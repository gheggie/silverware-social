<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Social\Components
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-social
 */

namespace SilverWare\Social\Components;

use SilverStripe\CMS\Model\ErrorPage;
use SilverStripe\Forms\DropdownField;
use SilverWare\Components\BaseComponent;
use SilverWare\Forms\FieldSection;
use SilverWare\Social\Model\SharingButton;
use Page;

/**
 * An extension of the base component class for a sharing component.
 *
 * @package SilverWare\Social\Components
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-social
 */
class SharingComponent extends BaseComponent
{
    /**
     * Define layout constants.
     */
    const LAYOUT_VERTICAL   = 'vertical';
    const LAYOUT_HORIZONTAL = 'horizontal';
    
    /**
     * Human-readable singular name.
     *
     * @var string
     * @config
     */
    private static $singular_name = 'Sharing Component';
    
    /**
     * Human-readable plural name.
     *
     * @var string
     * @config
     */
    private static $plural_name = 'Sharing Components';
    
    /**
     * Description of this object.
     *
     * @var string
     * @config
     */
    private static $description = 'A component which shows a series of sharing buttons for the current page';
    
    /**
     * Icon file for this object.
     *
     * @var string
     * @config
     */
    private static $icon = 'silverware-social/admin/client/dist/images/icons/SharingComponent.png';
    
    /**
     * Defines an ancestor class to hide from the admin interface.
     *
     * @var string
     * @config
     */
    private static $hide_ancestor = BaseComponent::class;
    
    /**
     * Maps field names to field types for this object.
     *
     * @var array
     * @config
     */
    private static $db = [
        'ButtonLayout' => 'Varchar(16)'
    ];
    
    /**
     * Defines the default values for the fields of this object.
     *
     * @var array
     * @config
     */
    private static $defaults = [
        'ButtonLayout' => 'horizontal'
    ];
    
    /**
     * Defines the allowed children for this object.
     *
     * @var array|string
     * @config
     */
    private static $allowed_children = [
        SharingButton::class
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
        
        // Create Style Fields:
        
        $fields->addFieldToTab(
            'Root.Style',
            FieldSection::create(
                'SharingStyle',
                $this->fieldLabel('SharingStyle'),
                [
                    DropdownField::create(
                        'ButtonLayout',
                        $this->fieldLabel('ButtonLayout'),
                        $this->getButtonLayoutOptions()
                    )
                ]
            )
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
        
        $labels['ButtonLayout'] = _t(__CLASS__ . '.BUTTONLAYOUT', 'Button layout');
        $labels['SharingStyle'] = _t(__CLASS__ . '.SHARING', 'Sharing');
        
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
        $classes = ['buttons', $this->ButtonLayout];
        
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
        $classes = ['button'];
        
        $this->extend('updateListItemClassNames', $classes);
        
        return $classes;
    }
    
    /**
     * Answers a list of all buttons within the receiver.
     *
     * @return DataList
     */
    public function getButtons()
    {
        return $this->AllChildren();
    }
    
    /**
     * Answers a list of the enabled buttons within the receiver.
     *
     * @return ArrayList
     */
    public function getEnabledButtons()
    {
        return $this->getButtons()->filterByCallback(function ($button) {
            return $button->isEnabled();
        });
    }
    
    /**
     * Answers true if the object is disabled within the template.
     *
     * @return boolean
     */
    public function isDisabled()
    {
        // Disable (if no enabled buttons):
        
        if (!$this->getEnabledButtons()->exists()) {
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
    
    /**
     * Answers an array of options for the button layout field.
     *
     * @return array
     */
    public function getButtonLayoutOptions()
    {
        return [
            self::LAYOUT_VERTICAL   => _t(__CLASS__ . '.VERTICAL', 'Vertical'),
            self::LAYOUT_HORIZONTAL => _t(__CLASS__ . '.HORIZONTAL', 'Horizontal')
        ];
    }
}
