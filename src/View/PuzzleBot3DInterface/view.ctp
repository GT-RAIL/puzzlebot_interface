<?php
/**
 * Puzzle Bot Interface
 *
 * The Puzzle Bot view. This interface will for testing queuing and chat.
 *
 * @author		Carl Saldanha csaldanha3@gatech.edu
 * @copyright	2015 Georgia Institute of Technology
 * @link		none 
 * @version		0.0.1
 * @package		app.Controller
 */
?>

<?php
//custom styling
echo $this->Html->css('PuzzleBot3DInterface');
?>

<html>
<head>

	<?php
		echo $this->Html->script('bootstrap.min');
		echo $this->Html->css('bootstrap.min');
		echo $this->Rms->ros($environment['Rosbridge']['uri']);
		//Init study information
		echo $this->Rms->initStudy();
	?>
	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/EventEmitter/5.0.0/EventEmitter.js'></script>
	<script type='text/javascript' src='http://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.6.1/fabric.min.js'></script>
	
	<?php echo $this->Html->script('mjpegcanvas.js');?>

	<?php
		echo $this->Rms->tf(
    		$environment['Tf']['frame'],
    		$environment['Tf']['angular'],
    		$environment['Tf']['translational'],
    		$environment['Tf']['rate']
		);
	?>

</head>


<body>
	<table style="width:100% !important;">
		<tr>
			<td style="width: 22%; vertical-align:top;">
				<div id="tasks" style="height=500px; text-align: right; background-color:rgba(232, 238, 244, 1.0); border-radius:20px; margin:5px; padding:20px">
					<b>Your Tasks:</b>
					<ul style="margin:0">
						<li>Open the drawer</li>
						<li>Turn on the lamp</li>
						<li>Open the jar of beans</li>
						<li>Pour out the pitcher</li>
						<li>Pull the toy cart</li>
						<li>Take an apple from the lunch box</li>
					</ul>
				</div>
			</td>
			<td>
				<table>
					<tr><td>
						<table>
							<tr>
								<td style="width: 28%">
									<div id="viewer" style="text-align:center"></div>
								</td>
								<td style="width: 28%">
									<div id="mjpeg" style="text-align:center"></div>
								</td>
							</tr>
						</table>
					</td></tr>
					<tr>
						<td style="text-align:center; background-color:rgba(232, 238, 244, 1.0); border-radius:20px;">
							<b><span id="feedback-text">&nbsp;</span></b>
						</td>
					</tr>
				</table>
			</td>
			<td style="width: 22%; vertical-align:top;">
				<div id="instructions" style="height=500px; text-align: left; background-color:rgba(232, 238, 244, 1.0); border-radius:20px; margin:5px; padding:20px">
					<b>Instructions:</b>
					<ol type="1" style="list-style-type:decimal; margin-left:15px;">
					<li><b>Set a position</b> for the gripper by <b>clicking and dragging</b> the <b>ring and arrow marker</b> on the left.</li>
					<li>
						<b>Click the move button</b> below to automatically move the arm to your set position.
						<br /><table style="margin-left:auto; margin-right:auto;">
							<tr>
								<td style="text-align:center; vertical-align:middle;" width="150px">
									<button id='moveArm' class='button special' style="width:140px">move arm</button>
								</td>
								<td style="vertical-align:middle;">
									<map name="plan-map">
										<area shape="rect" coords="0,0,150,100" href="javascript:executeGrasp()">
									</map>
									<img id="img-plan" src="/img/Nimbus/nimbus-plan.png" height="100" width="150" style="vertical-align:middle" usemap="plan-map">
								</td>

							</tr>
						</table>
					</li>
					<li>Use the <b>Arm Controls</b> below to grasp and manipulate an object.</li>
					</ol>
				</div>
			</td>
		</tr>
	</table>
	<hr>
	<table style="width:100% !important">
		<tr>
			<th style="width:33%"></th>
			<th style="width:33%"><b>Arm Controls</b></th>
			<th style="width:33%"></th>
		</tr>
		<tr>
			<td>
				<table style="margin-left:auto;">
					<tr>
						<td style="vertical-align:middle;">
							<map name="gripper-map">
								<area shape="rect" coords="0,0,45,100" href="javascript:executeOpenGripper()">
								<area shape="rect" coords="46,0,104,100" href="javascript:executeCloseGripper()">
								<area shape="rect" coords="105,0,150,100" href="javascript:executeOpenGripper()">
							</map>
							<img id="img-gripper" src="/img/Nimbus/nimbus-gripper.png" height="100" width="150" style="vertical-align:middle" usemap="#gripper-map">
						</td>
						<td>
							<table>
								<tr>
									<td style="text-align:center" width="180px"><button id='openGripper' class='button special' style="width:170px">open gripper</button></td>
								</tr>
								<tr>
									<td style="text-align:center" width="180px"><button id='closeGripper' class='button special' style="width:170px">close gripper</button></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr height="20px"></tr>
					<tr>
						<td style="vertical-align:middle;">
							<map name="reset-map">
								<area shape="rect" coords="0,0,150,100" href="javascript:executeResetArm()">
							</map>
							<img id="img-reset" src="/img/Nimbus/nimbus-reset-arm.png" height="100" width="150" style="vertical-align:middle" usemap="#reset-map">
						</td>
						<td style="text-align:center; vertical-align:middle;" width="180px">
							<button id='resetArm' class='button special' style="width:170px">reset arm</button>
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table style="margin-left:auto; margin-right:auto;">
					<tr>
						<td style="vertical-align:middle;">
							<map name="fb-map">
								<area shape="rect" coords="0,0,150,40" href="javascript:executeMoveForward()">
								<area shape="rect" coords="0,41,150,100" href="javascript:executeMoveBack()">
							</map>
							<img id="img-fb" src="/img/Nimbus/nimbus-forward-back.png" height="100" width="150" style="vertical-align:middle" usemap="#fb-map">
						</td>
						<td>
							<table>
								<tr>
									<td style="text-align:center" width="180px"><button id='moveForward' class='button special' style="width:170px">move forward</button></td>
								</tr>
								<tr>
									<td style="text-align:center" width="180px"><button id='moveBack' class='button special' style="width:170px">move back</button></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr height="20px"></tr>
					<tr>
						<td style="vertical-align:middle;">
							<map name="lr-map">
								<area shape="rect" coords="0,0,80,100" href="javascript:executeMoveLeft()">
								<area shape="rect" coords="81,0,150,100" href="javascript:executeMoveRight()">
							</map>
							<img id="img-lr" src="/img/Nimbus/nimbus-left-right.png" height="100" width="150" style="vertical-align:middle" usemap="#lr-map">
						</td>
						<td>
							<table>
								<tr>
									<td style="text-align:center" width="180px"><button id='moveLeft' class='button special' style="width:170px">move left</button></td>
								</tr>
								<tr>
									<td style="text-align:center" width="180px"><button id='moveRight' class='button special' style="width:170px">move right</button></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table style="margin-right:auto;">
					<tr>
						<td style="vertical-align:middle;">
							<map name="ud-map">
								<area shape="rect" coords="0,0,150,49" href="javascript:executeMoveUp()">
								<area shape="rect" coords="0,50,150,100" href="javascript:executeMoveDown()">
							</map>
							<img id="img-ud" src="/img/Nimbus/nimbus-up-down.png" height="100" width="150" style="vertical-align:middle" usemap="#ud-map">
						</td>
						<td>
							<table>
								<tr>
									<td style="text-align:center" width="180px"><button id='moveUp' class='button special' style="width:170px">move up</button></td>
								</tr>
								<tr>
									<td style="text-align:center" width="180px"><button id='moveDown' class='button special' style="width:170px">move down</button></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr height="20px"></tr>
					<tr>
						<td style="vertical-align:middle;">
							<map name="rotate-map">
								<area shape="rect" coords="0,0,75,100" href="javascript:executeRotateCW()">
								<area shape="rect" coords="76,0,150,100" href="javascript:executeRotateCCW()">
							</map>
							<img id="img-rotate" src="/img/Nimbus/nimbus-rotate-wrist.png" height="100" width="150" style="vertical-align:middle" usemap="#rotate-map">
						</td>
						<td>
							<table>
								<tr>
									<td style="text-align:center" width="180px"><button id='rotateCW' class='button special' style="width:170px">rotate cw</button></td>
								</tr>
								<tr>
									<td style="text-align:center" width="180px"><button id='rotateCCW' class='button special' style="width:170px">rotate ccw</button></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>

<script>
	var size = Math.min(((window.innerWidth / 2) - 120), window.innerHeight * 0.60);
	
	_VIEWER = new ROS3D.Viewer({
		divID: 'viewer',
		width: 500,
		height: 425,
		antialias: true,
		background: '#50817b',
		intensity: 0.660000
	});

	_VIEWER.addObject(
		new ROS3D.SceneNode({
			object: new ROS3D.Grid({cellSize: 0.75, size: 20, color: '#2B0000'}),
			tfClient: _TF,
			frameID: '/table_base_link'
		})
	);

	//add IMs
	<?php foreach ($environment['Im'] as $im): ?>
		new ROS3D.InteractiveMarkerClient({
			ros: _ROS,
			tfClient: _TF,
			camera: _VIEWER.camera,
			rootObject: _VIEWER.selectableObjects,
			<?php echo isset($im['Collada']['id']) ? __('loader:%d,', h($im['Collada']['id'])) : ''; ?>
			<?php echo isset($im['Resource']['url']) ? __('path:"%s",', h($im['Resource']['url'])) : ''; ?>
			topic: '<?php echo h($im['topic']); ?>'
		});
	<?php endforeach; ?>
</script>

<?php
// URDF
foreach ($environment['Urdf'] as $urdf) {
	echo $this->Rms->urdf(
		$urdf['param'],
		$urdf['Collada']['id'],
		$urdf['Resource']['url']
	);
}
?>

<script>
	//Setup ROS action clients
	var armClient = new ROSLIB.ActionClient({
		ros: _ROS,
		serverName: '/nimbus_moveit/common_actions/arm_action',
		actionName: 'nimbus_moveit/ArmAction'
	});
	var gripperClient = new ROSLIB.ActionClient({
		ros: _ROS,
		serverName: '/gripper_actions/gripper_manipulation',
		actionName: 'rail_manipulation_msgs/GripperAction'
	});
	var primitiveClient = new ROSLIB.ActionClient({
		ros: _ROS,
		serverName: '/nimbus_moveit/primitive_action',
		actionName: 'rail_manipulation_msgs/PrimitiveAction'
	});
	
	//TODO: Connect to marker action server for grasp execution

</script>

<script type="text/javascript">

	/****************************************************************************
	 *                          Button Callbacks                                *
	 ****************************************************************************/
	$('#resetArm').click(function (e) {
		e.preventDefault();
		executeResetArm();
	});

	$('#openGripper').click(function (e) {
		e.preventDefault();
		executeOpenGripper();
	});
	$('#closeGripper').click(function (e) {
		executeCloseGripper();
	});

	$('#moveForward').click(function (e) {
		e.preventDefault();
		executeMoveForward();
	});
	$('#moveBack').click(function (e) {
		e.preventDefault();
		executeMoveBack();
	});

	$('#moveLeft').click(function (e) {
		e.preventDefault();
		executeMoveLeft();
	});
	$('#moveRight').click(function (e) {
		e.preventDefault();
		executeMoveRight();
	});

	$('#moveUp').click(function (e) {
		e.preventDefault();
		executeMoveUp();
	});
	$('#moveDown').click(function (e) {
		e.preventDefault();
		executeMoveDown();
	});

	$('#rotateCW').click(function (e) {
		e.preventDefault();
		executeRotateCW();
	});
	$('#rotateCCW').click(function (e) {
		e.preventDefault();
		executeRotateCCW();
	});

	$('#moveArm').click(function (e) {
		e.preventDefault();
		executeGrasp();
	});

	/****************************************************************************
	 *                           Grasp Actions                                  *
	 ****************************************************************************/
	function executeGrasp() {
		//TODO: Connect to marker action server for grasp execution

	}


	/****************************************************************************
	 *                         Primitive Actions                                *
	 ****************************************************************************/
	function executeResetArm() {
		disableInput();
		var goal = new ROSLIB.Goal({
			actionClient: armClient,
			goalMessage: {
				action: 1
			}
		});
		goal.on('feedback', function(feedback) {
			displayFeedback(feedback.message);
		});
		goal.on('result', function(result) {
			enableInput();
		});
		goal.send();
	}

	function executeOpenGripper() {
		disableInput();
		var goal = new ROSLIB.Goal({
			actionClient: gripperClient,
			goalMessage: {
				close: false
			}
		});
		goal.on('feedback', function(feedback) {
			displayFeedback(feedback.message);
		});
		goal.on('result', function(result) {
			enableInput();
		});
		goal.send();
	}
	function executeCloseGripper() {
		disableInput();
		var goal = new ROSLIB.Goal({
			actionClient: gripperClient,
			goalMessage: {
				close: true
			}
		});
		goal.on('feedback', function(feedback) {
			displayFeedback(feedback.message);
		});
		goal.on('result', function(result) {
			enableInput();
		});
		goal.send();
	}

	function executeMoveForward() {
		disableInput();
		var goal = new ROSLIB.Goal({
			actionClient: primitiveClient,
			goalMessage: {
				primitive_type: 0,
				axis: 1,
				distance: 0.1
			}
		});
		goal.on('feedback', function(feedback) {
			displayFeedback(feedback.feedback);
		});
		goal.on('result', function(result) {
			enableInput();
		});
		goal.send();
	}
	function executeMoveBack() {
		disableInput();
		var goal = new ROSLIB.Goal({
			actionClient: primitiveClient,
			goalMessage: {
				primitive_type: 0,
				axis: 1,
				distance: -0.1
			}
		});
		goal.on('feedback', function(feedback) {
			displayFeedback(feedback.feedback);
		});
		goal.on('result', function(result) {
			enableInput();
		});
		goal.send();
	}

	function executeMoveLeft() {
		disableInput();
		var goal = new ROSLIB.Goal({
			actionClient: primitiveClient,
			goalMessage: {
				primitive_type: 0,
				axis: 0,
				distance: -0.1
			}
		});
		goal.on('feedback', function(feedback) {
			displayFeedback(feedback.feedback);
		});
		goal.on('result', function(result) {
			enableInput();
		});
		goal.send();
	}
	function executeMoveRight() {
		disableInput();
		var goal = new ROSLIB.Goal({
			actionClient: primitiveClient,
			goalMessage: {
				primitive_type: 0,
				axis: 0,
				distance: 0.1
			}
		});
		goal.on('feedback', function(feedback) {
			displayFeedback(feedback.feedback);
		});
		goal.on('result', function(result) {
			enableInput();
		});
		goal.send();
	}

	function executeMoveUp() {
		disableInput();
		var goal = new ROSLIB.Goal({
			actionClient: primitiveClient,
			goalMessage: {
				primitive_type: 0,
				axis: 2,
				distance: 0.1
			}
		});
		goal.on('feedback', function(feedback) {
			displayFeedback(feedback.feedback);
		});
		goal.on('result', function(result) {
			enableInput();
		});
		goal.send();
	}
	function executeMoveDown() {
		disableInput();
		var goal = new ROSLIB.Goal({
			actionClient: primitiveClient,
			goalMessage: {
				primitive_type: 0,
				axis: 2,
				distance: -0.1
			}
		});
		goal.on('feedback', function(feedback) {
			displayFeedback(feedback.feedback);
		});
		goal.on('result', function(result) {
			enableInput();
		});
		goal.send();
	}

	function executeRotateCW() {
		disableInput();
		var goal = new ROSLIB.Goal({
			actionClient: primitiveClient,
			goalMessage: {
				primitive_type: 1,
				axis: 0,
				distance: -1.5708
			}
		});
		goal.on('feedback', function(feedback) {
			displayFeedback(feedback.feedback);
		});
		goal.on('result', function(result) {
			enableInput();
		});
		goal.send();
	}
	function executeRotateCCW() {
		disableInput();
		var goal = new ROSLIB.Goal({
			actionClient: primitiveClient,
			goalMessage: {
				primitive_type: 1,
				axis: 0,
				distance: 1.5708
			}
		});
		goal.on('feedback', function(feedback) {
			displayFeedback(feedback.feedback);
		});
		goal.on('result', function(result) {
			enableInput();
		});
		goal.send();
	}

	/****************************************************************************
	 *                             Feedback                                     *
	 ****************************************************************************/

	function disableInput() {
		$('#img-plan').css("pointerEvents", "none");
		$('#img-gripper').css("pointerEvents", "none");
		$('#img-reset').css("pointerEvents", "none");
		$('#img-fb').css("pointerEvents", "none");
		$('#img-lr').css("pointerEvents", "none");
		$('#img-ud').css("pointerEvents", "none");
		$('#img-rotate').css("pointerEvents", "none");

		$('#moveArm').css("pointerEvents", "none");
		$('#openGripper').css("pointerEvents", "none");
		$('#closeGripper').css("pointerEvents", "none");
		$('#resetArm').css("pointerEvents", "none");
		$('#moveForward').css("pointerEvents", "none");
		$('#moveBack').css("pointerEvents", "none");
		$('#moveLeft').css("pointerEvents", "none");
		$('#moveRight').css("pointerEvents", "none");
		$('#moveUp').css("pointerEvents", "none");
		$('#moveDown').css("pointerEvents", "none");
		$('#rotateCW').css("pointerEvents", "none");
		$('#rotateCCW').css("pointerEvents", "none");

		$('#moveArm').prop("disabled", true);
		$('#openGripper').prop("disabled", true);
		$('#closeGripper').prop("disabled", true);
		$('#resetArm').prop("disabled", true);
		$('#moveForward').prop("disabled", true);
		$('#moveBack').prop("disabled", true);
		$('#moveLeft').prop("disabled", true);
		$('#moveRight').prop("disabled", true);
		$('#moveUp').prop("disabled", true);
		$('#moveDown').prop("disabled", true);
		$('#rotateCW').prop("disabled", true);
		$('#rotateCCW').prop("disabled", true);
	}

	function enableInput() {
		$('#img-plan').css("pointerEvents", "");
		$('#img-gripper').css("pointerEvents", "");
		$('#img-reset').css("pointerEvents", "");
		$('#img-fb').css("pointerEvents", "");
		$('#img-lr').css("pointerEvents", "");
		$('#img-ud').css("pointerEvents", "");
		$('#img-rotate').css("pointerEvents", "");

		$('#moveArm').css("pointerEvents", "");
		$('#openGripper').css("pointerEvents", "");
		$('#closeGripper').css("pointerEvents", "");
		$('#resetArm').css("pointerEvents", "");
		$('#moveForward').css("pointerEvents", "");
		$('#moveBack').css("pointerEvents", "");
		$('#moveLeft').css("pointerEvents", "");
		$('#moveRight').css("pointerEvents", "");
		$('#moveUp').css("pointerEvents", "");
		$('#moveDown').css("pointerEvents", "");
		$('#rotateCW').css("pointerEvents", "");
		$('#rotateCCW').css("pointerEvents", "");

		$('#moveArm').prop("disabled", false);
		$('#openGripper').prop("disabled", false);
		$('#closeGripper').prop("disabled", false);
		$('#resetArm').prop("disabled", false);
		$('#moveForward').prop("disabled", false);
		$('#moveBack').prop("disabled", false);
		$('#moveLeft').prop("disabled", false);
		$('#moveRight').prop("disabled", false);
		$('#moveUp').prop("disabled", false);
		$('#moveDown').prop("disabled", false);
		$('#rotateCW').prop("disabled", false);
		$('#rotateCCW').prop("disabled", false);
	}

	function displayFeedback(message) {
		document.getElementById("feedback-text").innerHTML = message;
	}


	//this is the topic for cartesian moving objects around
	var cartesian_move_topic = new ROSLIB.Topic({
			ros: _ROS,
			name: '/nimbus_moveit_wrapper/cartesian_control',
			messageType: 'geometry_msgs/Twist'
	});
	cartesian_move_topic.advertise();
	var size = 500
	<?php
		$streamTopics = '[';
		$streamNames = '[';
		foreach ($environment['Stream'] as $stream) {
			$streamTopics .= "'" . $stream['topic'] . "', ";
			$streamNames .= "'" . $stream['name'] . "', ";
		}
		// remove the final comma
		$streamTopics = substr($streamTopics, 0, strlen($streamTopics) - 2);
		$streamNames = substr($streamNames, 0, strlen($streamNames) - 2);
		$streamTopics .= ']';
		$streamNames .= ']';
	?>
	var mjpegcanvas=new MJPEGCANVAS.MultiStreamViewer({
		divID: 'mjpeg',
		host: '<?php echo $environment['Mjpeg']['host']; ?>',
		port: <?php echo $environment['Mjpeg']['port']; ?>,
		width: size,
		height: size * 0.85,
		quality: <?php echo $environment['Stream']?(($environment['Stream'][0]['quality']) ? $environment['Stream'][0]['quality'] : '90'):''; ?>,
		topics: <?php echo $streamTopics; ?>,
		labels: <?php echo $streamNames; ?>,
		tfObject:_TF,
		tf:'arm_mount_plate_link',
		refreshRate:'5'
	},EventEmitter);

	mjpegcanvas.interaction=function(linear,angular){
		var message=new ROSLIB.Message({
			'linear':linear,
			'angular':angular
		});
		cartesian_move_topic.publish(message);
	}
	var timer=null;
	var move_arm_x=null;
	var move_arm_y=null;
	var mjpeg_canvas_rect = mjpegcanvas.canvas.getBoundingClientRect();
	var speed=1; //a constant representing the speed of the interaction of the arm
	mjpegcanvas.canvas.addEventListener('mousemove',function(event){
		if (timer){
			clearTimeout(timer)
		}
		move_arm_x=event.clientX - mjpeg_canvas_rect.left- (mjpegcanvas.width/2)
		move_arm_y=mjpegcanvas.height-event.clientY - mjpeg_canvas_rect.top
		timers=setTimeout(move_arm,1000)
	})

	mjpegcanvas.canvas.addEventListener('mouseout',function(event){
		if (timer){
			clearTimeout(timer)
		}
	})

	function move_arm(x,y){
		var linear={'x':move_arm_x,'y':move_arm_y,'z':0};
		var point=MJPEGCANVAS.convertImageCoordinatestoWorldCoordinates(mjpegcanvas.transform,linear.x,linear.y,linear.z,mjpegcanvas.width,mjpegcanvas.height)
		var temp = point.z
		point.z=point.x
		point.x=temp
		console.log(linear)
		var message=new ROSLIB.Message({
			'linear':{x:0.0,y:-1.0,z:0.0},
			'angular':{x:0.0,y:0.0,z:0.0}
		});
		cartesian_move_topic.publish(message);
	}
	//add a set of interactive markers
  //  mjpegcanvas.addTopic('/nimbus_interactive_manipulation/update_full','visualization_msgs/InteractiveMarkerInit')

</script>
<script>
	 // var cartesian_move_topic = new ROSLIB.Topic({
	 //        ros: _ROS,
	 //        name: '/nimbus_moveit_wrapper/cartesian_control',
	 //        messageType: 'geometry_msgs/Twist'
	 //    });
	 //    cartesian_move_topic.advertise();
	 //    var message=new ROSLIB.Message({
	 //        'linear':{x:0.0,y:0.0,z:0.0},
	 //        'angular':{x:0.0,y:0.0,z:0.0}
	 //    });
	 //    cartesian_move_topic.publish(message);
</script>
</html
