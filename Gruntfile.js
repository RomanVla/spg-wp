var path = require('path');

module.exports = function (grunt) {

    require('load-grunt-tasks')(grunt);

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        jshint: {
            options: {
                curly: true,
                eqeqeq: true,
                immed: true,
                latedef: true,
                newcap: true,
                noarg: true,
                sub: true,
                undef: true,
                eqnull: true,
                browser: true,
                globals: {
                    cnArgs: true,
                    jQuery: true,
                    $: true,
                    console: true,
                    Promise: true,

                    particlesJS: true,
                    bulmaAccordion: true,
                    introJs: true,

                    wp_var: true,
                    iConnectel: true,
                    iConnectelCookieNotice: true,
                    appICT: true,
                    jsPDF: true,
                    ServicePricingCalculator: true,
                }
            },
            all: [
                'src/js/**/*.js'
            ],
        },

        concat: {
            dist: {
                options: {
                    sourceMap: true,
                    separator: ';',
                    process: function(src, filepath) {
                        return '\n\n/*📁 ' + filepath + '*/\n' + src;
                    }
                },
                files: {
                    'js/scripts.min.js': [
                        'src/js/main.js'
                    ]
                }
            }
        },

        uglify: {
            my_target: {
                files: {
                    'js/scripts.min.js': ['js/scripts.min.js']
                }
            }
        },
        sass: {
            options: {
                sourceMap: true,
                outputStyle: 'compressed'
            },
            dist: {
                files: {
                    'css/spg-style.css': 'src/sass/main.scss',
                }
            }
        },

        watch: {
            options: {
                livereload: true,
            },
            files: [
                '*.php',
                'php/**/*.php',

                'src/sass/**/*.scss',
                'src/js/**/*.js',

                //Excluded files
                '!js/scripts.min.js'
            ],
            tasks: ['sass', 'concat']
        }
    });

    // Default task(s).
    grunt.registerTask('generate-icon', ['sass']);
    grunt.registerTask('prod', ['sass', 'concat', 'uglify']);
    grunt.registerTask('default', ['sass', 'concat']);

};
