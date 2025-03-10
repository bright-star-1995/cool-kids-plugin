<?php
/**
 * Namespace for AdminController class
 * php version 8.1
 *
 * @category WordPress_Admin
 * @package  CoolKidsPlugin
 * @author   Display Name <kadeem.young@spikeup.com>
 * @license  BSD Licence
 * @link     https://knightlogic.com.au/coolkids
 */
namespace CoolKidsApp\Controller;

use CoolKidsApp\Model\Member;

/**
 * Class AdminController
 *
 * This class handles the administration functionalities of the system,
 * such as managing admin menus, enqueueing scripts, and rendering members.
 * 
 * @category WordPress_Admin
 * @package  CoolKidsPlugin
 * @author   Display Name <kadeem.young@spikeup.com>
 * @license  BSD Licence
 * @link     https://knightlogic.com.au/coolkids
 */
class AdminController
{
    public $memberObj;

    /**
     * AdminController constructor.
     * 
     * Initializes the AdminController class.
     */
    public function __construct()
    {
        $this->memberObj = new Member();
        add_action('admin_menu', [ $this, 'addAdminMenu' ]);
        add_action('admin_enqueue_scripts', array( $this, 'enqueueScripts' ));
        add_action('wp_enqueue_scripts', array( $this, 'enqueueScripts' ));
    }

    /**
     * Add Admin Menu
     * 
     * Function to add a custom admin menu in the system.
     * 
     * @return void
     */
    public function addAdminMenu()
    {
        add_menu_page(
            'CoolKids Members',
            'CoolKids Members',
            'manage_options',
            'coolkids-members-management',
            [ $this, 'coolKidsMembersRender' ]
        );
    }

    /**
     * Enqueue Scripts
     * 
     * Method to enqueue necessary scripts required for the admin interface.
     * 
     * @return void
     */
    public function enqueueScripts()
    {
        wp_enqueue_script(
            'cool-kids-admin',
            COOLKIDS_PLUGIN_BASEDIR_URL.'Assets/js/admin.js',
            array( 'jquery' ),
            '',
            true
        );
        wp_localize_script(
            'cool-kids-admin', 'adminUrlVars', array(
            'apiUrl' => home_url().'/wp-json/api/v1/update-role/',
            )
        );
    }

    /**
     * Cool Kids Members Render
     * 
     * Render "cool kids" members in the admin interface.
     * 
     * @return string The HTML content for rendering cool kids members.
     */
    public function coolKidsMembersRender()
    {
        $members = $this->memberObj->getAllMembers();
        $roles = [ Member::COOL_KID, Member::COOLER_KID, Member::COOLEST_KID ];
        include COOLKIDS_PLUGIN_BASEDIR . 'View/admin.php';
    }

}