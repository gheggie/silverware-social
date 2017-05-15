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
use SilverWare\Extensions\RenderableExtension;
use SilverWare\ORM\MultiClassObject;
use SilverWare\Social\Components\SharingComponent;
use SilverWare\View\GridAware;
use SilverWare\View\Renderable;
use SilverWare\View\ViewClasses;
use Page;

/**
 * An extension of the multi-class object class for a sharing button.
 *
 * @package SilverWare\Social\Model
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-social
 */
class SharingButton extends MultiClassObject
{
    use GridAware;
    use Renderable;
    use ViewClasses;
    
    /**
     * Human-readable singular name.
     *
     * @var string
     * @config
     */
    private static $singular_name = 'Sharing Button';
    
    /**
     * Human-readable plural name.
     *
     * @var string
     * @config
     */
    private static $plural_name = 'Sharing Buttons';
    
    /**
     * Defines the default sort field and order for this object.
     *
     * @var string
     * @config
     */
    private static $default_sort = 'Sort';
    
    /**
     * Maps field names to field types for this object.
     *
     * @var array
     * @config
     */
    private static $db = [
        'Sort' => 'Int',
        'Name' => 'Varchar(128)'
    ];
    
    /**
     * Defines the has-one associations for this object.
     *
     * @var array
     * @config
     */
    private static $has_one = [
        'Parent' => SharingComponent::class
    ];
    
    /**
     * Defines the summary fields of this object.
     *
     * @var array
     * @config
     */
    private static $summary_fields = [
        'Type',
        'Name',
        'Disabled.Nice'
    ];
    
    /**
     * Defines the extension classes to apply to this object.
     *
     * @var array
     * @config
     */
    private static $extensions = [
        RenderableExtension::class
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
        
        // Create Main Fields:
        
        if ($this->isInDB()) {
            
            $fields->addFieldsToTab(
                'Root.Main',
                [
                    TextField::create(
                        'Name',
                        $this->fieldLabel('Name')
                    )
                ]
            );
            
        }
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers a validator for the CMS interface.
     *
     * @return RequiredFields
     */
    public function getCMSValidator()
    {
        return RequiredFields::create([
            'Name'
        ]);
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
        
        $labels['Name'] = _t(__CLASS__ . '.NAME', 'Name');
        
        // Answer Field Labels:
        
        return $labels;
    }
    
    /**
     * Answers the title of the receiver for the CMS interface.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->Name ? $this->Name : parent::getTitle();
    }
    
    /**
     * Answers the link for the sharing button.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->getCurrentPageLink();
    }
    
    /**
     * Answers the current page from the parent component.
     *
     * @return Page
     */
    public function getCurrentPage()
    {
        return $this->Parent()->getCurrentPage(Page::class);
    }
    
    /**
     * Answers the link for the current page.
     *
     * @return string
     */
    public function getCurrentPageLink()
    {
        if ($page = $this->getCurrentPage()) {
            return $page->AbsoluteLink();
        }
    }
    
    /**
     * Answers the title for the current page.
     *
     * @return string
     */
    public function getCurrentPageTitle()
    {
        if ($page = $this->getCurrentPage()) {
            return $page->Title;
        }
    }
    
    /**
     * Answers the default style ID for the HTML template.
     *
     * @return string
     */
    public function getDefaultStyleID()
    {
        return sprintf(
            '%s_%s',
            $this->Parent()->getHTMLID(),
            $this->getClassNameWithID()
        );
    }
    
    /**
     * Renders the object for the HTML template.
     *
     * @param string $layout Page layout passed from template.
     * @param string $title Page title passed from template.
     *
     * @return DBHTMLText|string
     */
    public function renderSelf($layout = null, $title = null)
    {
        return $this->renderWith(static::class);
    }
}
