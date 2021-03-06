<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

/**
 *  Layout
 *
 * @var string
 */
	public $layout = 'bootstrap';
        public $uses = array('User','Project','OrderLine');

	function beforeFilter() {
	    parent::beforeFilter();
            $authUser = $this->getAuthUser();
	    if(!isAdminUser($this->getAuthUser()) && 
		(($this->action != 'dashboard' && $this->action != 'view' && $this->action != 'edit' ) ||
		 ($this->action == 'view' && $authUser['User']['id'] != $this->request->params['pass'][0]) ||
		 ($this->action == 'edit' && $authUser['User']['id'] != $this->request->params['pass'][0])
	        )
	      ) {
		setErrorFlush($this->Session, "you don't have permission to access.");
		$this->redirect("/dashboard");
	    }
	}
/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('TwitterBootstrap.BootstrapHtml', 'TwitterBootstrap.BootstrapForm', 'TwitterBootstrap.BootstrapPaginator', 'Js', 'Usermgmt.UserAuth');


/**
 * Components
 *
 * @var array
 */
	public $components = Array(
	    'Session',
	    'Auth' => Array(
		'loginRedirect' => Array('controller'  => 'projects', 'action' => 'index'),
		'logoutRedirect' => Array('controller' => 'admin', 'action' => 'login'),
		'loginAction' => Array('controller' => 'admin', 'action' => 'login'),
		'authenticate' => Array('Form' => Array('fields' => Array('username' => 'email')))
	    )
	);
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid %s', __('user')));
		}
		$this->set('user', $this->User->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('user')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('user')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$userGroups = $this->User->UserGroup->find('list', array('conditions' => array(
		    'UserGroup.id >' => GUEST_GROUP_ID
		)));
		$this->set(compact('userGroups'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid %s', __('user')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('user')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('user')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
		$user = $this->User->findById($id);
		$userGroup = "";
		if (isAdminUser($user)) {
		    $userGroups = $this->User->UserGroup->find('list', array('conditions' => array(
			'UserGroup.id' => ADMIN_GROUP_ID
		    )));
		}
		else {
		    $userGroups = $this->User->UserGroup->find('list', array('conditions' => array(
			'UserGroup.id >' => GUEST_GROUP_ID
		    )));
		}
		$this->set(compact('userGroups'));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid %s', __('user')));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('user')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('user')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * Used to show dashboard of the user
	 *
	 * @access public
	 * @return array
	 */
	public function dashboard() {
		$userId=$this->UserAuth->getUserId();
		$user = $this->User->findById($userId);
		$this->set('user', $user);
		$this->set('orderStatuses', $this->User->OrderLine->OrderStatus->find('list'));
		$this->set('projectNames', $this->User->OrderLine->Project->find('list'));
		$this->set('projects', $this->paginate('Project', array(
		    'Project.user_id' => $userId
		)));
		$this->set('orderLines', $this->paginate('OrderLine'));
	}
}
