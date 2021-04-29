<?php

/**
 * An extension of the data object class for a sharing button.
 */
class SharingButton extends DataObject
{
    private static $singular_name = "Button";
    private static $plural_name   = "Buttons";
    
    private static $default_sort = "Sort";
    
    private static $db = array(
        'Sort' => 'Int',
        'Name' => 'Varchar(255)',
        'Disabled' => 'Boolean'
    );
    
    private static $has_one = array(
        'Component' => 'SilverWareComponent'
    );
    
    private static $defaults = array(
        'Disabled' => 0
    );
    
    private static $casting = array(
        'Disabled' => 'Boolean'
    );
    
    private static $summary_fields = array(
        'Type' => 'Type',
        'Name' => 'Name',
        'Disabled.Nice' => 'Disabled'
    );
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Create Field Tab Set:
        
        $fields = FieldList::create(TabSet::create('Root'));
        
        // Create Main Fields:
        
        $fields->addFieldToTab(
            'Root.Main',
            TextField::create(
                'Name',
                _t('SharingButton.NAME', 'Name')
            )
        );
        
        // Create Options Tab:
        
        $fields->findOrMakeTab('Root.Options', _t('SharingButton.OPTIONS', 'Options'));
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            CheckboxField::create(
                'Disabled',
                _t('SharingButton.DISABLED', 'Disabled')
            )
        );
        
        // Extend Field Objects:
        
        $this->extend('updateCMSFields', $fields);
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers a string describing the type of button.
     *
     * @return string
     */
    public function getType()
    {
        return $this->i18n_singular_name();
    }
    
    /**
     * Renders the receiver for the HTML template.
     *
     * @return string
     */
    public function forTemplate()
    {
        return $this->renderWith($this->ClassName);
    }
    
    /**
     * Answers the link for the sharing button.
     *
     * @return string
     */
    public function Link()
    {
        return $this->getCurrentPageLink();
    }
    
    /**
     * Answers true if the receiver is disabled.
     *
     * @return boolean
     */
    public function IsDisabled()
    {
        return $this->getField('Disabled');
    }
    
    /**
     * Answers the current page.
     *
     * @return SiteTree
     */
    public function getCurrentPage()
    {
        return $this->Component()->getCurrentPage('SiteTree');
    }
    
    /**
     * Answers the link for the current page.
     *
     * @return string
     */
    public function getCurrentPageLink()
    {
        if ($Page = $this->getCurrentPage()) {
            return $Page->AbsoluteLink();
        }
    }
    
    /**
     * Answers the title for the current page.
     *
     * @return string
     */
    public function getCurrentPageTitle()
    {
        if ($Page = $this->getCurrentPage()) {
            return $Page->Title;
        }
    }
}
