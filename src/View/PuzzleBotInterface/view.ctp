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
	
	<?php echo $this->Html->script('mjpegcanvas2.js');?>

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
	<div id="mjpeg"></div>
</body>

	<script type="text/javascript">

		//this is the topic for cartesian moving objects around
		var cartesian_move_topic = new ROSLIB.Topic({
                ros: _ROS,
                name: '/tablebot_moveit_wrapper/cartesian_control',
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
		console.log(EventEmitter)
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
	  //  mjpegcanvas.addTopic('/tablebot_interactive_manipulation/update_full','visualization_msgs/InteractiveMarkerInit')

	</script>
	<script>
	     // var cartesian_move_topic = new ROSLIB.Topic({
         //        ros: _ROS,
         //        name: '/tablebot_moveit_wrapper/cartesian_control',
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