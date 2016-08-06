<?php
/**
 * Puzzle Bot Interface
 *
 * This is a 2d  view of the system with a 3d interactive marker that moves the robot with the interaction
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

	<style type="text/css">
		  #markers{
		    position: relative;
		  }
		  #markers canvas{
		    position: absolute;
		    top:0;
		    left:30px;
		  }
	</style>

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
			<td style="width: 56%">
				<div id="markers" style="text-align:center"></div>
			</td>
			<td style="width: 22%; vertical-align:top;">
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
									<img src="/img/Nimbus/nimbus-prev.png" height="50" width="75" style="vertical-align:middle" usemap="prev-map">
								</td>
								<td>
									<map name="next-map">
										<area shape="rect" coords="0,0,75,50" href="javascript:nextGrasp()">
									</map>
									<img src="/img/Nimbus/nimbus-next.png" height="50" width="75" style="vertical-align:middle" usemap="next-map">
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
									<img src="/img/Nimbus/nimbus-shallow-grasp.png" height="50" width="75" style="vertical-align:middle" usemap="shallow-grasp-map">
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
									<img src="/img/Nimbus/nimbus-deep-grasp.png" height="50" width="75" style="vertical-align:middle" usemap="deep-grasp-map">
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
							<img src="/img/Nimbus/nimbus-gripper.png" height="100" width="150" style="vertical-align:middle" usemap="#gripper-map">
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
							<img src="/img/Nimbus/nimbus-reset-arm.png" height="100" width="150" style="vertical-align:middle" usemap="#reset-map">
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
							<img src="/img/Nimbus/nimbus-forward-back.png" height="100" width="150" style="vertical-align:middle" usemap="#fb-map">
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
							<img src="/img/Nimbus/nimbus-left-right.png" height="100" width="150" style="vertical-align:middle" usemap="#lr-map">
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
							<img src="/img/Nimbus/nimbus-up-down.png" height="100" width="150" style="vertical-align:middle" usemap="#ud-map">
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
							<img src="/img/Nimbus/nimbus-rotate-wrist.png" height="100" width="150" style="vertical-align:middle" usemap="#rotate-map">
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
	var graspClient = new ROSLIB.ActionClient({
		ros: _ROS,
		serverName: '/grasp_selector/execute_grasp',
		actionName: 'rail_agile_grasp_msgs/SelectedGraspAction'
	})

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

	$('#shallowGrasp').click(function (e) {
		e.preventDefault();
		executeShallowGrasp();
	});
	$('#deepGrasp').click(function (e) {
		e.preventDefault();
		executeDeepGrasp();
	});

	/****************************************************************************
	 *                           Grasp Actions                                  *
	 ****************************************************************************/
	function prevGrasp() {
		var request = new ROSLIB.ServiceRequest({
			forward: false
		});
		segmentClient.callService(request, function(result) {});
	}

	function nextGrasp() {
		var request = new ROSLIB.ServiceRequest({
			forward: true
		});
		segmentClient.callService(request, function(result) {});
	}

	function executeShallowGrasp() {
		var goal = new ROSLIB.Goal({
			actionClient: graspClient,
			goalMessage: {
				shallow: true
			}
		});
		goal.send();
	}

	function executeDeepGrasp() {
		var goal = new ROSLIB.Goal({
			actionClient: graspClient,
			goalMessage: {
				shallow: false
			}
		});
		goal.send();
	}

	/****************************************************************************
	 *                         Primitive Actions                                *
	 ****************************************************************************/
	function executeResetArm() {
		var goal = new ROSLIB.Goal({
			actionClient: armClient,
			goalMessage: {
				action: 1
			}
		});
		goal.send();
	}

	function executeOpenGripper() {
		var goal = new ROSLIB.Goal({
			actionClient: gripperClient,
			goalMessage: {
				close: false
			}
		});
		goal.send();
	}
	function executeCloseGripper() {
		var goal = new ROSLIB.Goal({
			actionClient: gripperClient,
			goalMessage: {
				close: true
			}
		});
		goal.send();
	}

	function executeMoveForward() {
		var goal = new ROSLIB.Goal({
			actionClient: primitiveClient,
			goalMessage: {
				primitive_type: 0,
				axis: 1,
				distance: 0.1
			}
		});
		goal.send();
	}
	function executeMoveBack() {
		var goal = new ROSLIB.Goal({
			actionClient: primitiveClient,
			goalMessage: {
				primitive_type: 0,
				axis: 1,
				distance: -0.1
			}
		});
		goal.send();
	}

	function executeMoveLeft() {
		var goal = new ROSLIB.Goal({
			actionClient: primitiveClient,
			goalMessage: {
				primitive_type: 0,
				axis: 0,
				distance: -0.1
			}
		});
		goal.send();
	}
	function executeMoveRight() {
		var goal = new ROSLIB.Goal({
			actionClient: primitiveClient,
			goalMessage: {
				primitive_type: 0,
				axis: 0,
				distance: 0.1
			}
		});
		goal.send();
	}

	function executeMoveUp() {
		var goal = new ROSLIB.Goal({
			actionClient: primitiveClient,
			goalMessage: {
				primitive_type: 0,
				axis: 2,
				distance: 0.1
			}
		});
		goal.send();
	}
	function executeMoveDown() {
		var goal = new ROSLIB.Goal({
			actionClient: primitiveClient,
			goalMessage: {
				primitive_type: 0,
				axis: 2,
				distance: -0.1
			}
		});
		goal.send();
	}

	function executeRotateCW() {
		var goal = new ROSLIB.Goal({
			actionClient: primitiveClient,
			goalMessage: {
				primitive_type: 1,
				axis: 0,
				distance: -1.5708
			}
		});
		goal.send();
	}
	function executeRotateCCW() {
		var goal = new ROSLIB.Goal({
			actionClient: primitiveClient,
			goalMessage: {
				primitive_type: 1,
				axis: 0,
				distance: 1.5708
			}
		});
		goal.send();
	}
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


</script>
<script>
  /**
   * Setup all visualization elements when the page is loaded. 
   */
  function init() {
	var size=600;
	var mjpegcanvas=new MJPEGCANVAS.MultiStreamViewer({
		divID: 'markers',
		host: '<?php echo $environment['Mjpeg']['host']; ?>',
		port: <?php echo $environment['Mjpeg']['port']; ?>,
		width: size,
		height: size * 0.85,
		quality: <?php echo $environment['Stream']?(($environment['Stream'][0]['quality']) ? $environment['Stream'][0]['quality'] : '90'):''; ?>,
		topics: <?php echo $streamTopics; ?>,
		labels: <?php echo $streamNames; ?>,
		tfObject:_TF,
		tf:'table_base_link',
		refreshRate:'5'
	},EventEmitter);

    // Setup a client to listen to TFs.
    var tfClient = new ROSLIB.TFClient({
      ros : _ROS,
      angularThres : 0.01,
      transThres : 0.01,
      rate : 10.0,
      fixedFrame : '/jaco_base_link'
    });


    // Create the main viewer
    var viewer = new ROS3D.Viewer({
      divID : 'markers',
      width : 800,
      height : 600,
      antialias : true,
      alpha: 0.1,
      near: 0.1, //from P. Grice's code  https://github.com/gt-ros-pkg/hrl-assistive/blob/indigo-devel/assistive_teleop/vci-www/js/video/viewer.js
      far: 50,
      fov: 50,//50, //from ASUS documentation -https://www.asus.com/us/3D-Sensor/Xtion_PRO_LIVE/specifications/
      cameraPose:{x:0.05,y:0.34,z:0},
      //cameraPosition:{x:0.25,y:0,z:-0.5}, //kinect 2 
      //cameraRotation:{x:-1.5708,y:0,z:-1.5708}, //kinect 2
      interactive:false,
      frame: '/camera_rgb_optical_frame',
      tfClient: _TF
     });


    mjpegcanvas.on('change',function(topic){
      if(topic =='/camera/rgb/image_rect_color'){
        viewer.changeCamera(0);
      } else{
        viewer.changeCamera(1);
      }
    });
    
    // Setup the marker client.
    var imClient = new ROS3D.InteractiveMarkerClient({
      ros : _ROS,
      tfClient : tfClient,
      topic : '/nimbus_interactive_manipulation',
      camera : viewer.camera,
      rootObject : viewer.selectableObjects
    });
  }
  init();
</script>
</html>
