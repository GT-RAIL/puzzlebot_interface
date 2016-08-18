<?php
/**
 * Puzzle Bot Interface
 *
 * This uses agile grasp to add user clicks to the sytem 
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
echo $this->Html->css('PuzzleBotClickInterface');
?>

<style type="text/css">
	#mjpeg{
		position: relative;
	}
	#mjpeg canvas{
		position: absolute;
	}
</style>

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
	<?php echo $this->Html->script('ros3d.js');?>

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
			<td style="width: 30%; vertical-align:top; text-align:right">
				<table>
					<tr>
						<td style="width: 30%; vertical-align:top; text-align:right">
							<div id="tasks" style="text-align: right; background-color:rgba(232, 238, 244, 1.0); border-radius:20px; margin:5px; padding:20px">
								<b>Your Tasks:</b>
								<ul style="margin:0">
									<li>Pull the cart across the green line</li>
									<li>Open the box</li>
									<li>Open the bottle</li>
								</ul>
							</div>
						</td>
					</tr>
					<tr>
						<td >
							<div id="camera-controls" style="text-align: right; background-color:rgba(232, 238, 244, 1.0); border-radius:20px; margin:5px; padding:20px">
								<b>View Controls</b>
								<br />
								<button id='changeView' class='button special' style="width:150px">change view</button>
							</div>
						</td>
					</tr>
				</table>
					<!--
					<hr>
					<b>Switch Cameras:</b>
					<ul style="margin:0">
						<select id='mjpegcanvas_select'>
							<?php foreach ($environment['Stream'] as $stream):?>
							<option value='<?php echo $stream['topic']?>'><?php echo $stream['name']?></option>
							<?php endforeach;?>
						</select>
					</ul>
					-->
			</td>
			<td>
				<table>
					<tr>
						<td style="width:500px; height:435px;">
							<div id="mjpeg" style="text-align:center; width:40%"></div>
						</td>
					</tr>
					<tr>
						<td style="text-align:center; background-color:rgba(232, 238, 244, 1.0); border-radius:20px; width:40%">
							<b><span id="feedback-text">&nbsp;</span></b>
						</td>
					</tr>
				</table>
			</td>
			<td style="width: 30%; vertical-align:top;">
				<div id="instructions" style="height=500px; text-align: left; background-color:rgba(232, 238, 244, 1.0); border-radius:20px; margin:5px; padding:20px">
					<b>Instructions:</b>
					<ol type="1" style="list-style-type:decimal; margin-left:15px; margin-bottom:0px;">
					<li><b>Set a position</b> for the gripper by <b>clicking in the 3D scene</b> on the left.</li>
					<li>
						<b>Cycle through suggestions</b> to choose a good grasp.
						<br><table style="margin-left:auto; margin-right:auto">
							<tr>
								<td>
									<map name="prev-map">
										<area shape="rect" coords="0,0,75,50" href="javascript:prevGrasp()">
									</map>
									<img id="img-prev" src="/img/Nimbus/nimbus-prev.png" height="50" width="75" style="vertical-align:middle" usemap="prev-map">
								</td>
								<td>
									<map name="next-map">
										<area shape="rect" coords="0,0,75,50" href="javascript:nextGrasp()">
									</map>
									<img id="img-next" src="/img/Nimbus/nimbus-next.png" height="50" width="75" style="vertical-align:middle" usemap="next-map">
								</td>
							</tr>
						</table>
					</li>
					<li>
						Select <b>Shallow</b> or <b>Deep Grasp</b> and the robot will automatically move to your selected position.
						<br /><table style="margin-left:auto; margin-right:auto;">
							<tr>
								<td style="vertical-align:middle;">
									<map name="shallow-grasp-map">
										<area shape="rect" coords="0,0,75,50" href="javascript:executeShallowGrasp()">
									</map>
									<img id="img-shallow" src="/img/Nimbus/nimbus-shallow-grasp.png" height="50" width="75" style="vertical-align:middle" usemap="shallow-grasp-map">
								</td>
								<td style="text-align:center" width="200px">
									<button id='shallowGrasp' class='button special' style="width:190px">shallow grasp</button>
								</td>
							</tr>
							<tr>
								<td style="vertical-align:middle;">
									<map name="deep-grasp-map">
										<area shape="rect" coords="0,0,75,50" href="javascript:executeShallowGrasp()">
									</map>
									<img id="img-deep" src="/img/Nimbus/nimbus-deep-grasp.png" height="50" width="75" style="vertical-align:middle" usemap="deep-grasp-map">
								</td>
								<td style="text-align:center" width="200px">
									<button id='deepGrasp' class='button special' style="width:190px">deep grasp</button>
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
	

</script>

<script>
	//Setup ROS action clients
	var armClient = new ROSLIB.ActionClient({
		ros: _ROS,
		serverName: '/nimbus_moveit/common_actions/arm_action',
		actionName: 'rail_manipulation_msgs/ArmAction'
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
	var graspClient = new ROSLIB.ActionClient({
		ros: _ROS,
		serverName: '/grasp_selector/execute_grasp',
		actionName: 'rail_agile_grasp_msgs/SelectedGraspAction'
	});
	var pointCloudClickClient = new ROSLIB.ActionClient({
		ros: _ROS,
		serverName: '/point_cloud_clicker/click_image_point',
		actionName: 'rail_agile_grasp_msgs/ClickImagePointAction'
	});

	//Setup ROS service clients
	var cycleGraspsClient = new ROSLIB.Service({
		ros : _ROS,
		name : '/grasp_selector/cycle_grasps',
		serviceType : 'rail_agile_grasp_msgs/CycleGrasps'
	});
	var changePointCloudGS = new ROSLIB.Service({
		ros : _ROS,
		name : '/grasp_sampler/change_point_cloud_topic',
		serviceType : 'rail_agile_grasp_msgs/ChangePointCloud'
	});
	var changePointCloudPCC = new ROSLIB.Service({
		ros : _ROS,
		name : '/point_cloud_clicker/change_point_cloud_topic',
		serviceType : 'rail_agile_grasp_msgs/ChangePointCloud'
	});
	var changePointCloudRAG = new ROSLIB.Service({
		ros : _ROS,
		name : '/rail_agile_grasp/change_point_cloud_topic',
		serviceType : 'rail_agile_grasp_msgs/ChangePointCloud'
	});

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
		e.preventDefault();
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

	$('#shallowGrasp').click(function (e) {
		e.preventDefault();
		executeShallowGrasp();
	});
	$('#deepGrasp').click(function (e) {
		e.preventDefault();
		executeDeepGrasp();
	});

	$('#mjpegcanvas_select').change(function(e){
		e.preventDefault();
		mjpegcanvas.changeStream(this.value);
	});

	/****************************************************************************
	 *                           Grasp Actions                                  *
	 ****************************************************************************/
	function prevGrasp() {
		var request = new ROSLIB.ServiceRequest({
			forward: false
		});
		cycleGraspsClient.callService(request, function(result) {});
	}

	function nextGrasp() {
		var request = new ROSLIB.ServiceRequest({
			forward: true
		});
		cycleGraspsClient.callService(request, function(result) {});
	}

	function executeShallowGrasp() {
		disableInput();
		var goal = new ROSLIB.Goal({
			actionClient: graspClient,
			goalMessage: {
				shallow: true
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

	function executeDeepGrasp() {
		disableInput();
		var goal = new ROSLIB.Goal({
			actionClient: graspClient,
			goalMessage: {
				shallow: false
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
	 *                           View Actions                                   *
	 ****************************************************************************/
	$('#changeView').click(function (e) {
		e.preventDefault();
		switchCamera();
	});

	function switchCamera() {
		//TODO: Change stream, populate cloudTopic correctly
		var request = new ROSLIB.ServiceRequest({
			cloudTopic: "new_cloud_topic"
		});
		changePointCloudGS.callService(request, function(result) {});
		changePointCloudPCC.callService(request, function(result) {});
		changePointCloudRAG.callService(request, function(result) {});
	}

	/****************************************************************************
	 *                             Feedback                                     *
	 ****************************************************************************/

	function disableInput() {
		$('#img-prev').css("pointerEvents", "none");
		$('#img-next').css("pointerEvents", "none");
		$('#img-shallow').css("pointerEvents", "none");
		$('#img-deep').css("pointerEvents", "none");
		$('#img-gripper').css("pointerEvents", "none");
		$('#img-reset').css("pointerEvents", "none");
		$('#img-fb').css("pointerEvents", "none");
		$('#img-lr').css("pointerEvents", "none");
		$('#img-ud').css("pointerEvents", "none");
		$('#img-rotate').css("pointerEvents", "none");

		$('#shallowGrasp').css("pointerEvents", "none");
		$('#deepGrasp').css("pointerEvents", "none");
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

		$('#shallowGrasp').prop("disabled", true);
		$('#deepGrasp').prop("disabled", true);
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
		$('#img-prev').css("pointerEvents", "");
		$('#img-next').css("pointerEvents", "");
		$('#img-shallow').css("pointerEvents", "");
		$('#img-deep').css("pointerEvents", "");
		$('#img-gripper').css("pointerEvents", "");
		$('#img-reset').css("pointerEvents", "");
		$('#img-fb').css("pointerEvents", "");
		$('#img-lr').css("pointerEvents", "");
		$('#img-ud').css("pointerEvents", "");
		$('#img-rotate').css("pointerEvents", "");

		$('#shallowGrasp').css("pointerEvents", "");
		$('#deepGrasp').css("pointerEvents", "");
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

		$('#shallowGrasp').prop("disabled", false);
		$('#deepGrasp').prop("disabled", false);
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

	var size = 500;
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


	// Create the main viewer
	var viewer = new ROS3D.Viewer({
		divID : 'mjpeg',
		width : size,
		height : size * 0.85,
		antialias : true,
		alpha: 0.1,
		near: 0.1, //from P. Grice's code  https://github.com/gt-ros-pkg/hrl-assistive/blob/indigo-devel/assistive_teleop/vci-www/js/video/viewer.js
		far: 50,
		fov: 50,//50, //from ASUS documentation -https://www.asus.com/us/3D-Sensor/Xtion_PRO_LIVE/specifications/
		cameraPose:{x:-0.05,y:0.42,z:-0.05},
		//cameraPosition:{x:0.25,y:0,z:-0.5}, //kinect 2
		cameraRotation:{x:-0.34,y:0,z:3.15}, //this is when the interactive markers base frame is set to table instead of jaco
		frame: '/camera_rgb_optical_frame',
		interactive:false,
		tfClient: _TF
	});

	// Setup the marker client.
	var imClient = new ROS3D.InteractiveMarkerClient({
		ros : _ROS,
		tfClient : _TF,
		topic : '/grasp_selector',
		camera : viewer.camera,
		rootObject : viewer.selectableObjects
	});

	$('#mjpeg').on('click','canvas',function(event){
		disableInput();
		var rect = $(this)[0].getBoundingClientRect();
		var goal = new ROSLIB.Goal({
			actionClient: pointCloudClickClient,
			goalMessage: {
				x:event.clientX - rect.left,
				y:event.clientY -rect.top,
				imageWidth:size,
				imageHeight:size*0.85
			}
		});
		goal.on('feedback', function(feedback) {
			displayFeedback(feedback.message);
		});
		goal.on('result', function(result) {
			enableInput();
		});
		goal.send();
	})

</script>

</html>
