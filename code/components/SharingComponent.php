<?php

/**
 * An extension of the base component class for a sharing component.
 */
class SharingComponent extends BaseComponent
{
    private static $singular_name = "Sharing Component";
    private static $plural_name   = "Sharing Components";
    
    private static $description = "Shows a series of social sharing buttons for the current page";
    
    private static $icon = "silverware-social/images/icons/SharingComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $db = array(
        'ButtonLayout' => "Enum('vertical, horizontal', 'horizontal')",
        'ButtonMarginRight' => 'Varchar(16)',
        'ButtonMarginRightUnit' => "Enum('px, em, rem, pt, cm, in', 'rem')",
        'ButtonMarginBottom' => 'Varchar(16)',
        'ButtonMarginBottomUnit' => "Enum('px, em, rem, pt, cm, in', 'rem')",
    );
    
    private static $has_many = array(
        'Buttons' => 'SharingButton'
    );
    
    private static $defaults = array(
        'ButtonLayout' => 'horizontal',
        'ButtonMarginRight' => 2,
        'ButtonMarginRightUnit' => 'rem',
        'ButtonMarginBottom' => 1,
        'ButtonMarginBottomUnit' => 'rem'
    );
    
    private static $required_themed_css = array(
        'sharing-component'
    );
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Insert Buttons Tab:
        
        $fields->insertAfter(
            Tab::create(
                'Buttons',
                _t('SharingComponent.BUTTONS', 'Buttons')
            ),
            'Main'
        );
        
        // Add Buttons Grid Field to Tab:
        
        $fields->addFieldToTab(
            'Root.Buttons',
            GridField::create(
                'Buttons',
                _t('SharingComponent.BUTTONS', 'Buttons'),
                $this->Buttons(),
                GridFieldConfig_MultiClassEditor::create()->useDescendantsOf('SharingButton')
            )
        );
        
        // Create Style Fields:
        
        $fields->addFieldToTab(
            'Root.Style',
            ToggleCompositeField::create(
                'SharingComponentStyle',
                $this->i18n_singular_name(),
                array(
                    FieldGroup::create(
                        _t('SharingComponent.BUTTONMARGINRIGHT', 'Button margin right'),
                        array(
                            TextField::create(
                                'ButtonMarginRight',
                                ''
                            )->setAttribute('placeholder', _t('SharingComponent.MARGIN', 'Margin')),
                            DropdownField::create(
                                'ButtonMarginRightUnit',
                                '',
                                $this->owner->dbObject('ButtonMarginRightUnit')->enumValues()
                            )
                        )
                    ),
                    FieldGroup::create(
                        _t('SharingComponent.BUTTONMARGINBOTTOM', 'Button margin bottom'),
                        array(
                            TextField::create(
                                'ButtonMarginBottom',
                                ''
                            )->setAttribute('placeholder', _t('SharingComponent.MARGIN', 'Margin')),
                            DropdownField::create(
                                'ButtonMarginBottomUnit',
                                '',
                                $this->owner->dbObject('ButtonMarginBottomUnit')->enumValues()
                            )
                        )
                    )
                )
            )
        );
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'SharingComponentOptions',
                $this->i18n_singular_name(),
                array(
                    DropdownField::create(
                        'ButtonLayout',
                        _t('SharingComponent.BUTTONLAYOUT', 'Button layout'),
                        $this->dbObject('ButtonLayout')->enumValues()
                    )
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers the CSS string for the margin-right style.
     *
     * @return string
     */
    public function getButtonMarginRightCSS()
    {
        if ($this->ButtonMarginRight != '') {
            return $this->ButtonMarginRight . $this->ButtonMarginRightUnit;
        }
    }

    /**
     * Answers the CSS string for the margin-bottom style.
     *
     * @return string
     */
    public function getButtonMarginBottomCSS()
    {
        if ($this->ButtonMarginBottom != '') {
            return $this->ButtonMarginBottom . $this->ButtonMarginBottomUnit;
        }
    }
    
    /**
     * Answers the appropriate display type based on the button layout of the receiver.
     *
     * @return string
    */
    public function getButtonDisplayType()
    {
        return $this->ButtonLayout == 'vertical' ? 'block' : 'inline-block';
    }
    
    /**
     * Answers a list of the enabled buttons within the receiver.
     *
     * @return ArrayList
     */
    public function EnabledButtons()
    {
        $buttons = array();
        
        foreach ($this->Buttons() as $button) {
            
            if (!$button->IsDisabled()) {
                $buttons[] = $button;
            }
            
        }
        
        return ArrayList::create($buttons);
    }
    
    /**
     * Answers true to disable the component if the current page is not shareable.
     *
     * @return boolean
     */
    public function Disabled()
    {
        if (!$this->EnabledButtons()->count()) {
            return true;
        }
        
        if (($Page = $this->getCurrentPage('SiteTree')) && !($Page instanceof ErrorPage)) {
            
            if (!$Page->SharingDisabled) {
                return $this->getField('Disabled');
            }
            
        }
        
        return true;
    }
    
    /**
     * Loads the CSS and scripts required by the receiver.
     */
    public function getRequirements()
    {
        // Load Parent Requirements:
        
        parent::getRequirements();
        
        // Load Button Requirements:
        
        $this->EnabledButtons()->each(function ($button) {
            $button->getRequirements();
        });
    }
}

/**
 * An extension of the base component controller class for a sharing component.
 */
class SharingComponent_Controller extends BaseComponent_Controller
{
    /**
     * Defines the URLs handled by this controller.
     */
    private static $url_handlers = array(
        
    );
    
    /**
     * Defines the allowed actions for this controller.
     */
    private static $allowed_actions = array(
        
    );
    
    /**
     * Performs initialisation before any action is called on the receiver.
     */
    public function init()
    {
        // Initialise Parent:
        
        parent::init();
    }
}
