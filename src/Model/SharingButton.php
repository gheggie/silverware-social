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
use SilverWare\Model\Component;
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
class SharingButton extends Component
{
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
     * Description of this object.
     *
     * @var string
     * @config
     */
    private static $description = 'A component which represents a sharing button';
    
    /**
     * Icon file for this object.
     *
     * @var string
     * @config
     */
    private static $icon = 'silverware/social: admin/client/dist/images/icons/SharingButton.png';
    
    /**
     * Defines the table name to use for this object.
     *
     * @var string
     * @config
     */
    private static $table_name = 'SilverWare_SharingButton';
    
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
     * Answers the link for the sharing button.
     *
     * @return string
     */
    public function getButtonLink()
    {
        return $this->getCurrentPageLink();
    }
    
    /**
     * Answers the link for the current page.
     *
     * @return string
     */
    public function getCurrentPageLink()
    {
        if ($page = $this->getCurrentPage(Page::class)) {
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
        if ($page = $this->getCurrentPage(Page::class)) {
            return $page->Title;
        }
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
