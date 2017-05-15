<?php
App::uses('InterfaceController', 'Controller');

/**
 * Nimbus Crowd Interface
 *
 * A full interface for letting crowd users demonstrate tasks using the Nimbus robot.
 *
 * @author		David Kent dekent@gatech.edu
 * @copyright	2017 Georgia Institute of Technology
 * @link		none
 * @version		0.0.1
 * @package		app.Controller
 */
class NimbusCrowdInterfaceController extends InterfaceController {
/**
 * The basic view action. All necessary variables are set in the main interface controller.
 *
 * @return null
 */
	public function view() {
		
		// set the title of the HTML page
		$this->set('title_for_layout', 'Control a Robot');

		// we will need some RWT libraries
		$this->set('rwt',
			array(
				'roslibjs' => 'current',
				'ros2djs' => 'current',
				'nav2djs' => 'current',
				'ros3djs' => 'current',
				'keyboardteleopjs' => 'current',
				'mjpegcanvasjs' => 'current',
				'rosqueuejs' => 'current'
			)
		);

		$this->set('userId', $this->Auth->user('id'));
	}

	public function admin(){
		// set the title of the HTML page
		$this->set('title_for_layout', 'TRAINS Study');

		// we will need some RWT libraries
		$this->set('rwt',
			array(
				'roslibjs' => 'current',
				'ros2djs' => 'current',
				'nav2djs' => 'current',
				'ros3djs' => 'current',
				'keyboardteleopjs' => 'current',
				'mjpegcanvasjs' => 'current',
				'rosqueuejs' => 'current'
			)
		);

		$this->set('userId', $this->Auth->user('id'));
	}
}
