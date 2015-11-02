<?php

/*
 * Copyright (C) 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @url https://bitbucket.org/vagenas/wp-admin-notices/wiki/Home
 * @
 */

if (!class_exists('WP_Admin_Notices')) {
    /**
     * Singleton class of WP_Admin_Notices
     * Please see https://bitbucket.org/vagenas/wp-admin-notices/wiki/Home
     *
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     */
    class WP_Admin_Notices {

        /**
         * Instance of this class.
         *
         * @since 1.0.0
         * @var WP_Admin_Notices
         */
        protected static $instance = null;

        /**
         * Name of the array that will be stored in DB
         * @var string 
         * @since 1.0.0
         */
        protected $noticesArrayName = 'WCQDWPAdminNotices';

 
        /**
         * Name of the GET / POST that will be to remove the msg
         * @var string 
         * @since 1.0.0
         */
        protected $REQUESTID = 'WCQDRMSG';       
        
        /**
         * Notices array as loaded from DB
         * @var array
         * @since 1.0.0 
         */
        protected $notices = array();

        /**
         * Costructor (private since this is a singleton)
         */
        private function __construct() {
            $this->loadNotices();
            $this->auto_remove_Notice();
            add_action('admin_notices', array($this, 'displayNotices'));
        }

        /**
         * Returns an instance of this class.
         *
         * @since 1.0.0
         * @return WP_Admin_Notices
         */
        public static function getInstance() {
            if (null == self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Loads notices from DB
         */
        private function loadNotices() {
            $notices = get_option($this->noticesArrayName);
            if (is_array($notices)) {
                $this->notices = $notices;
            }
        }

        /**
         * Action hook to display notices. 
         * Just echoes notices that should be displayed.
         */
        public function displayNotices() {
            foreach ($this->notices as $key => $notice) { 
                if ($this->isTimeToDisplay($notice)) { 
                    echo $notice->getContentFormated($notice->getWrapper());
                    $notice->incrementDisplayedTimes();
                }
                if($notice->getTimes() > 0){
                    if ($notice->isTimeToDie()) {
                        unset($this->notices[$key]);
                    }                    
                }
                
            }
            $this->storeNotices();
        }
        
        /**
         * Removes Notice By Getting ID From GET / POST METHOD
         */
        public function auto_remove_Notice(){
                   
            if(isset($_REQUEST[$this->REQUESTID])){
                $nonce = $_REQUEST['_wpnonce'];
                if (wp_verify_nonce( $nonce, 'WCQDREMOVEMSG' ) ) {   
                    $this->deleteNotice($_REQUEST[$this->REQUESTID]);
                    if (wp_get_referer()){ wp_safe_redirect( wp_get_referer() ); }
                }
            }
        }

        /**
         * Stores notices in DB
         */
        private function storeNotices() {
            update_option($this->noticesArrayName, $this->notices);
        }

        /**
         * Deletes a notice
         * @param int $notId The notice unique id
         */
        public function deleteNotice($notId) {
            foreach ($this->notices as $key => $notice) {
                if ($notice->getId() === $notId) {
                    unset($this->notices[$key]);
                    break;
                }
            }
            $this->storeNotices();
        }

        /**
         * Adds a notice to be displayed
         * @param erpAdminMessage $notice
         */
        public function addNotice(WP_Notice $notice) {
            $this->notices[] = $notice;
            $this->storeNotices();
        }

        /**
         * Checks if is time to display a notice
         * @param WP_Notice $notice
         * @return bool 
         */
        private function isTimeToDisplay(WP_Notice $notice) {
            $screens = $notice->getScreen();
            if (!empty($screens)) {
                $curScreen = get_current_screen();
                if (!is_array($screens) || !in_array($curScreen->id, $screens)) {
                    return false;
                }
            }

            $usersArray = $notice->getUsers(); 
            if (!empty($usersArray)) {
                $curUser = get_current_user_id();
                if (!is_array($usersArray) || !in_array($curUser, $usersArray) || $usersArray[$curUser] >= $notice->getTimes()) {
                    return false;
                }
                
                
            } else if ($notice->getTimes() == 0) {
                return true;
            } else if ($notice->getTimes() <= $notice->getDisplayedTimes()) {
                return false;
            }

            return true;
        }

    }

}


if (!class_exists('WP_Notice')) {
    /**
     * Abstract class of a notice
     *
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     */
    abstract class WP_Notice {

        /**
         * Notice message to be displayed
         * @var string 
         */
        protected $content;

        /**
         * Notice type 
         * @var string 
         */
        protected $type;

        /**
         * In which screens the notice to be displayed
         * @var array 
         */
        protected $screen;

        /**
         * Unique identifier for notice
         * @var int
         */
        protected $id;

        /**
         * Number of times to be displayed
         * @var int
         */
        protected $times = 1;

        /**
         * User ids this notice should be displayed
         * @var array
         */
        protected $users = array();

        /**
         * Number of times this message is displayed
         * @var int
         */
        protected $displayedTimes = 0;

        /**
         * Keeps track of how many times and to
         * which users this notice is displayed
         * @var array
         */
        protected $displayedToUsers = array();

        /**
         * With Or WithOut Wraper
         * @var array
         */
        protected $WithWraper = true;        

        /**
         * 
         * @param type $content Coantent to be displayed
         * @param type $times How many times this notice will be displayed
         * @param array $screen The admin screens this notice will be displayed into (empty for all screens)
         * @param array $users Array of users this notice concernes (empty for all users)
         */
        public function __construct($content, $times = 1, Array $screen = array(), Array $users = array(), $WithWraper = true) {
            $this->content = $content;
            $this->screen = $screen;
            $this->id = uniqid();
            $this->times = $times;
            $this->users = $users;
            $this->WithWraper = $WithWraper;
        }

        /**
         * Get the content of the notice
         * @param bool $wrapInParTag If the content should be wrapped in a paragraph tag
         * @return string Formated content
         */
        public function getContentFormated($wrapInParTag = true) {
            $before = '<div class="' . $this->type . '">';
            $before .= $wrapInParTag ? '<p>' : '';
            $after = $wrapInParTag ? '</p>' : '';
            $after .= '</div>';
            return $before . $this->getContent() . $after;
        }

        /**
         * Increment displayed times of the notice
         * @return \WP_Notice
         */
        public function incrementDisplayedTimes() {
            $this->displayedTimes++;

            if (array_key_exists(get_current_user_id(), $this->displayedToUsers)) {
                $this->displayedToUsers[get_current_user_id()] ++;
            } else {
                $this->displayedToUsers[get_current_user_id()] = 1;
            }
            return $this;
        }

        /**
         * Checks if the notice should me destroyed
         * @return boolean True iff notice is deprecated
         */
        public function isTimeToDie() {
            if (empty($this->users)) {
                return $this->displayedTimes >= $this->times;
            } else {
                $i = 0;
                foreach ($this->users as $key => $value) {
                    if (isset($this->displayedToUsers[$value]) && $this->displayedToUsers[$value] >= $this->times) {
                        $i++;
                    }
                }
                if ($i >= count($this->users)) {
                    return true;
                }
            }
            return false;
        }

         /**
         * Get the $WithWraper Value
         */
        public function getWrapper(){
            return $this->WithWraper;
        }

        /**
         * Set the $WithWraper Value
         * @param boolean $screen 
         */
        public function setWrapper($wrapper = true){
            $this->WithWraper = $wrapper;
            return $this;
        }
        
        
        /**
         * Get the current screen slug
         * @return string Current screen slug
         */
        public function getScreen() {
            return $this->screen;
        }

        /**
         * Set the screens the notice will be displayed
         * @param array $screen
         * @return \WP_Notice
         */
        public function setScreen(Array $screen) {
            $this->screen = $screen;
            return $this;
        }

        /**
         * Get the notice string unformated
         * @return string
         */
        public function getContent() {
            return $this->content;
        }

        /**
         * 
         * @param string $content
         * @return \WP_Notice
         */
        public function setContent($content) {
            $this->content = $content;
            return $this;
        }

        /**
         * 
         * @return string
         */
        public function getId() {
            return $this->id;
        }

        /**
         * 
         * @return int
         */
        public function getTimes() {
            return $this->times;
        }

        /**
         * 
         * @return array
         */
        public function getUsers() {
            return $this->users;
        }

        /**
         * 
         * @param int $times
         * @return \WP_Notice
         */
        public function setTimes($times) {
            $this->times = $times;
            return $this;
        }

        /**
         * 
         * @param array $users
         * @return \WP_Notice
         */
        public function setUsers(Array $users) {
            $this->users = $users;
            return $this;
        }

        /**
         * 
         * @return int
         */
        public function getDisplayedTimes() {
            return $this->displayedTimes;
        }

        /**
         * 
         * @return array
         */
        public function getDisplayedToUsers() {
            return $this->displayedToUsers;
        }

        /**
         * 
         * @param int $displayedTimes
         * @return \WP_Notice
         */
        public function setDisplayedTimes($displayedTimes) {
            $this->displayedTimes = $displayedTimes;
            return $this;
        }

        /**
         * 
         * @param array $displayedToUsers
         * @return \WP_Notice
         */
        public function setDisplayedToUsers(Array $displayedToUsers) {
            $this->displayedToUsers = $displayedToUsers;
            return $this;
        }

    }

    /**
     * Type of notices
     */
    class WP_Error_Notice extends WP_Notice {

        protected $type = 'error wcQD';

    }

    class WP_Updated_Notice extends WP_Notice {

        protected $type = 'updated';

    }

    class WP_UpdateNag_Notice extends WP_Notice {

        protected $type = 'update-nag';

    }

}

/**
 * Hook action to admin init
 */
if(!has_action('init', array('WP_Admin_Notices', 'getInstance'))){
    add_action('init', array('WP_Admin_Notices', 'getInstance'));
}
//WP_Admin_Notices::getInstance();
if ( ! function_exists( 'wc_qd_notice' ) ) {
    function wc_qd_notice( $message, $type = 'update',$args = array()) {
        $notice = '';
        $defaults = array('times' => 1,'screen' => array(),'users' => array(), 'wraper' => true);    
        $args = wp_parse_args( $args, $defaults );
        extract($args);
        if($type == 'error'){$notice = new WP_Error_Notice($message,$times, $screen, $users);}
        if($type == 'update'){$notice = new WP_Updated_Notice($message,$times, $screen, $users);}
        if($type == 'upgrade'){$notice = new WP_UpdateNag_Notice($message,$times, $screen, $users);} 
        $msgID = $notice->getId();
        
        $message = str_replace('$msgID$',$msgID,$message);
        $notice->setContent($message);
        $notice->setWrapper($wraper);
        WP_Admin_Notices::getInstance()->addNotice($notice);
    }
}

if ( ! function_exists( 'wc_qd_remove_link' ) ) {
    function wc_qd_remove_link($attributes = '',$msgID = '$msgID$', $text = 'Remove Notice') {
        if(!empty($msgID)){
            $url = admin_url().'?WCQDRMSG='.$msgID ;
            $url = wp_nonce_url($url, 'WCQDREMOVEMSG');
            $url = urldecode($url);
            $tag = '<a '.$attributes.' href="'.$url.'">'.__($text,'woocommerce-quick-donation').'</a>';
            return $tag;
        }
    }
}
?>