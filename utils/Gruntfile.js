(function () {
   'use strict';
}());
module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('../package.json'),
    shell: {
        copyTemplate: {
            command: 'cp -R ../src/* ~/<%= dest %>'
        }
    },
    jshint: {
      options: { 
        jshintrc: '.jshintrc'
      },
      files: [
        'Gruntfile.js',
        '../src/webroot/js/*.js',
        '!../src/webroot/js/*.min.js' 
      ]
    },
    watch: {
      dev: {
        options: {
          spawn: false,
          interrupt: true
        },
        dest:'<%= dest %>',
        files: [
          'Gruntfile.js',
          '../src/*.*',
          '../src/**/*.*',
          '../src/**/**/*.*'
        ],
        tasks: ['jshint','shell:copyTemplate']
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-shell');

  grunt.registerTask('build_and_watch',function(dest){
    if (arguments.length === 0){
      grunt.log.writeln('Please add the destination as the root of your RMS. grunt dev:destination');
      return false;
    }
    else{
      grunt.config.set('dest',dest);
      grunt.task.run(['watch:dev']);
      return true;
    }  
  });

  grunt.registerTask('build', 'copy files to local RMS', function(dest){
    if (arguments.length === 0){
      grunt.log.writeln('Please add the destination as the root of your RMS. grunt dev:destination');
      return false;
    }
    else{
      grunt.config.set('dest',dest);
      grunt.task.run(['jshint','shell:copyTemplate']);
      return true;
    }
  });
};

