<?php
    class Thubbl_Controllers_CustomControllerAclManager extends Zend_Controller_Plugin_Abstract
    {
        // default user role if not logged or (or invalid role found)
        private $_guestRole   = 'guest';
        private $_defaultRole = 'member';

        // the action to dispatch if a user doesn't have sufficient privileges
        private $_authController = array( 'controller' => 'user'
        								, 'action'     => 'login');

        public function __construct(Zend_Auth $auth)
        {
            $this->auth = $auth;
            $this->acl = new Zend_Acl();

            // add the different user roles
            $this->acl->addRole(new Zend_Acl_Role($this->_guestRole));
            $this->acl->addRole(new Zend_Acl_Role('member'));

            // add the resources we want to have control over
            $this->acl->add(new Zend_Acl_Resource('user'));

            // allow access to everything for all users by default
            // except for the account management and administration areas
            $this->acl->allow();
            $this->acl->deny(null, 'user');
			
            // add an exception so guests can log in or register
            // in order to gain privilege
            $this->acl->allow('guest', 'user', array('login',
                                                     'remember-password',
                                                     'registration',
                                                     'registration-complete'));

            // allow members access to the account management area
            $this->acl->allow('member', 'user');

        }

        /**
         * preDispatch
         *
         * Before an action is dispatched, check if the current user
         * has sufficient privileges. If not, dispatch the default
         * action instead
         *
         * @param Zend_Controller_Request_Abstract $request
         */
        public function preDispatch(Zend_Controller_Request_Abstract $request)
        {
            // check if a user is logged in and has a valid role,
            // otherwise, assign them the default role (guest)
            if ($this->auth->hasIdentity()) {
                $role = $this->_defaultRole;
            } else {
                $role = $this->_guestRole;
			}

            if (!$this->acl->hasRole($role)) {
            	$role = $this->_guestRole;
            }

            // the ACL resource is the requested controller name
            $resource = $request->controller;

            // the ACL privilege is the requested action name
            $privilege = $request->action;

            // if we haven't explicitly added the resource, check
            // the default global permissions
            if (!$this->acl->has($resource))
                $resource = null;

            // access denied - reroute the request to the default action handler
            if (!$this->acl->isAllowed($role, $resource, $privilege)) {
                $request->setControllerName($this->_authController['controller']);
                $request->setActionName($this->_authController['action']);
            }
        }
    }
?>