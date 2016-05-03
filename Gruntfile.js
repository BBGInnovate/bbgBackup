module.exports = function (grunt){

	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

		/* watch task */
		sass: {
			dev: {
				options: {
					style: 'expanded',
					sourcemap: 'none'
				},
				files: {
					'compiled/style-human.css': '_scss/all.scss'
				}
			},
			dist: {
				options: {
					style: 'compressed',
					sourcemap: 'none'
				},
				files: {
					'compiled/style.css': '_scss/all.scss'
				}
			}

		},

		/* autoprefixer */
		autoprefixer: {
			options: {
				browsers: ['last 2 versions']
			},
			//prefix all files
			multiple_files: {
				expand: true,
				flatten: true,
				src: 'compiled/*.css',
				dest: ''
			}
		},

		/* watch task */
		watch: {
			css: {
				files: '**/*.scss',
				tasks: ['sass', 'autoprefixer'],
				options: {
					livereload: 35729
				}
			},
			php: {
				files: ['**/*.php'],
				options: {
					livereload: 35729
				}
			}
		}

	});

	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.registerTask('default', ['watch']);

}