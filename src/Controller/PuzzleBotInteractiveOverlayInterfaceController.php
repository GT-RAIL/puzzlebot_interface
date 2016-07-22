<?php
App::uses('InterfaceController', 'Controller');

/**
 * Puzzle Bot Interface Controller 
 *
 * The is a merged interface for the mjpegcavnas and 3d parts of the canvas. on Puzzlebot
 *
 * @author		Carl Saldanha - csaldanha3@gatech.edu
 * @copyright	2016 Georgia Institute Of Technology
 * @link		none yet
 * @since		PuzzleInterface v 0.0.1
 * @version		0.0.1
 * @package		app.Controller
 */
class PuzzleBotInteractiveOverlayInterfaceController extends InterfaceController {
/**
 * The basic view action. All necessary variables are set in the main interface controller.
 *
 * @return null
 */
	public function view() {
		
		// set the title of the HTML page
		$this->set('title_for_layout', 'Nimbus Learning from demonstration Study');

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
		$this->set('title_for_layout', 'Nimbus Learning from demonstration Study');

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
