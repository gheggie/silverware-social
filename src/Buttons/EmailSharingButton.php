<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Social\Buttons
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-social
 */

namespace SilverWare\Social\Buttons;

use SilverStripe\Forms\TextField;
use SilverWare\Social\Model\SharingButton;

/**
 * An extension of the sharing button class for an email sharing button.
 *
 * @package SilverWare\Social\Buttons
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-social
 */
class EmailSharingButton extends SharingButton
{
    /**
     * Human-readable singular name.
     *
     * @var string
     * @config
     */
    private static $singular_name = 'Email Sharing Button';
    
    /**
     * Human-readable plural name.
     *
     * @var string
     * @config
     */
    private static $plural_name = 'Email Sharing Buttons';
    
    /**
     * Defines an ancestor class to hide from the admin interface.
     *
     * @var string
     * @config
     */
    private static $hide_ancestor = SharingButton::class;
    
    /**
     * Maps field names to field types for this object.
     *
     * @var array
     * @config
     */
    private static $db = [
        'EmailSubject' => 'Varchar(255)',
        'EmailMessage' => 'Varchar(255)'
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
        
        $fields->addFieldsToTab(
            'Root.Main',
            [
                TextField::create(
                    'EmailSubject',
                    $this->fieldLabel('EmailSubject')
                )->setRightTitle(
                    _t(
                        __CLASS__ . '.EMAILSUBJECTRIGHTTITLE',
                        'Uses the title of the current page if blank.'
                    )
                ),
                TextField::create(
                    'EmailMessage',
                    $this->fieldLabel('EmailMessage')
                )->setRightTitle(
                    _t(
                        __CLASS__ . '.EMAILMESSAGERIGHTTITLE',
                        'Included in the body of the email before the shared link.'
                    )
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
        
        $labels['EmailSubject'] = _t(__CLASS__ . '.EMAILSUBJECT', 'Email subject');
        $labels['EmailMessage'] = _t(__CLASS__ . '.EMAILMESSAGE', 'Email message');
        
        // Answer Field Labels:
        
        return $labels;
    }
    
    /**
     * Populates the default values for the fields of the receiver.
     *
     * @return void
     */
    public function populateDefaults()
    {
        // Populate Defaults (from parent):
        
        parent::populateDefaults();
        
        // Populate Defaults:
        
        $this->Name = _t(__CLASS__ . '.SHAREVIAEMAIL', 'Share via Email');
    }
    
    /**
     * Answers the subject for the email.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->EmailSubject ? $this->EmailSubject : $this->getCurrentPageTitle();
    }
    
    /**
     * Answers the message for the email.
     *
     * @return string
     */
    public function getMessage()
    {
        if ($this->EmailMessage) {
            return sprintf('%s %s', rtrim($this->EmailMessage), $this->getCurrentPageLink());
        }
        
        return $this->getCurrentPageLink();
    }
    
    /**
     * Answers the link for the sharing button.
     *
     * @return string
     */
    public function getLink()
    {
        return sprintf('mailto:?subject=%s&body=%s', $this->getSubject(), $this->getMessage());
    }
}
