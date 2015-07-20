'use strict';
module.exports = function ( grunt ) {

	// load all grunt tasks matching the `grunt-*` pattern
	// Ref. https://npmjs.org/package/load-grunt-tasks
	require( 'load-grunt-tasks' )( grunt );

	grunt.initConfig( {
		// watch for changes and trigger sass, jshint, uglify and livereload
		watch: {
			sass: {
				files: [ 'admin/css/sass/**/*.{scss,sass}' ],
				tasks: [ 'sass', 'autoprefixer' ]
			}
		},
		// sass
		sass: {
			dist: {
				options: {
					style: 'expanded'
				},
				files: {
					'admin/css/rtmedia-transcoding-admin.css': 'admin/css/sass/rtmedia-transcoding-admin.scss'
				}
			},
			minify: {
				options: {
					style: 'compressed'
				},
				files: {
					'admin/css/rtmedia-transcoding-admin.min.css': 'admin/css/sass/rtmedia-transcoding-admin.scss'
				}
			}
		},
		// autoprefixer
		autoprefixer: {
			options: {
				browsers: [ 'last 2 versions', 'ie 9', 'ios 6', 'android 4' ],
				map: true
			},
			files: {
				expand: true,
				flatten: true,
				src: 'admin/css/*.css',
				dest: 'admin/css/'
			}
		},
		// Uglify Ref. https://npmjs.org/package/grunt-contrib-uglify
		uglify: {
			options: {
				banner: '/*! \n * rtMedia Transcoding JavaScript Library \n * @package rtMediaTranscoding \n */',
			},
			backend: {
				src: [
					'admin/js/rtmedia-transcoding-admin.js'
				],
				dest: 'admin/js/rtmedia-transcoding-admin.min.js'
			}
		}
	} );
	// register task
	grunt.registerTask( 'default', [ 'sass', 'autoprefixer', 'uglify', 'watch' ] );
};