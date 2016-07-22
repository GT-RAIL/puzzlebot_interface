##Puzzlebot

This is the Repository of the Puzzlebot Interface. The files in this repository are interfaces for the [Robot Management System](https://github.com/gt-rail/rms).

To install RMS, follow instructions on its' [Github](https://github.com/gt-rail/rms).

###Adding this interface to RMS

In the web view of RMS, go to `Admin>ROS Settings>Interfaces` and create 3 new entries with the names `PuzzleBotClick`,`PuzzleBot` and `PuzzleBot3D`.

###Developing 

In the utils folder run `grunt build_and_watch:path/to/rms/app`. There is more information on installing Grunt and it's dependencies in [utils/README.md](utils/README.md).

**NOTE**: The path specified to RMS is relative from your home directory.

**Known Issues**: When renaming files both the old and the new files will be copied into the RMS folder

### License
This is released with a BSD license. For full terms and conditions, see the [LICENSE](LICENSE) file.
