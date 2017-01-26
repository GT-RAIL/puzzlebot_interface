##Puzzlebot

This is the Repository of the Puzzlebot Interface. The files in this repository are interfaces for the [Robot Management System](https://github.com/gt-rail/rms).

This repository includes the interfaces used in A Comparison of Remote Robot Teleoperation Interfaces for General Object Manipulation (publication forthcoming in HRI2017).  The interfaces align with the evaluation study conditions as follows:

 * Free Positioning : PuzzleBot3DInterface
 * Constrained Positioing : PuzzleBotNavidget2DInterface
 * Point-and-Click : PuzzleBotClickInterface

To install RMS, follow instructions on its' [Github](https://github.com/gt-rail/rms).

###How to Run 

To run you require, [Openni](https://structure.io/openni), [remote_manipulation_markers](http://wiki.ros.org/remote_manipulation_markers), and [nimbus_bot](https://github.com/GT-RAIL/nimbus_bot). There are instructions on which nodes to run under which conditions in [How to Run file](How to Run.md)

###Developing 

In the utils folder run `grunt build_and_watch:path/to/rms/app`. There is more information on installing Grunt and it's dependencies in [utils/README.md](utils/README.md).

**NOTE**: The path specified to RMS is relative from your home directory.

**Known Issues**: When renaming files both the old and the new files will be copied into the RMS folder

### License
This is released with a BSD license. For full terms and conditions, see the [LICENSE](LICENSE) file.
