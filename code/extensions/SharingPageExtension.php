<?php

/**
 * An extension of the data extension class which adds sharing settings to pages.
 */
class SharingPageExtension extends DataExtension
{
    private static $db = array(
        'SharingDisabled' => 'Boolean'
    );
    
    private static $defaults = array(
        'SharingDisabled' => 0
    );
    
    /**
     * Updates the CMS settings fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateSettingsFields(FieldList $fields)
    {
        // Update Field Objects:
        
        $fields->addFieldToTab(
            'Root.Settings',
            $settings = ToggleCompositeField::create(
                'SharingSettings',
                _t('SharingPageExtension.SOCIALSHARING', 'Social Sharing'),
                array(
                    CheckboxField::create(
                        'SharingDisabled',
                        _t('SharingPageExtension.SHARINGDISABLED', 'Sharing disabled')
                    )
                )
            )
        );
        
        // Check Permissions and Modify Fields:
        
        if (!Permission::check('ADMIN') && !Permission::check('SILVERWARE_PAGE_SETTINGS_CHANGE')) {
            
            foreach ($settings->getChildren() as $field) {
                $settings->makeFieldReadonly($field);
            }
            
        }
    }
}
