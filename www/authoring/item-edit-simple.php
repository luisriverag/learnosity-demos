<?php

    //common environment attributes including search paths. not specific to Learnosity
	include_once '../env_config.php';

	//site scaffolding
	include_once 'includes/header.php';

    //common Learnosity config elements including API version control vars
	include_once '../lrn_config.php';

    //alias(es) to eliminate the need for fully qualified classname(s) from sdk
	use LearnositySdk\Request\Init;
	use LearnositySdk\Utils\Uuid;


    //security object. timestamp added by SDK
	$security = [
		'consumer_key' => $consumer_key,
		'domain'       => $domain
	];


    //simple api request object, with additional common features added and commented
	$request = [
		'mode'      => 'item_edit',
		'reference' => Uuid::generate(),
		'config'    => [
		    /*
		     * dependent API settings: QuestionEditor init options
		     * ui layout mode "simple" for simple authoring
		     */
			"dependencies" => [
                "question_editor_api" => [
                    "init_options" => [
                        "ui" => [
                            "layout" => [
                                "mode" => "simple"
                            ]
                        ]
                    ]

                ]
            ],
			'item_edit' => [
				'item' => [
					//show item reference and allow editing
					'reference' => [
						'show' => true,
						'edit' => true
					],
					/*
					 * enable dynamic content and shared passages in items
					 * allow duplication of items
					 */
					'dynamic_content' => true,
					'duplicate' => true,
					'shared_passage' => true,
                	'enable_audio_recording'=>true
				]
			]
		],
		//user for whom this API is initialized. recorded when editing item content.
		'user' => [
			'id'        => 'demos-site',
			'firstname' => 'Demos',
			'lastname'  => 'User',
			'email'     => 'demos@learnosity.com'
		]
	];

	$Init = new Init('author', $security, $consumer_secret, $request);
	$signedRequest = $Init->generate();

?>

    <!--site scaffolding-->
    <div class="jumbotron section">
        <div class="toolbar">
            <ul class="list-inline">
                <li data-toggle="tooltip" data-original-title="Preview API Initialisation Object"><a href="#"  data-toggle="modal" data-target="#initialisation-preview"><span class="glyphicon glyphicon-search"></span></a></li>
                <li data-toggle="tooltip" data-original-title="Visit the documentation"><a href="https://support.learnosity.com/hc/en-us/categories/360000105358-Learnosity-Author" title="Documentation"><span class="glyphicon glyphicon-book"></span></a></li>
            </ul>
        </div>
        <div class="overview">
            <h2>Simplify Authoring for Teachers and Instructors</h2>
            <p>This demo provides a stripped down question editor - with simpler, opinionated templates to make question creation as easy as possible.</p>
        </div>
    </div>


    <div class="section pad-sml">
        <!--HTML placeholder that is replaced by Author API-->
        <div id="learnosity-author"></div>
    </div>


    <!-- version of api maintained in lrn_config.php file -->
    <script src="<?php echo $url_authorapi; ?>"></script>
    <script>
        var initializationObject = <?php echo $signedRequest; ?>;

        //optional callbacks for ready and/or error event(s)
        var callbacks = {
            readyListener: function () {
                console.log("Author API has successfully initialized.");
            },
            errorListener: function (err) {
                console.log(err);
            }
        };

        var authorApp = LearnosityAuthor.init(initializationObject, callbacks);
    </script>


<?php
	include_once 'views/modals/initialisation-preview.php';
	include_once 'includes/footer.php';
