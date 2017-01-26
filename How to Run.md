##On Computer With Arm

###Bring up the arm
    roslaunch nimbus_bringup nimbus_bringup2.launch
	rosrun nimbus_moveit_config primitive_actions

###Point and Click
    roslaunch agile_test_nodes find_grasps.launch remove_table:=true
    rosrun agile_test_nodes point_cloud_clicker _cloud_topic:=/camera_side/depth_registered/points
	rosrun agile_test_nodes grasp_selector
    
###Constrained Positioning
    roslaunch remote_manipulation_markers constrained_positioning.launch grasp_topic:="nimbus_moveit/common_actions/pickup_unrecognized" run_separate_vis:=true

###Free Positioning
    roslaunch remote_manipulation_markers free_positioning.launch base_link:=table_base_link eef_link:=nimbus_ee_link grasp_topic:="nimbus_moveit/common_actions/pickup_unrecognized" 

###Web Interfaces
    roslaunch nimbus_web_interfaces web_services1.launch

##On Computer With Second Asus Camera
    roslaunch openni_launch openni.launch camera:=camera_side depth_registration:=true publish_tfs:=true
