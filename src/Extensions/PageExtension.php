<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Social\Extensions
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-social
 */

namespace SilverWare\Social\Extensions;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Security\Permission;
use SilverWare\Forms\FieldSection;

/**
 * A data extension which adds social settings to pages.
 *
 * @package SilverWare\Social\Extensions
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-social
 */
class PageExtension extends DataExtension
{
    /**
     * Maps field names to field types for the extended object.
     *
     * @var array
     * @config
     */
    private static $db = [
        'SharingDisabled' => 'Boolean'
    ];
    
    /**
     * Defines the default values for the fields of the extended object.
     *
     * @var array
     * @config
     */
    private static $defaults = [
        'SharingDisabled' => 0
    ];
    
    /**
     * Updates the CMS settings fields of the extended object.
     *
     * @param FieldList $fields Collection of CMS settings fields from the extended object.
     *
     * @return void
     */
    public function updateSettingsFields(FieldList $fields)
    {
        // Update Field Objects:
        
        $fields->addFieldToTab(
            'Root.Settings',
            $settings = FieldSection::create(
                'SocialSettings',
                $this->owner->fieldLabel('SocialSettings'),
                [
                    CheckboxField::create(
                        'SharingDisabled',
                        $this->owner->fieldLabel('SharingDisabled')
                    )
                ]
            )
        );
        
        // Check Permissions and Modify Fields:
        
        if (!Permission::check(['ADMIN', 'SILVERWARE_PAGE_SETTINGS_CHANGE'])) {
            
            foreach ($settings->getChildren() as $field) {
                $settings->makeFieldReadonly($field);
            }
            
        }
    }
    
    /**
     * Updates the field labels of the extended object.
     *
     * @param array $labels Array of field labels from the extended object.
     *
     * @return void
     */
    public function updateFieldLabels(&$labels)
    {
        $labels['SocialSettings']  = _t(__CLASS__ . '.SOCIAL', 'Social');
        $labels['SharingDisabled'] = _t(__CLASS__ . '.SHARINGDISABLED', 'Sharing disabled');
    }
    
    /**
     * Answers true if sharing is disabled for the extended object.
     *
     * @return boolean
     */
    public function isSharingDisabled()
    {
        return (boolean) $this->owner->SharingDisabled;
    }
}
