<?php
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
?>   

<?php
//custom styling
echo $this->Html->css('NimbusCrowdInterface');
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
	<?php echo $this->Html->script('ros3d.old.js');?>

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
	<table style="width:1280px !important; height:800px !important; margin-left:auto; margin-right:auto; border:1px solid black">
		<tr>
			<td>
				<table style="width:100% !important">
					<tr>
						<td>
							<div id="camera-feed" style="height:619px; margin:5px">
								<div id="mjpeg">
									<canvas id='mjpegcanvas'></canvas>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div id="change-view" style="text-align:center">
								<button id='changeView' class='button special' style="width:200px">change view</button>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div id="feedback-flash"  style="line-height:88px; position:relative;  text-align:center; background-color:rgba(232, 138, 144, 1.0); border-radius:24px; margin:5px">
							<div id="feedback"  style="line-height:88px; position:relative;  text-align:center; background-color:rgba(232, 238, 244, 1.0); border-radius:20px; margin:0px">
								<b><span id="feedback-text" style="display: inline-block; vertical-align: middle; line-height:1em">&nbsp;</span></b>
							</div>
							</div>
						</td>
					</tr>
				</table>
			</td>
			<td style="width: 33%; vertical-align:top;">
			<div id="controls" style="height:780px; text-align: left; background-color:rgba(232, 238, 244, 1.0); border-radius:20px; margin:5px; padding:20px">
				<table style="width:100% !important;">
					<tr>
						<td>
							<table style="width:100% !important;">
								<tr>
									<td style="width:50%; text-align:center">
										<button id='graspMode' class='button special' style="width:150px">Grasp</button>
									</td>
									<td style="text-align:center">
										<button id='placeMode' class='button special' style="width:150px">Place</button>
									</td>
								</tr>
								<tr>
									<td style="text-align:center">
										<button id='moveMode' class='button special' style="width:150px">Move</button>
									</td>
									<td style="text-align:center">
										<button id='commonMode' class='button special' style="width:150px">Common</button>
									</td>
								</tr>
							</table>
							<hr style="margin-bottom:10px;" />
						</td>
					</tr>
					<tr>
						<td style="text-align:center">
							<b><span id="current-mode">&nbsp;</span></b>

							<div id="mode-grasp" style="display:none">
								<table style="width:100% !important">
									<tr>
										<td style="line-height:110%;">
											<b>Click on the camera feed</b> to calculate grasp suggestions. <b>Cycle through</b> suggestions with the arrows below.
										</td>
									</tr>
									<tr>
										<td>
											<table style="width:100% !important">
												<tr>
													<td style="text-align:right">
														<map name="prev-map">
															<area shape="rect" coords="0,0,75,50" href="javascript:prevPose()">
														</map>
														<img id="img-prev-grasp" src="/img/Nimbus/nimbus-prev.png" height="50" width="75" style="vertical-align:middle" usemap="prev-map">
													</td>
													<td style="text-align:left">
														<map name="next-map">
															<area shape="rect" coords="0,0,75,50" href="javascript:nextPose()">
														</map>
														<img id="img-next-grasp" src="/img/Nimbus/nimbus-next.png" height="50" width="75" style="vertical-align:middle" usemap="next-map">
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<table style="width:100% !important">
												<tr>
													<td>
														<map name="refine-pose-map">
															<area shape="rect" coords="0,0,150,100" href="javascript:enableRefine()">
														</map>
														<img id="img-refine-pose" src="/img/Nimbus/nimbus-refine-pose.png" height="100" width="150" style="vertical-align:middle" usemap="refine-pose-map">
													</td>
													<td>
														<map name="plan-map">
															<area shape="rect" coords="0,0,150,100" href="javascript:executePose(0)">
														</map>
														<img id="img-plan-grasp" src="/img/Nimbus/nimbus-plan.png" height="100" width="150" style="vertical-align:middle" usemap="plan-map">
													</td>
												</tr>
												<tr>
													<td style="vertical-align:middle;">
														<button id='refineGrasp' class='button special' style="width:150px">refine</button>
													</td>
													<td style="vertical-align:middle;">
														<button id='executeGrasp' class='button special' style="width:150px">execute</button>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</div>

							<div id="mode-place" style="display:none">
								<table style="width:100% !important">
									<tr>
										<td style="line-height:110%;">
											<b>Click on the camera feed</b> to calculate place suggestions. <b>Cycle through</b> suggestions with the arrows below.
										</td>
									</tr>
									<tr>
										<td>
											<table style="width:100% !important">
												<tr>
													<td style="text-align:right">
														<map name="prev-place-map">
															<area shape="rect" coords="0,0,75,50" href="javascript:prevPose()">
														</map>
														<img id="img-prev-place" src="/img/Nimbus/nimbus-prev.png" height="50" width="75" style="vertical-align:middle" usemap="prev-place-map">
													</td>
													<td style="text-align:left">
														<map name="next-place-map">
															<area shape="rect" coords="0,0,75,50" href="javascript:nextPose()">
														</map>
														<img id="img-next-place" src="/img/Nimbus/nimbus-next.png" height="50" width="75" style="vertical-align:middle" usemap="next-place-map">
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<table style="width:100% !important;">
												<tr>
													<td>
														<map name="refine-place-map">
															<area shape="rect" coords="0,0,150,100" href="javascript:enableRefine()">
														</map>
														<img id="img-refine-place" src="/img/Nimbus/nimbus-refine-place.png" height="100" width="150" style="vertical-align:middle" usemap="refine-place-map">
													</td>
													<td>
														<map name="plan-place-map">
															<area shape="rect" coords="0,0,150,100" href="javascript:executePose(1)">
														</map>
														<img id="img-plan-place" src="/img/Nimbus/nimbus-move-to-place.png" height="100" width="150" style="vertical-align:middle" usemap="plan-place-map">
													</td>
												</tr>
												<tr>
													<td style="vertical-align:middle;">
														<button id='refinePlace' class='button special' style="width:150px">refine</button>
													</td>
													<td style="vertical-align:middle;">
														<button id='executePlace' class='button special' style="width:150px">execute</button>
													</td>
												</tr>
											</table>
										</td>
								</table>
							</div>

							<div id="mode-move" style="display:none">
								<table style="width:100% !important;">
									<tr>
										<td>
											<table style="width:100% !important">
												<tr>
													<td>
														<img id="img-move-side" src="/img/Nimbus/nimbus-move-side.png" height="100" width="150" style="vertical-align:middle">
													</td>
													<td>
														<img id="img-move-top" src="/img/Nimbus/nimbus-move-top.png" height="100" width="150" style="vertical-align:middle">
													</td>
												</tr>
											</table>

											<br />
										</td>
									</tr>
									<tr>
										<td style="line-height:110%;">
											<b>Click on the camera feed</b> to move the arm to the point you clicked.
											<br /><br />
										</td>
									</tr>
									<tr>
										<td style="line-height:110%;">
											The arm will move in a <b>straight line</b> to the clicked point.
											<br /><br />
										</td>
									</tr>
									<tr>
										<td style="line-height:110%;">
											<b>The arm will not avoid collisions!</b>
											<br /><br />
										</td>
									</tr>
								</table>
							</div>

							<div id="mode-common" style="display:none">
								<table style="width:100% !important;">
									<tr>
										<td>
											<map name="gripper-map">
												<area shape="rect" coords="0,0,45,100" href="javascript:executeOpenGripper()">
												<area shape="rect" coords="46,0,104,100" href="javascript:executeCloseGripper()">
												<area shape="rect" coords="105,0,150,100" href="javascript:executeOpenGripper()">
											</map>
											<img id="img-gripper" src="/img/Nimbus/nimbus-gripper.png" height="100" width="150" style="vertical-align:middle" usemap="#gripper-map">
										</td>
										<td>
											<map name="reset-map">
												<area shape="rect" coords="0,0,150,100" href="javascript:executeResetArm()">
											</map>
											<img id="img-reset" src="/img/Nimbus/nimbus-reset-arm.png" height="100" width="150" style="vertical-align:middle" usemap="#reset-map">
										</td>
									</tr>
									<tr>
										<td>
											<button id='openGripper' class='button special' style="width:150px; margin-left:auto !important; margin-right:auto !important;">open</button>
										</td>
										<td style="text-align:center; vertical-align:middle;">
											<button id='resetArm' class='button special' style="width:150px">reset arm</button>
										</td>
									</tr>
									<tr>
										<td>
											<button id='closeGripper' class='button special' style="width:150px; margin-left:auto !important; margin-right:auto !important;">close</button>
										</td>
										<td></td>
									</tr>
								</table>
							</div>
							<hr style="margin-bottom:10px;" />
						</td>
					</tr>
					<tr>
						<td>

							<div id="refine-mode" style="display:none;">
								<table style="width:100% !important;">
									<tr>
										<td>
											<div id="refine-point" style="display:none;">
												<table style="width:100% !important;">
													<tr>
														<td style="text-align:center;">
															<img id="img-refine-point" src="/img/Nimbus/nimbus-refine-point.png" height="100" width="150" style="vertical-align:middle">
														</td>
													</tr>
													<tr>
														<td style="line-height:110%; text-align:center">
															<b>Click and drag</b> the arrows around the purple gripper to change the <b>center point</b>.
														</td>
													</tr>
												</table>
											</div>
											<div id="refine-angle" style="display:none;">
												<table style="width:100% !important;">
													<tr>
														<td style="text-align:center;">
															<img id="img-refine-angle" src="/img/Nimbus/nimbus-refine-angle.png" height="100" width="150" style="vertical-align:middle">
														</td>
													</tr>
													<tr>
														<td style="line-height:110%; text-align:center;">
															<b>Click and drag</b> the arrows around the purple gripper to change the <b>grasp angle</b>.
														</td>
													</tr>
												</table>
											</div>
											<div id="refine-wrist" style="display:none;">
												<table style="width:100% !important;">
													<tr>
														<td style="text-align:center;">
															<img id="img-refine-wrist" src="/img/Nimbus/nimbus-refine-wrist.png" height="100" width="150" style="vertical-align:middle">
														</td>
													</tr>
													<tr>
														<td style="line-height:110%; text-align:center;">
															<b>Click and drag</b> the ring and arrow around the purple gripper to change the <b>wrist rotation</b> and <b>grasp depth</b>.
														</td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<table style="width:100% !important">
												<tr>
													<td><button id='refinePrev' class='button special' style="width:90px;">prev</button></td>
													<td><button id='refineNext' class='button special' style="width:90px;">next</button></td>
													<td><button id='refineDone' class='button special' style="width:90px;">done</button></td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
				</table>
			</div>
			</td>
		</tr>
	</table>
</body>

<script>
	/****************************************************************************
	 *                           Initial Logging                                *
	 ****************************************************************************/
	RMS.logString('new-session', 'nimbus-crowd-interface');

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
	var executePoseClient = new ROSLIB.ActionClient({
		ros: _ROS,
		serverName: '/click_and_refine/execute',
		actionName: 'remote_manipulation_markers/SpecifiedPoseAction'
	});
	var imageClickClient = new ROSLIB.ActionClient({
		ros: _ROS,
		serverName: '/click_handler/click_image_point',
		actionName: 'point_and_click/ClickImageAction'
	});


	//Setup ROS service clients
	var cycleGraspsClient = new ROSLIB.Service({
		ros : _ROS,
		name : '/click_and_refine/cycle_grasps',
		serviceType : 'remote_manipulation_markers/CycleGrasps'
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
	var changePointCloudCH = new ROSLIB.Service({
		ros : _ROS,
		name : '/click_handler/change_point_cloud_topic',
		serviceType : 'rail_agile_grasp_msgs/ChangePointCloud'
	});
	var changePointCloudPS = new ROSLIB.Service({
		ros : _ROS,
		name : '/place_sampler/change_point_cloud_topic',
		serviceType : 'rail_agile_grasp_msgs/ChangePointCloud'
	});
	var changeRefineModeClient = new ROSLIB.Service({
		ros : _ROS,
		name : '/click_and_refine/switch_mode',
		serviceType : 'remote_manipulation_markers/ModeSwitch'
	});
	var clearPosesClient = new ROSLIB.Service({
		ros : _ROS,
		name : '/click_and_refine/clear',
		serviceType : 'std_srvs/Empty'
	});


	//Setup ROS subscribers
	var graspPosesSubscriber = new ROSLIB.Topic({
		ros : _ROS,
		name : '/grasp_sampler/sampled_grasps',
		messageType : 'geometry_msgs/PoseArray'
	});
	graspPosesSubscriber.subscribe(function(message) {
		if (message.poses.length === 0)
		{
			displayFeedback('Could not find any grasps at the clicked point. Try clicking a different point.');
		}
	});
	var placePosesSubscriber = new ROSLIB.Topic({
		ros : _ROS,
		name : '/place_sampler/sampled_poses',
		messageType : 'geometry_msgs/PoseArray'
	});
	placePosesSubscriber.subscribe(function(message) {
		if (message.poses.length === 0)
		{
			displayFeedback('Could not find any grasps at the clicked point. Try clicking a different point.');
		}
		else
		{
			displayFeedback('Successfully calculated place poses at the clicked point.');
		}
		enableButtonInput();
		enableClickInput();
	});
	var agileSubscriber = new ROSLIB.Topic({
		ros : _ROS,
		name : '/point_cloud_clicker/click_image_point/feedback',
		messageType : 'rail_agile_grasp_msgs/ClickImagePointActionFeedback'
	});
	agileSubscriber.subscribe(function(message) {
		displayFeedback(message.feedback.message);
	});

</script>

<script type="text/javascript">
	/****************************************************************************
	 *                          Global Variables                                *
	 ****************************************************************************/
	//TODO populate from ROS
	var streams=['http://localhost'+ ':8080/stream?topic=/depthcloud_encoded_side&type=vp8&bitrate=50000&quality=100','http://localhost'+ ':8080/stream?topic=/depthcloud_encoded&type=vp8&bitrate=50000&quality=100'];
	var cloudTopics=['/camera_side/depth_registered/points', '/camera/depth_registered/points'];
	//points to the current stream being played
	var current_stream_id=0;

	var actionMode="";
	var refineMode=0;

	/****************************************************************************
	 *                         Action Mode Control                              *
	 ****************************************************************************/
	$('#graspMode').click(function (e) {
		e.preventDefault();
		document.getElementById("current-mode").innerHTML = "Grasp";
		hideRefine();
		hideActions();
		$('#mode-grasp').css("display", "inline");
		actionMode = "Grasp";

		var request = new ROSLIB.ServiceRequest({});
		clearPosesClient.callService(request, function(result) {
			displayFeedback('Grasp mode active.');
		});
	});

	$('#placeMode').click(function (e) {
		e.preventDefault();
		document.getElementById("current-mode").innerHTML = "Place";
		hideRefine();
		hideActions();
		$('#mode-place').css("display", "inline");
		actionMode = "Place";

		var request = new ROSLIB.ServiceRequest({});
		clearPosesClient.callService(request, function(result) {
			displayFeedback('Place mode active.');
		});
	});

	$('#moveMode').click(function (e) {
		e.preventDefault();
		document.getElementById("current-mode").innerHTML = "Move";
		hideRefine();
		hideActions();
		$('#mode-move').css("display", "inline");
		actionMode = "Move";

		var request = new ROSLIB.ServiceRequest({});
		clearPosesClient.callService(request, function(result) {
			displayFeedback('Move mode active.');
		});
	});

	$('#commonMode').click(function (e) {
		e.preventDefault();
		document.getElementById("current-mode").innerHTML = "Common";
		hideRefine();
		hideActions();
		$('#mode-common').css("display", "inline");
		actionMode = "Common";

		var request = new ROSLIB.ServiceRequest({});
		clearPosesClient.callService(request, function(result) {
			displayFeedback('Common mode active.');
		});
	});

	function hideActions() {
		$('#mode-grasp').css("display", "none");
		$('#mode-place').css("display", "none");
		$('#mode-move').css("display", "none");
		$('#mode-common').css("display", "none");
		actionMode = "";
	}


	/****************************************************************************
	 *                         Refine Mode Control                              *
	 ****************************************************************************/
	$('#refineGrasp').click(function (e) {
		e.preventDefault();
		enableRefine();
	});

	$('#refinePlace').click(function (e) {
		e.preventDefault();
		enableRefine();
	});

	$('#refinePrev').click(function (e) {
		e.preventDefault();
		setRefineMode(refineMode - 1);
	});

	$('#refineNext').click(function (e) {
		e.preventDefault();
		setRefineMode(refineMode + 1);
	});

	$('#refineDone').click(function (e) {
		e.preventDefault();
		hideRefine();
		var request = new ROSLIB.ServiceRequest({
			mode: 0
		});
		changeRefineModeClient.callService(request, function(result) {
			displayFeedback('Refine mode finished.');
		});
		enableButtonInput();
	});

	function enableRefine() {
		disableButtonInput();
		hideRefineInstructions();
		$('#refine-mode').css("display", "inline");
		setRefineMode(1);
	}

	function setRefineMode(newRefineMode) {
		hideRefineInstructions();
		if (newRefineMode === 1)
		{
			$('#refine-point').css("display", "inline");
			$('#refinePrev').css("pointerEvents", "none");
			$('#refinePrev').prop("disabled", true);
			$('#refineNext').css("pointerEvents", "");
			$('#refineNext').prop("disabled", false);

			var request = new ROSLIB.ServiceRequest({
				mode: 1
			});
			changeRefineModeClient.callService(request, function(result) {
				displayFeedback('Point refine mode active.');
			});
		}
		else if (newRefineMode === 2)
		{
			$('#refine-angle').css("display", "inline");
			$('#refinePrev').css("pointerEvents", "");
			$('#refinePrev').prop("disabled", false);
			$('#refineNext').css("pointerEvents", "");
			$('#refineNext').prop("disabled", false);

			var request = new ROSLIB.ServiceRequest({
				mode: 2
			});
			changeRefineModeClient.callService(request, function(result) {
				displayFeedback('Angle refine mode active.');
			});
		}
		else if (newRefineMode === 3)
		{
			$('#refine-wrist').css("display", "inline");
			$('#refinePrev').css("pointerEvents", "");
			$('#refinePrev').prop("disabled", false);
			$('#refineNext').css("pointerEvents", "none");
			$('#refineNext').prop("disabled", true);

			var request = new ROSLIB.ServiceRequest({
				mode: 3
			});
			changeRefineModeClient.callService(request, function(result) {
				displayFeedback('Wrist refine mode active.');
			});
		}
		refineMode = newRefineMode;
	}

	function hideRefine() {
		hideRefineInstructions();
		$('#refine-mode').css("display", "none");
		refineMode = 0;
	}

	function hideRefineInstructions() {
		$('#refine-point').css("display", "none");
		$('#refine-angle').css("display", "none");
		$('#refine-wrist').css("display", "none");
	}

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

	/****************************************************************************
	 *                           Grasp Actions                                  *
	 ****************************************************************************/
	$('#executeGrasp').click(function (e) {
		e.preventDefault();
		executePose(0);
	});

	/****************************************************************************
	 *                           Place Actions                                  *
	 ****************************************************************************/
	$('#executePlace').click(function (e) {
		e.preventDefault();
		executePose(1);
	});

	/****************************************************************************
	 *                    Pose Planning Common Actions                          *
	 ****************************************************************************/
	function executePose(actionId) {
		disableButtonInput();
		disableClickInput();

		var goal = new ROSLIB.Goal({
			actionClient: executePoseClient,
			goalMessage: {
				action: actionId
			}
		});
		goal.on('feedback', function(feedback) {
			displayFeedback(feedback.message);
		});
		goal.on('result', function(result) {
			enableButtonInput();
			enableClickInput();
		});
		goal.send();
	}

	function prevPose() {
		disableButtonInput();
		disableClickInput();
		//RMS.logString('manipulation-request', 'prev-grasp');
		var request = new ROSLIB.ServiceRequest({
			forward: false
		});
		cycleGraspsClient.callService(request, function(result) {
			//RMS.logString('manipulation-result', JSON.stringify(result));
			displayFeedback('Displaying pose ' + (result.index + 1));
		});
		enableButtonInput();
		enableClickInput();
	}

	function nextPose() {
		disableButtonInput();
		disableClickInput();
		//RMS.logString('manipulation-request', 'next-grasp');
		var request = new ROSLIB.ServiceRequest({
			forward: true
		});
		cycleGraspsClient.callService(request, function(result) {
			//RMS.logString('manipulation-result', JSON.stringify(result));
			displayFeedback('Displaying pose ' + (result.index + 1));
		});
		enableButtonInput();
		enableClickInput();
	}

	/****************************************************************************
	 *                            Move Actions                                  *
	 ****************************************************************************/
	function executeMove(x, y, w, h) {
		disableButtonInput();
		disableClickInput();

		var goal = new ROSLIB.Goal({
			actionClient: imageClickClient,
			goalMessage: {
				x: x,
				y: y,
				imageWidth: w,
				imageHeight: h,
				action: 2
			}
		});
		goal.on('feedback', function(feedback) {
			displayFeedback(feedback.message);
		});
		goal.on('result', function(result) {
			//RMS.logString('primitive-result', JSON.stringify(result));
			console.log("Move action completed with completion amount:");
			console.log(result.completion);
			enableButtonInput();
			enableClickInput();
		});
		goal.send();
	}

	/****************************************************************************
	 *                           Common Actions                                 *
	 ****************************************************************************/
	function executeResetArm() {
		disableButtonInput();
		disableClickInput();
		//RMS.logString('primitive-request', 'reset-arm');
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
			//RMS.logString('primitive-result', JSON.stringify(result));
			enableButtonInput();
			enableClickInput();
		});
		goal.send();
	}

	function executeOpenGripper() {
		disableButtonInput();
		disableClickInput();
		//RMS.logString('primitive-request', 'open-gripper');
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
			//RMS.logString('primitive-result', JSON.stringify(result));
			enableButtonInput();
			enableClickInput();
		});
		goal.send();
	}
	function executeCloseGripper() {
		disableButtonInput();
		disableClickInput();
		//RMS.logString('primitive-request', 'close-gripper');
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
			//RMS.logString('primitive-result', JSON.stringify(result));
			enableButtonInput();
			enableClickInput();
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
		current_stream_id=(current_stream_id+1) % streams.length;
		var request = new ROSLIB.ServiceRequest({
			cloudTopic: cloudTopics[current_stream_id]
		});
		viewer.changeCamera(current_stream_id);
		changePointCloudGS.callService(request, function(result) {});
		changePointCloudPCC.callService(request, function(result) {});
		changePointCloudRAG.callService(request, function(result) {});
		changePointCloudCH.callService(request, function(result) {});
		changePointCloudPS.callService(request, function(result) {});

		RMS.logString('change-view', 'camera ' + current_stream_id);
	}

	/****************************************************************************
	 *                             Feedback                                     *
	 ****************************************************************************/

	function disableButtonInput() {
		$('#img-prev-grasp').css("pointerEvents", "none");
		$('#img-next-grasp').css("pointerEvents", "none");
		$('#img-refine-pose').css("pointerEvents", "none");
		$('#img-plan-grasp').css("pointerEvents", "none");
		$('#img-prev-place').css("pointerEvents", "none");
		$('#img-next-place').css("pointerEvents", "none");
		$('#img-refine-place').css("pointerEvents", "none");
		$('#img-plan-place').css("pointerEvents", "none");
		$('#img-gripper').css("pointerEvents", "none");
		$('#img-reset').css("pointerEvents", "none");

		$('#graspMode').css("pointerEvents", "none");
		$('#placeMode').css("pointerEvents", "none");
		$('#moveMode').css("pointerEvents", "none");
		$('#commonMode').css("pointerEvents", "none");
		$('#refineGrasp').css("pointerEvents", "none");
		$('#executeGrasp').css("pointerEvents", "none");
		$('#refinePlace').css("pointerEvents", "none");
		$('#executePlace').css("pointerEvents", "none");
		$('#openGripper').css("pointerEvents", "none");
		$('#resetArm').css("pointerEvents", "none");
		$('#closeGripper').css("pointerEvents", "none");

		$('#graspMode').prop("disabled", true);
		$('#placeMode').prop("disabled", true);
		$('#moveMode').prop("disabled", true);
		$('#commonMode').prop("disabled", true);
		$('#refineGrasp').prop("disabled", true);
		$('#executeGrasp').prop("disabled", true);
		$('#refinePlace').prop("disabled", true);
		$('#executePlace').prop("disabled", true);
		$('#openGripper').prop("disabled", true);
		$('#resetArm').prop("disabled", true);
		$('#closeGripper').prop("disabled", true);
	}

	function disableClickInput() {
		clickingDisabled = true;
	}

	function enableButtonInput() {
		$('#img-prev-grasp').css("pointerEvents", "");
		$('#img-next-grasp').css("pointerEvents", "");
		$('#img-refine-pose').css("pointerEvents", "");
		$('#img-plan-grasp').css("pointerEvents", "");
		$('#img-prev-place').css("pointerEvents", "");
		$('#img-next-place').css("pointerEvents", "");
		$('#img-refine-place').css("pointerEvents", "");
		$('#img-plan-place').css("pointerEvents", "");
		$('#img-gripper').css("pointerEvents", "");
		$('#img-reset').css("pointerEvents", "");

		$('#graspMode').css("pointerEvents", "");
		$('#placeMode').css("pointerEvents", "");
		$('#moveMode').css("pointerEvents", "");
		$('#commonMode').css("pointerEvents", "");
		$('#refineGrasp').css("pointerEvents", "");
		$('#executeGrasp').css("pointerEvents", "");
		$('#refinePlace').css("pointerEvents", "");
		$('#executePlace').css("pointerEvents", "");
		$('#openGripper').css("pointerEvents", "");
		$('#resetArm').css("pointerEvents", "");
		$('#closeGripper').css("pointerEvents", "");

		$('#shallowGrasp').prop("disabled", false);
		$('#graspMode').prop("disabled", false);
		$('#placeMode').prop("disabled", false);
		$('#moveMode').prop("disabled", false);
		$('#commonMode').prop("disabled", false);
		$('#refineGrasp').prop("disabled", false);
		$('#executeGrasp').prop("disabled", false);
		$('#refinePlace').prop("disabled", false);
		$('#executePlace').prop("disabled", false);
		$('#openGripper').prop("disabled", false);
		$('#resetArm').prop("disabled", false);
		$('#closeGripper').prop("disabled", false);
	}

	function enableClickInput() {
		clickingDisabled = false;
	}

	function displayFeedback(message) {
		document.getElementById("feedback-text").innerHTML = message;
		$('#feedback').fadeTo(50, 0.5, function() { $(this).fadeTo(300, 1.0)});
	}


	/****************************************************************************
	 *                              Setup                                       *
	 ****************************************************************************/
	var size = 826;
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

	var streams=<?php echo  $streamTopics ?>;
	var canvas=document.getElementById('mjpegcanvas');
	canvas.width=size;
	canvas.height=size * 0.75;

	var videos=[];


	for(var i =0;i<streams.length;i++){
		videos.push(document.createElement('video'));
		videos[i].src='http://localhost:8080/stream?topic='+streams[i]+'&type=vp8&bitrate=50000&quality=10';
		videos[i].crossOrigin = 'Anonymous';
		videos[i].setAttribute('crossorigin', 'Anonymous');
		videos[i].play();
	}

	videos[0].addEventListener('play',function()	{
			//TODO fix this width
		draw(canvas.getContext("2d"),size,size*0.75);
    },false);

	function draw(c,w,h) {
		c.drawImage(videos[current_stream_id],x=0,y=0,width=w,height=h);
		setTimeout(draw,200,c,w,h);
	}

	// Create the main viewer
	var viewer = new ROS3D.Viewer({
		divID : 'mjpeg',
		width: size,
		height: size*0.75,
		antialias: true,
		intensity: 0.660000,
		cameraPose : {x:-0.107,y:-1.227,z:0.329}, //hand-tuned
		//cameraPose : {x:-0.107,y:-1.177,z:0.329}, //original
		center: {x:0.005608, y:0.042784, z:0.262058}, //hand-tuned
		//center: {x:0.015608, y:0.042784, z:0.247058}, //original
		fov: 45,
		alpha: 0.1,
		near: 0.1, //from P. Grice's code  https://github.com/gt-ros-pkg/hrl-assistive/blob/indigo-devel/assistive_teleop/vci-www/js/video/viewer.js
		far: 50,
		interactive:false,
		tfClient: _TF
	});

	//new ROS3D.UrdfClient({ros:_ROS,tfClient:_TF,rootObject:viewer.rootObject,loader:1,path:"http://rail-engine.cc.gatech.edu/urdf/",param:"robot_description"});

	var camera2=new ROS3D.ViewerCamera({
		near:0.1,
		far:50,
		fov:45,
		aspect:size/(size*0.75),
		rootObjectPose : {position:{x:0.025,y:0.118,z:1.287},rotation:{x:0,y:0,z:0}}, //hand-tuned
		//rootObjectPose : {position:{x:0.025,y:0.118,z:1.197},rotation:{x:0,y:0,z:0}}, //original
		cameraPosition : {x:0.025,y:0.118,z:1.287}, //hand-tuned
		//cameraPosition : {x:0.025,y:0.118,z:1.197}, //original
		center: {x:0.021832, y:0.368916, z:0.000150}, //hand-tuned
		//center: {x:0.021832, y:0.388916, z:0.000150}, //original
		tfClient: _TF  //for the asus overhead camera
	});

	viewer.addCamera(camera2);

	// Setup the marker client.
	var imClient = new ROS3D.InteractiveMarkerClient({
		ros : _ROS,
		tfClient : _TF,
		topic : '/click_and_refine',
		camera : viewer.camera,
		rootObject : viewer.selectableObjects
	});

	var clickingDisabled = false;

	/****************************************************************************
	 *                      Manipulation Planning                               *
	 ****************************************************************************/
	$('#mjpeg').on('click','canvas',function(event){
		//TODO: change this based on action and refine mode
		console.log("canvas click, in mode: ", actionMode);
		if (actionMode === "Grasp")
		{
			if (refineMode === 0) {
				if (!clickingDisabled) {
					console.log("click");
					disableButtonInput();
					disableClickInput();
					//RMS.logString('manipulation-request', 'calculate-grasps');
					var rect = $(this)[0].getBoundingClientRect();
					var goal = new ROSLIB.Goal({
						actionClient: imageClickClient,
						goalMessage: {
							x: event.clientX - rect.left,
							y: event.clientY - rect.top,
							imageWidth: size,
							imageHeight: Math.round(size * 0.75),
							action: 0
						}
					});
					goal.on('feedback', function (feedback) {
						displayFeedback(feedback.message);
					});
					goal.on('result', function (result) {
						//RMS.logString('manipulation-result', JSON.stringify(result));

						enableButtonInput();
						enableClickInput();
					});
					goal.send();
				}
			}
		}
		else if (actionMode === "Place")
		{
			if (refineMode === 0)
			{
				if (!clickingDisabled) {
					console.log("click");
					disableButtonInput();
					disableClickInput();
					//RMS.logString('manipulation-request', 'calculate-grasps');
					var rect = $(this)[0].getBoundingClientRect();
					var goal = new ROSLIB.Goal({
						actionClient: imageClickClient,
						goalMessage: {
							x: event.clientX - rect.left,
							y: event.clientY - rect.top,
							imageWidth: size,
							imageHeight: Math.round(size * 0.75),
							action: 1
						}
					});
					goal.on('feedback', function (feedback) {
						displayFeedback(feedback.message);
					});
					goal.on('result', function (result) {
						//RMS.logString('manipulation-result', JSON.stringify(result));

						console.log(result);
						if (result.completion === 0) {
							console.log("re-enable!!");
							enableButtonInput();
							enableClickInput();
						}
					});
					goal.send();
				}
			}
		}
		else if (actionMode === "Move")
		{
			console.log("Move callback");
			if (!clickingDisabled) {
				console.log("Sending click goal...");
				var rect = $(this)[0].getBoundingClientRect();
				executeMove(event.clientX - rect.left, event.clientY - rect.top, size, Math.round(size*0.75));
				console.log("Click goal sent.");
			}
		}
		else if (actionMode === "Common")
		{

		}

	})

</script>

</html>
