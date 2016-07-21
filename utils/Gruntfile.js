(function () {
   'use strict';
}());
module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('../package.json'),
    copy: {
      build: {
        cwd : '..',
        src  : ['src/*/**'],
        dest : grunt.config.get('dest'),
        expand: true
      }
    },
    jshint: {
      options: {
        jshintrc: '.jshintrc'
      },
      files: [
        'Gruntfile.js',
        '../src/js/*.js'
      ]
    },
    watch: {
      dev: {
        options: {
          interrupt: true
        },
        files: [
          '../src/*.*',
          '../src/**/*.*'
        ],
        tasks: []
      },
      build_and_watch: {
        options: {
          interrupt: true
        },
        files: [
          'Gruntfile.js',
          '../src/*.*',
          '../src/**/*.*'
        ],
        tasks: ['dev']
      }
    }
  }

  );

  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-clean');


  grunt.registerTask('build', ['jshint','copy:build']);
  grunt.registerTask('build_and_watch', ['watch']);

  grunt.registerTask('dev', 'copy files to local RMS', function(dest){
    if (arguments.length === 0){
      grunt.log.writeln('Please add the destination as the root of your RMS. grunt dev:destination');
    }
    else{
      grunt.config.set('dest',dest);
      grunt.task.run(['build']);
    }
  });
};

