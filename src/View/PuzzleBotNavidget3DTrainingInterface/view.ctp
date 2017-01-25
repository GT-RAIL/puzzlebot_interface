<?php
/**
 * Puzzle Bot Interface
 *
 * This is a 3d side by side view of the system with an interactive marker that overlays on the 2d video
 *
 * @author		Carl Saldanha csaldanha3@gatech.edu
 * @copyright	2016 Georgia Institute of Technology
 * @link		none 
 * @version		0.0.1
 * @package		app.Controller
 */
?>

<?php
//custom styling
echo $this->Html->css('PuzzleBot3DInterface');
?>

<style type="text/css">
	#mjpeg{
		position: relative;
	}
	#mjpeg canvas{
		position: absolute;
	}
	#mjpeg2{
		position: relative;
	}
	#mjpeg2 canvas{
		position: absolute;
	}
</style>

<html>
<head>
	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/three.js/r79/three.js'></script>
	<?php echo $this->Html->script('ColladaLoader.js');?>

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
			<td style="width: 22%; vertical-align:top;">
				<table>
					<tr>
						<td style="width: 30%; vertical-align:top; text-align:right">
							<div id="tasks" style="height=500px; text-align: right; background-color:rgba(232, 238, 244, 1.0); border-radius:20px; margin:5px; padding:20px">
								<b>Your Tasks:</b>
								<ul style="margin:0">
									<!--<li>Move the lemon onto the white plate</li>
									<li>Reset the arm when you're finished</li>-->
									
									<li>Move the lemon onto the white plate</li>
									<li>Reset the arm when you're finished</li>

								</ul>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div id="camera-controls" style="height=500px; text-align: right; background-color:rgba(232, 238, 244, 1.0); border-radius:20px; margin:5px; padding:20px">
								<b>View Controls:</b>
								<br />
								<button id='changeView' class='button special' style="width:150px">change view</button>
							</div>
						</td>
					</tr>
				</table>
			</td>
			<td style="width: 56%;">
				<table>
					<tr><td>
						<table>
							<tr>

								<td>
									<!-- <div id="mjpeg" style="text-align:center"></div> -->
									<div id='mjpeg' style=" width:500px;"><canvas id='mjpegcanvas'></canvas>  </div>
									<div id='mjpeg2'></div>
								</td>
								<td>
									<div id="viewer" style="text-align:center"></div>
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
					<li><b>Click the image</b> on the left to set a <b>grasping area</b>.</li>
					<li><b>Click on the sphere</b> to set a <b>grasp angle</b>.</li>
					<li><b>Adjust your grasp</b> by <b>clicking and dragging</b> the <b>ring and arrow</b> marker around the purple gripper.</li>
					<li>
						Use the <b>clear buttons</b> to <b>start a new grasp</b>.
						<br /><table style="margin-left:auto; margin-right:auto;">
							<tr>
								<td style="text-align:center; vertical-align:middle;" width="150px">
									<button id='clearGrasp' class='button special' style="width:150px">clear grasp</button>
								</td>
								<td style="vertical-align:middle;">
									<button id='clearAll' class='button special' style="width:150px">clear all</button>
								</td>
							</tr>
						</table>
					</li>
					<li>
						<b>Click the move button</b> below to automatically move the arm to your set position.
						<br /><table style="margin-left:auto; margin-right:auto;">
							<tr>
								<td style="text-align:center; vertical-align:middle;" width="150px">
									<button id='moveArm' class='button special' style="width:150px">move arm</button>
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
					<li>Use the <b>Arm Controls</b> below to manipulate objects.</li>
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
	/****************************************************************************
	 *                           Initial Logging                                *
	 ****************************************************************************/
	RMS.logString('new-session', 'navidget-3d-training')

	//var size = Math.min(((window.innerWidth / 2) - 120), window.innerHeight * 0.60);
	var size=500;

	_VIEWER = new ROS3D.Viewer({
		divID: 'viewer',
		width: size,
		height: size*0.75,
		antialias: true,
		background: '#50817b',
		intensity: 0.660000,
		//cameraPose: {x:0,y:0.106,z:1.201},
		cameraPose: {x:-0.059,y:-0.888,z:0.253},
		//center: {x:0.006538, y:0.316884, z:0.005329},
		center: {x:0.020235, y:0.042263, z:0.231021},
		fov: 45
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
		if ('<?php echo h($im['topic']); ?>' ==  '/constrained_positioning') {
			new ROS3D.InteractiveMarkerClient({
				ros: _ROS,
				tfClient: _TF,
				camera: _VIEWER.camera,
				rootObject: _VIEWER.selectableObjects,
				<?php echo isset($im['Collada']['id']) ? __('loader:%d,', h($im['Collada']['id'])) : ''; ?>
				<?php echo isset($im['Resource']['url']) ? __('path:"%s",', h($im['Resource']['url'])) : ''; ?>
				topic: '<?php echo h($im['topic']); ?>'
			});
		}
	<?php endforeach; ?>
		new ROS3D.InteractiveMarkerClient({
			ros: _ROS,
			tfClient: _TF,
			camera: _VIEWER.camera,
			rootObject: _VIEWER.selectableObjects,
			topic : '/constrained_positioning',
		});
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
		serverName: '/constrained_positioning/execute_grasp',
		actionName: 'remote_manipulation_markers/SpecifiedGraspAction'
	});
	var pointCloudClickClient = new ROSLIB.ActionClient({
		ros: _ROS,
		serverName: '/point_cloud_clicker/click_image_point_navidget',
		actionName: 'rail_agile_grasp_msgs/ClickImagePointAction'
	});

	//Setup ROS service clients
	var resetMarkerClient = new ROSLIB.Service({
		ros : _ROS,
		name : '/nimbus_6dof_planning/reset_marker_position',
		serviceType : 'std_srvs/Empty'
	});
	var clearGraspClient = new ROSLIB.Service({
		ros : _ROS,
		name : '/constrained_positioning/clear_gripper_marker',
		serviceType : 'std_srvs/Empty'
	});
	var clearAllClient = new ROSLIB.Service({
		ros : _ROS,
		name : '/constrained_positioning/clear_full_marker',
		serviceType : 'std_srvs/Empty'
	});
	var changePointCloudPCC = new ROSLIB.Service({
		ros : _ROS,
		name : '/point_cloud_clicker/change_point_cloud_topic',
		serviceType : 'rail_agile_grasp_msgs/ChangePointCloud'
	});


</script>

<script type="text/javascript">
	/****************************************************************************
	 *                          Global Variables                                *
	 ****************************************************************************/
	
	 var streams=['http://rail-engine.cc.gatech.edu'+ ':8080/stream?topic=/depthcloud_encoded&type=vp8&bitrate=50000&quality=100','http://rail-engine.cc.gatech.edu'+ ':8080/stream?topic=/depthcloud_encoded_side&type=vp8&bitrate=50000&quality=100'];
	 var cloudTopics=['/camera_side/depth_registered/points', '/camera/depth_registered/points'];
	 var pointClouds=[];  //points to the current stream being played
	 var current_stream_id=0;
	 var canvas=document.getElementById('mjpegcanvas');
	 canvas.width=size;
	 canvas.height=size*0.75;
	 var depthCloud;
	 var viewer;
	 //what a lie this is an asus node	 
	var kinectNodes=[];


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

	$('#moveArm').click(function (e) {
		e.preventDefault();
		executeGrasp();
	});

	$('#clearGrasp').click(function (e) {
		e.preventDefault();
		clearGrasp();
	});
	$('#clearAll').click(function (e) {
		e.preventDefault();
		clearAll();
	});

	
	/****************************************************************************
	 *                           Grasp Actions                                  *
	 ****************************************************************************/
	function executeGrasp() {
		disableInput();
		RMS.logString('manipulation-request', 'move-arm');
		var goal = new ROSLIB.Goal({
			actionClient: graspClient,
			goalMessage: {

			}
		});
		goal.on('feedback', function(feedback) {
			displayFeedback(feedback.message);
		});
		goal.on('result', function(result) {
			RMS.logString('manipulation-result', JSON.stringify(result));
			enableInput();
		});
		goal.send();
	}

	function clearGrasp() {
		RMS.logString('manipulation-request', 'clear-grasp');
		var request = new ROSLIB.ServiceRequest ({});
		clearGraspClient.callService(request, function(result){RMS.logString('manipulation-result', 'grasps cleared')});
	}

	function clearAll() {
		RMS.logString('manipulation-request', 'clear-all');
		var request = new ROSLIB.ServiceRequest ({});
		clearAllClient.callService(request, function(result){clickingDisabled = false; RMS.logString('manipulation-result', 'navidget marker reset');});
	}
	/****************************************************************************
	 *                         Primitive Actions                                *
	 ****************************************************************************/
	function executeResetArm() {
		disableInput();
		RMS.logString('primitive-request', 'reset-arm');
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
			RMS.logString('primitive-result', JSON.stringify(result));
			enableInput();
		});
		goal.send();
	}

	function executeOpenGripper() {
		disableInput();
		RMS.logString('primitive-request', 'open-gripper');
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
			RMS.logString('primitive-result', JSON.stringify(result));
			enableInput();
		});
		goal.send();
	}
	function executeCloseGripper() {
		disableInput();
		RMS.logString('primitive-request', 'close-gripper');
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
			RMS.logString('primitive-result', JSON.stringify(result));
			enableInput();
		});
		goal.send();
	}

	function executeMoveForward() {
		disableInput();
		RMS.logString('primitive-request', 'move-forward');
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
			RMS.logString('primitive-result', JSON.stringify(result));
			enableInput();
		});
		goal.send();
	}
	function executeMoveBack() {
		disableInput();
		RMS.logString('primitive-request', 'move-back');
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
			RMS.logString('primitive-result', JSON.stringify(result));
			enableInput();
		});
		goal.send();
	}

	function executeMoveLeft() {
		disableInput();
		RMS.logString('primitive-request', 'move-left');
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
			RMS.logString('primitive-result', JSON.stringify(result));
			enableInput();
		});
		goal.send();
	}
	function executeMoveRight() {
		disableInput();
		RMS.logString('primitive-request', 'move-right');
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
			RMS.logString('primitive-result', JSON.stringify(result));
			enableInput();
		});
		goal.send();
	}

	function executeMoveUp() {
		disableInput();
		RMS.logString('primitive-request', 'move-up');
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
			RMS.logString('primitive-result', JSON.stringify(result));
			enableInput();
		});
		goal.send();
	}
	function executeMoveDown() {
		disableInput();
		RMS.logString('primitive-request', 'move-down');
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
			RMS.logString('primitive-result', JSON.stringify(result));
			enableInput();
		});
		goal.send();
	}

	function executeRotateCW() {
		disableInput();
		RMS.logString('primitive-request', 'rotate-cw');
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
			RMS.logString('primitive-result', JSON.stringify(result));
			enableInput();
		});
		goal.send();
	}
	function executeRotateCCW() {
		disableInput();
		RMS.logString('primitive-request', 'rotate-ccw');
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
			RMS.logString('primitive-result', JSON.stringify(result));
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
		//TODO: Change stream
		current_stream_id=(current_stream_id+1) % streams.length;
		var request = new ROSLIB.ServiceRequest({
			cloudTopic: cloudTopics[current_stream_id]
		});
		for (var i=0;i<streams.length;i++){
			if(i!=current_stream_id){
				kinectNodes[i].visible=false;
			}
			else{
				kinectNodes[current_stream_id].visible=true;
			}
		}
		viewer.changeCamera(current_stream_id);
		changePointCloudPCC.callService(request, function(result) {});

		RMS.logString('change-view', 'camera ' + current_stream_id);
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

		clickingDisabled = true;
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

		clickingDisabled = false;
	}

	function displayFeedback(message) {
		document.getElementById("feedback-text").innerHTML = message;
	}


	function init() {
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

		var streams2=<?php echo  $streamTopics ?>;

		var videos=[];


		for(var i =0;i<streams2.length;i++){
			videos.push(document.createElement('video'));
			videos[i].src='http://rail-engine.cc.gatech.edu:8080/stream?topic='+streams2[i]+'&type=vp8&bitrate=50000&quality=10';
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
		viewer = new ROS3D.Viewer({
			divID : 'mjpeg2',
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

		// Setup the marker client.
		new ROS3D.InteractiveMarkerClient({
			ros : _ROS,
			tfClient : _TF,
			topic : '/nimbus_6dof_vis',
			camera : viewer.camera,
			rootObject : viewer.selectableObjects
		});

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

		//new ROS3D.UrdfClient({ros:_ROS,tfClient:_TF,rootObject:viewer.rootObject,loader:1,path:"http://rail-engine.cc.gatech.edu/urdf/",param:"robot_description"});
		//focal length done by hand tuning
		function register_depth_cloud(){
			var depthCloud = new ROS3D.DepthCloud({
				f:750,
      			url : streams[0],
      			width: 640,
  				height:480,
  				pointSize:3,
  				clickable:true,
  				viewer:_VIEWER,
  				pose : {position:{x:0.0,y:-0.01,z:0},orientation:{x:0,y:0.0,z:0.0}}
    		});
		    depthCloud.startStream();
			depthCloud.click=function(event3d){
				console.log(event3d.intersection.point);
				RMS.logString('manipulation-request', 'create-navidget-sphere');
				var goal = new ROSLIB.Goal({
					actionClient: pointCloudClickClient,
					goalMessage: {
						x: event3d.intersection.point.x,
						y:  event3d.intersection.point.y,
						imageWidth: 640,
						imageHeight:480
					}
				});
				displayFeedback('New Sphere Created');
				disableInput();
				goal.on('feedback', function (feedback) {
					displayFeedback(feedback.message)
				});
				goal.on('result', function (result) {
					RMS.logString('manipulation-result', JSON.stringify(result));
					enableInput();
				});
				goal.send();
			} 			

			// Create Kinect scene node
			var kinectNode = new ROS3D.SceneNode({
				frameID : '/camera_depth_optical_frame',
				tfClient : _TF,
				object : depthCloud,
  				pose : {position:{x:0.0,y:-0.01,z:0},orientation:{x:0,y:0.0,z:0.0}},
				visible : false
		    });

			pointClouds.push(depthCloud.video);
			var depthCloud2 = new ROS3D.DepthCloud({
			//side camera
      			url : streams[1],
      			f:850,
      			width: 640,
  				height:480,
  				pointSize:3,
  				clickable:true,
  				viewer:_VIEWER,
  				pose : {position:{x:0.08,y:-0.050,z:0},orientation:{x:0,y:0.0,z:0.0}}
    		});
			depthCloud2.click=function(event3d){
				RMS.logString('manipulation-request', 'create-navidget-sphere');
				var goal = new ROSLIB.Goal({
					actionClient: pointCloudClickClient,
					goalMessage: {
						x: event3d.intersection.point.x,
						y:  event3d.intersection.point.y,
						imageWidth: 640,
						imageHeight:480
					}
				});
				displayFeedback('New Sphere Created');
				disableInput();
				goal.on('feedback', function (feedback) {
					displayFeedback(feedback.message)
				});
				goal.on('result', function (result) {
					RMS.logString('manipulation-result', JSON.stringify(result));
					enableInput();
				});
				goal.send();
	    	};
	    	 
		    depthCloud2.startStream();

			// Create Kinect scene node
			var kinectNode2 = new ROS3D.SceneNode({
		      frameID : '/camera_side_depth_optical_frame',
		      tfClient : _TF,
		      object : depthCloud2, 
  				pose : {position:{x:0.08,y:-0.050,z:0},orientation:{x:0,y:0.0,z:0.0}}
		    });

			
		    pointClouds.push(depthCloud2.video);

			depthCloud.frame=kinectNode;
			depthCloud2.frame=kinectNode2;

			kinectNodes.push(kinectNode2);
			kinectNodes.push(kinectNode);
 
			_VIEWER.addObject(kinectNode2,true);
			_VIEWER.addObject(kinectNode,true);
			 
			function update_depth_pose(){
				// console.log(kinectNode2.pose);
				// depthCloud2.pickingScene.updatePose(kinectNode2.pose);	
				// console.log(depthCloud2.pickingScene.pose);
			}
			setTimeout(function(){update_depth_pose();},10000);
			
		}
		setTimeout(function(){register_depth_cloud();},2000);
		clickingDisabled = false;

	}
	$(document).ready(function(){init();});
	$('#viewer').on('click','canvas',function(event){
			RMS.logString('manipulation-request', 'canvas-click');
	})
	$('#viewer').on('mousedown','canvas',function(event){
		RMS.logString('manipulation-request', 'canvas-mousedown');
	})
	$('#viewer').on('mouseup','canvas',function(event){
		RMS.logString('manipulation-request', 'canvas-mouseup');
	})
</script>

</html>
