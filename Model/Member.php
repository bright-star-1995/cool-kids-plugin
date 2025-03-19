<?php
/**
 * Namespace for Member class
 * php version 8.1
 *
 * @category WordPress_Admin
 * @package  CoolKidsPlugin
 * @author   Display Name <kadeem.young@spikeup.com>
 * @license  BSD Licence
 * @link     https://knightlogic.com.au/coolkids
 */
namespace CoolKidsApp\Model;

/**
 * Class Member
 *
 * @category WordPress
 * @package  CoolKidsPlugin
 * @author   Display Name <kadeem.young@spikeup.com>
 * @license  BSD Licence
 * @link     https://knightlogic.com.au/coolkids
 */
class Member
{
    public $id;
    public $email;
    public $firstname;
    public $lastname;
    public $role;

    public $table_name;
    public const COOL_KID = 'cool-kid';
    public const COOLER_KID = 'cooler-kid';
    public const COOLEST_KID = 'coolest-kid';
    public $api_url = 'https://randomuser.me/api';

    /**
     * Member constructor.
     * 
     * Initializes the Member class.
     */
    function __construct()
    {
        global $wpdb;

            $this->table_name = $wpdb->prefix . 'members';
            $result = $wpdb->query("SHOW TABLES LIKE '$this->table_name'");
        if ($result == 0) {
            $table_columns = [
                "`id` INT(11) NOT NULL AUTO_INCREMENT",
                "`email` varchar(50) DEFAULT NULL",
                "`firstname` varchar(50) DEFAULT NULL",
                "`lastname` varchar(50) DEFAULT NULL",
                "`country` varchar(50) DEFAULT NULL",
                "`role` varchar(15) DEFAULT NULL",
                "PRIMARY KEY (`id`)",
            ];
            $query = "CREATE TABLE `$this->table_name` (" 
            . implode(", ", $table_columns) 
            . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb3";
            $wpdb->query($query);
        }
    }

    /**
     * Get Member
     * 
     * Get One Member by conditions
     *
     * @param array $params array of conditions
     * 
     * @return object if found, otherwise null
     */
    public function getMemberBy( $params )
    {
        global $wpdb;
        $query = "SELECT * FROM `$this->table_name`";
        $where = array();
        foreach ( $params as $key => $value ) {
            $where[] = "$key = '$value'";
        }
        $where_clause = implode(" and ", $where);

        if (count($where) > 0 ) {
            $query = "SELECT * FROM `$this->table_name` where ". $where_clause;
        }
        $results = $wpdb->get_results($query);
        if (count($results) > 0 ) {
            return $results[0];
        }
        return null;
    }

    /**
     * Check if Member Exists by Email
     * 
     * @param string $email Email of a member to find
     * 
     * @return integer count of members found
     */
    public function checkExist( $email )
    {
        global $wpdb;
        $query = $wpdb->prepare(
            "SELECT * FROM `$this->table_name` WHERE email = %s",
            $email
        );
        $results = $wpdb->get_results($query);
        if (count($results) > 0 ) {
            $_SESSION['member'] = $results[0];
        }
        return count($results);
    }

    /**
     * Register Member by Email
     * 
     * @param string $email Email of a member to register
     * 
     * @return integer if successful, returns new user's id, otherwise, -1 or -2
     */
    public function registerMember( $email )
    {
        global $wpdb;
        $new_user_id = -2;

        if ( $this->getMemberBy( [ 'email' => $email ] ) == 0 ) {
            $insert_data = [
            'email' => $email,
            'role' => Member::COOL_KID,
            ];

            $args = array(
                'timeout'       => 300,
                'sslverify'     => false
            ); 

            $response = wp_remote_get( $this->api_url, $args );
            if (is_wp_error($response) ) {
                //error_log( "Failed to fetch data: " . $this->api_url );
                return -1;
            }
                
            $data = wp_remote_retrieve_body($response);
            if (empty($data) ) {
                //error_log( "Empty data from: " . $this->api_url );
                return -1;
            }
            $man_data = json_decode($data, true);
            if (json_last_error() !== JSON_ERROR_NONE 
                || !is_array($man_data) 
                || empty($man_data)
            ) {
                //error_log( "JSON ERROR: " . $this->api_url );
                return -1;    
            } else {
                if (isset($man_data['results']) ) {
                    foreach ( $man_data['results'] as $result ) {
                         $insert_data['firstname'] = $result['name']['first'];
                         $insert_data['lastname'] = $result['name']['last'];
                         $insert_data['country'] = $result['location']['country'];

                         $result = $wpdb->insert($this->table_name, $insert_data);
                             $new_user_id = $wpdb->insert_id;

                         break;
                    }
                }
            }
        }

           return $new_user_id;
    }

    /**
     * Get All Member Registered
     * 
     * @return array list of members
     */
    public function getAllMembers()
    {
        global $wpdb;
        $query = "SELECT * FROM `$this->table_name`";
        $results = $wpdb->get_results($query);
        return $results;
    }

    /**
     * Get All Member Registered by logged-in member's role
     * 
     * @return array list of members
     */
    public function getMembers()
    {
        global $wpdb;
        $role = isset($_SESSION['member']->role) 
        ? $_SESSION['member']->role : Member::COOL_KID;
        $id = isset($_SESSION['member']->id) ? $_SESSION['member']->id : 0;
        $results = [];
        if ($role == Member::COOLER_KID ) {
            $query = "SELECT id, firstname, lastname, country, 
				'-' as email, '-' as role FROM `$this->table_name`";
            if ($id != 0 ) {
                $query .= ' where id <> '.$id;
            }
            $results = $wpdb->get_results($query);
        } else if ($role == Member::COOLEST_KID ) {
            $query = "SELECT * FROM `$this->table_name`";
            if ($id != 0 ) {
                $query .= ' where id <> '.$id;
            }
            $results = $wpdb->get_results($query);
        }
        return $results;
    }

    /**
     * Update member's role
     * 
     * @param integer $id   Member's ID
     * @param string  $role The Role to be updated
     * 
     * @return void
     */
    public function updateMemberRole( $id, $role )
    {
        global $wpdb;
        $query = $wpdb->prepare(
            "update `$this->table_name` set role=%s where id=%s", 
            $role, $id
        );
        $wpdb->query($query);
    }

}
