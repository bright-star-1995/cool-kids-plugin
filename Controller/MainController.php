<?php
/**
 * Namespace for MainController class
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
 * Class MainController
 *
 * This class handles the main functionalities of the system,
 * such as user registration, login, displays members.
 * Also it configures the rest api endpoint to update the user's role
 * 
 * @category WordPress
 * @package  CoolKidsPlugin
 * @author   Display Name <kadeem.young@spikeup.com>
 * @license  BSD Licence
 * @link     https://knightlogic.com.au/coolkids
 */
class MainController
{
    public $template_path = __DIR__ . '/../View/';
    public $memberObj;
    
    /**
     * MainController constructor.
     * 
     * Initializes the MainController class.
     */
    public function __construct()
    {
        if (!session_id() ) {
            session_start();
        }

        $this->memberObj = new Member();

        add_action('wp_ajax_authenticate', array( $this, 'authenticate' ));
        add_action('wp_ajax_nopriv_authenticate', array( $this, 'authenticate' ));
        add_action('wp_ajax_logout', array( $this, 'logout' ));
        add_action('wp_ajax_nopriv_logout', array( $this, 'logout' ));
        add_action('admin_enqueue_scripts', array( $this, 'enqueueScripts' ));
        add_action('wp_enqueue_scripts', array( $this, 'enqueueScripts' ));
        add_action('rest_api_init', array( $this, 'updateRole' ));
    }

    /**
     * Update User's Role
     * 
     * Rest API endpoint to update the user's role
     * 
     * @return string status of the action as json format
     */
    public function updateRole()
    {
        register_rest_route(
            'api/v1', '/update-role', [
            'methods' => 'POST',
            'callback' => function ( \WP_REST_Request $request ) {
                if (!current_user_can('administrator') ) {
                    return new \WP_REST_Response(
                        [ 
                        'status' => '401', 
                        'response' => 'Forbidden' 
                        ]
                    );
                }
                $data = json_decode(file_get_contents('php://input'), true);
                $role = '';
                if (isset($data[ 'role' ]) ) {
                    $role = $data[ 'role' ];
                }
                $user_email = '';
                if (isset($data[ 'email' ]) ) {
                    $user_email = $data[ 'email' ];
                }
                $firstname = '';
                if (isset($data[ 'firstname' ]) ) {
                    $firstname = $data[ 'firstname' ];
                }
                $lastname = '';
                if (isset($data[ 'lastname' ]) ) {
                    $lastname = $data[ 'lastname' ];
                }

                // Validate the role
                $roleList = [ 
                Member::COOL_KID, 
                Member::COOLER_KID, 
                Member::COOLEST_KID 
                ];
                if (!in_array($role, $roleList) ) {
                    return new \WP_REST_Response(
                        [ 
                        'status' => '400', 
                        'response' => 'Invalid role'
                        ]
                    );
                }

                // Get the user by ID
                $params = [];
                if ($user_email != '' ) {
                    $params[ 'email' ] = $user_email;
                }
                if ($firstname != '' ) {
                    $params[ 'firstname' ] = $firstname;
                }
                if ($lastname != '' ) {
                    $params[ 'lastname' ] = $lastname;
                }
                $user = $this->memberObj->getMemberBy($params);
                if (!$user) {
                    return new \WP_REST_Response(
                        [
                        'status' => 404, 
                        'response' => 'User not found.' 
                        ]
                    );
                }

                $this->memberObj->updateMemberRole($user->id, $role);
                return new \WP_REST_Response(
                    [ 
                    'status' => 200, 
                    'response' => 'User role updated successfully.' 
                    ]
                );
            },
            ]
        );
    }

    /**
     * Enqueue Scripts
     * 
     * Method to enqueue necessary scripts required for the UI interface.
     * 
     * @return void
     */
    public function enqueueScripts()
    {
        wp_enqueue_style(
            'bootstrap', 
            'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'
        );
        wp_enqueue_style(
            'cool-kids', 
            COOLKIDS_PLUGIN_BASEDIR_URL.'Assets/css/custom.css', 
            array( 'bootstrap' ), '', true
        );
        wp_enqueue_script(
            'cool-kids', 
            COOLKIDS_PLUGIN_BASEDIR_URL.'Assets/js/main.js',
            array( 'jquery' ), '', true
        );
        wp_localize_script(
            'cool-kids', 'urlVars', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'homeurl' => home_url(),
            )
        );
    }

    /**
     * Cool Kids Member Authentication
     * 
     * Performs authentication actions like register and log in
     * 
     * @return string status of the action as json format
     */
    public function authenticate()
    {
        $email = '';
        if (isset($_POST['email']) ) {
            $email = $_POST['email'];
        }

        $type = '';
        if (isset($_POST['type']) ) {
            $type = $_POST['type'];
        }

        if ($email == '' || $type == '' ) {
            echo json_encode(['error' => 'Data Error']);
            wp_die();
        }

        if ($type == 'login' ) {
            $ret = $this->memberObj->checkExist($email);
            if ($ret == 0 ) {
                echo json_encode(['error' => 'Login Error']);
                wp_die();
            }
        } else {
            $new_user_id = $this->memberObj->registerMember($email);
            if ($new_user_id == 0 ) {
                echo json_encode(
                    [
                    'error' => 'Registration Error. Try Again.'
                    ]
                );
                wp_die();
            } else if ($new_user_id == -1 ) {
                echo json_encode(
                    [
                    'error' => 'RandomUser API Error. Try Again.'
                    ]
                );
                wp_die();
            } else if ($new_user_id == -2 ) {
                echo json_encode(
                    [
                    'error' => 'Already Registered. Try Again.'
                    ]
                );
                wp_die();
            }
        }

        echo json_encode(['success' => true]);
        wp_die();
    }

    /**
     * Check Session
     * 
     * Checks if there is a user logged in
     * 
     * @return integer indicate whether a user logged in or not.
     */
    public function checkauth()
    {
        $logged_in = 0;
        if (isset($_SESSION['member']) ) {
            $logged_in = 1;
        }
        return $logged_in;
    }

    /**
     * Log Out
     * 
     * Destroys the logged-in member's session
     * 
     * @return string status of action as json format
     */
    public function logout()
    {
        if (isset($_SESSION['member']) ) {
            unset($_SESSION['member']);
        }
        echo json_encode(['success' => true]);
        wp_die();
    }

    /**
     * Main Function
     * 
     * If a member was logged in, it displays member's character and member data. 
     * Otherwise, displays nothing
     * 
     * @return string The HTML content for rendering cool kids members.
     */
    public function index()
    {
        $data['members'] = [];
        if ($this->checkauth() == '1' ) {
            $data['members'] = $this->memberObj->getMembers();
        }
        $this->render('index', $data);
    }

    /**
     * Render Sign Up page
     * 
     * @return string The HTML content for sign up page
     */
    public function signup()
    {
        if ($this->checkauth() == '0' ) {
            $this->render('signup');
        } else {
            wp_redirect(home_url());
        }
    }

    /**
     * Render Sign In page
     * 
     * @return string The HTML content for sign in page
     */
    public function signin()
    {
        if ($this->checkauth() == '0' ) {
            $this->render('signin');
        } else {
            wp_redirect(home_url());
        }
    }

    /**
     * Render View Page's content
     * 
     * @param string $view view file's name
     * @param array  $data data to render
     * 
     * @return string The HTML content
     */
    public function render( $view, $data = array() )
    {
        $viewFile = $this->template_path . $view . '.php';

        if (file_exists($viewFile) ) {
            // Make the data available to the view
            extract($data);
            
            // Start output buffering
            ob_start();
            
            // Include the view file
            include $viewFile;
            
            // Get the contents of the output buffer
            $content = ob_get_clean();
            
            // Output the content
            echo $content;
        } else {
            // Handle the case where the view file does not exist
            echo 'View not found';
            echo $viewFile;
        }
    }
}