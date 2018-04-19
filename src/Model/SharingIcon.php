<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Social\Model
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-social
 */

namespace SilverWare\Social\Model;

use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\ArrayLib;
use SilverStripe\ORM\FieldType\DBField;
use SilverWare\Extensions\Style\LinkColorStyle;
use SilverWare\FontIcons\Extensions\FontIconExtension;
use SilverWare\Model\Component;
use Page;

/**
 * An extension of the component class for a sharing button.
 *
 * @package SilverWare\Social\Model
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-social
 */
class SharingIcon extends Component
{
    /**
     * Define placement constants.
     */
    const PLACEMENT_AUTO   = 'auto';
    const PLACEMENT_TOP    = 'top';
    const PLACEMENT_LEFT   = 'left';
    const PLACEMENT_RIGHT  = 'right';
    const PLACEMENT_BOTTOM = 'bottom';
    
    /**
     * Human-readable singular name.
     *
     * @var string
     * @config
     */
    private static $singular_name = 'Sharing Icon';
    
    /**
     * Human-readable plural name.
     *
     * @var string
     * @config
     */
    private static $plural_name = 'Sharing Icons';
    
    /**
     * Description of this object.
     *
     * @var string
     * @config
     */
    private static $description = 'A component which represents a sharing icon';
    
    /**
     * Icon file for this object.
     *
     * @var string
     * @config
     */
    private static $icon = 'silverware/social: admin/client/dist/images/icons/SharingIcon.png';
    
    /**
     * Defines the table name to use for this object.
     *
     * @var string
     * @config
     */
    private static $table_name = 'SilverWare_SharingIcon';
    
    /**
     * Defines an ancestor class to hide from the admin interface.
     *
     * @var string
     * @config
     */
    private static $hide_ancestor = Component::class;
    
    /**
     * Defines the allowed children for this object.
     *
     * @var array|string
     * @config
     */
    private static $allowed_children = 'none';
    
    /**
     * Defines the extension classes to apply to this object.
     *
     * @var array
     * @config
     */
    private static $extensions = [
        FontIconExtension::class,
        LinkColorStyle::class
    ];
    
    /**
     * Defines the available icon sizes (in pixels).
     *
     * @var array
     * @config
     */
    private static $icon_sizes = [16, 24, 32, 48, 64, 96, 128];
    
    /**
     * Defines the default size of an icon (in pixels).
     *
     * @var integer
     * @config
     */
    private static $default_icon_size = 32;
    
    /**
     * Defines the default placement of an icon popover.
     *
     * @var integer
     * @config
     */
    private static $default_placement = 'auto';
    
    /**
     * Defines the class of sharing button to use for the popover.
     *
     * @var string
     * @config
     */
    private static $button_class;
    
    /**
     * Answers a validator for the CMS interface.
     *
     * @return RequiredFields
     */
    public function getCMSValidator()
    {
        return RequiredFields::create([
            'Title'
        ]);
    }
    
    /**
     * Answers an array of HTML tag attributes for the object.
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = array_merge(
            parent::getAttributes(),
            $this->getDataAttributes()
        );
        
        return $attributes;
    }
    
    /**
     * Answers an array of data attributes for the receiver.
     *
     * @return array
     */
    public function getDataAttributes()
    {
        $attributes = [
            'data-toggle' => 'popover',
            'data-container' => 'body',
            'data-placement' => $this->getPlacement()
        ];
        
        if (($parent = $this->getParent()) && $parent->ShowTitles) {
            $attributes['data-title'] = $this->Title;
        }
        
        $this->extend('updateDataAttributes', $attributes);
        
        return $attributes;
    }
    
    /**
     * Answers an array of class names for the HTML template.
     *
     * @return array
     */
    public function getClassNames()
    {
        $classes = array_merge(
            parent::getClassNames(),
            [
                'link',
                $this->IconSizeClass
            ]
        );
        
        return $classes;
    }
    
    /**
     * Answers the icon size class for the receiver.
     *
     * @return string
     */
    public function getIconSizeClass()
    {
        $size = $this->config()->default_icon_size;
        
        if (($parent = $this->getParent()) && $parent->IconSize) {
            $size = $parent->IconSize;
        }
        
        return sprintf('size-%d', $size);
    }
    
    /**
     * Answers the popover placement for the receiver.
     *
     * @return string
     */
    public function getPlacement()
    {
        if (($parent = $this->getParent()) && $parent->Placement) {
            return $parent->Placement;
        }
        
        return $this->config()->default_placement;
    }
    
    /**
     * Renders the component for the HTML template.
     *
     * @param string $layout Page layout passed from template.
     * @param string $title Page title passed from template.
     *
     * @return DBHTMLText|string
     */
    public function renderSelf($layout = null, $title = null)
    {
        return $this->getController()->customise([
            'Content' => $this->renderContent()
        ])->renderWith(self::class);
    }
    
    /**
     * Renders the content for the HTML template.
     *
     * @return DBHTMLText|string
     */
    public function renderContent()
    {
        // Render Subclass Template:
        
        if ($this->getTemplate() != self::class) {
            return $this->renderWith($this->getTemplate());
        }
        
        // Render Button Instance:
        
        if ($button = $this->getButton()) {
            return DBField::create_field('HTMLFragment', $button->render());
        }
    }
    
    /**
     * Answers an array of options for an icon size field.
     *
     * @return array
     */
    public function getIconSizeOptions()
    {
        return ArrayLib::valuekey($this->config()->icon_sizes);
    }
    
    /**
     * Answers an array of options for a placement field.
     *
     * @return array
     */
    public function getPlacementOptions()
    {
        return [
            self::PLACEMENT_AUTO => _t(__CLASS__ . '.AUTO', 'Auto'),
            self::PLACEMENT_TOP => _t(__CLASS__ . '.TOP', 'Top'),
            self::PLACEMENT_LEFT => _t(__CLASS__ . '.LEFT', 'Left'),
            self::PLACEMENT_RIGHT => _t(__CLASS__ . '.RIGHT', 'Right'),
            self::PLACEMENT_BOTTOM => _t(__CLASS__ . '.BOTTOM', 'Bottom'),
        ];
    }
    
    /**
     * Answers the sharing button instance for the receiver.
     *
     * @return SharingButton
     */
    public function getButton()
    {
        if ($class = $this->config()->button_class) {
            
            // Create Button:
            
            $button = $class::create();
            
            // Extend Button:
            
            $this->extend('updateButton', $button);
            
            // Answer Button:
            
            return $button;
            
        }
    }
}
