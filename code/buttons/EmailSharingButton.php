<?php

/**
 * An extension of the sharing button class for an email sharing button.
 */
class EmailSharingButton extends SharingButton
{
    private static $singular_name = "Email Button";
    private static $plural_name   = "Email Buttons";
    
    private static $db = array(
        'EmailSubject' => 'Varchar(255)',
        'EmailMessage' => 'Varchar(255)'
    );
    
    private static $defaults = array(
        'Name' => 'Share via Email'
    );
    
    private static $required_themed_css = array(
        'email-sharing-button'
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
        
        // Create Main Fields:
        
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                TextField::create(
                    'EmailSubject',
                    _t('EmailSharingButton.EMAILSUBJECT', 'Email subject')
                )->setRightTitle(
                    _t(
                        'EmailSharingButton.EMAILSUBJECTRIGHTTITLE',
                        'Uses the title of the current page if blank.'
                    )
                ),
                TextField::create(
                    'EmailMessage',
                    _t('EmailSharingButton.EMAILMESSAGE', 'Email message')
                )->setRightTitle(
                    _t(
                        'EmailSharingButton.EMAILMESSAGERIGHTTITLE',
                        'Included in the body of the email before the shared link.'
                    )
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers the link for the sharing button.
     *
     * @return string
     */
    public function Link()
    {
        // Obtain Subject and Message:
        
        $Subject = $this->getSubject();
        $Message = $this->getMessage();
        
        // Append Current Page Link:
        
        $Message .= $this->getCurrentPageLink();
        
        // Answer Sharing Button Link:
        
        return "mailto:?subject={$Subject}&amp;body={$Message}";
    }
    
    /**
     * Answers the subject for the email.
     *
     * @return string
     */
    public function getSubject()
    {
        if ($this->EmailSubject) {
            return $this->EmailSubject;
        }
        
        return $this->getCurrentPageTitle();
    }
    
    /**
     * Answers the message for the email.
     *
     * @return string
     */
    public function getMessage()
    {
        if ($this->EmailMessage) {
            
            return rtrim($this->EmailMessage) . " ";
            
        }
    }
}
