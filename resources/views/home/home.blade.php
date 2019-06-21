<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Starter Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <style>
        body {
            padding-top: 50px;
        }

        .starter-template {
            padding: 40px 15px;
            text-align: center;
        }

        .input-group-addon {
            color: #ffffff;
            background-color: #337ab7;
            border-color: #337ab7;
        }

        form {
            margin-top: 20px;
        }

        .form-group {
            width: 100%;
        }

        .input-group {
            width: 90%;
        }

        .result {
            margin-top: 20px;
            min-height: 300px;
        }

        .result .defaul-message {
            padding-top: 80px;
        }

        .result .video-info h3 {
            margin-top: 0;
        }

        .result .video-info a {
            margin-right: 10px;
            margin-bottom: 5px;
        }

        /*  css loading  */
        div.overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
            background: rgba(0, 0, 0, 0.3);
            /*opacity: 0.5;*/
        }

        div.overlay > div {

            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 1.5;
            text-align: center;
            /*vertical-align: middle;*/
            color: rgba(0, 0, 255, 0.57);
            font-weight: bold;
            font-size: 30px;
        }
    </style>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">Youtube Downloader</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav pull-right">
                <li class="active"><a href="https://youtubevi.com">Official Site</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>
<div class="overlay">
    <div>Loading...</div>
</div>
<div class="container">

    <div class="starter-template">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Youtube Downloader</h3>
            </div>
            <div class="panel-body">
                <form class="form-inline youtube-form" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="sr-only" for="youtube-video-link">Youtube Link</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="youtube-video-link" name="url" required
                                   maxlength="300" placeholder="https://www.youtube.com/watch?v=1d2HfH8EBsk">
                            <div class="input-group-addon btn btn-primary btn-submit">GET</div>
                        </div>
                    </div>
                </form>

                <div class="result">
                    <div class="col-xs-12 col-sm-12 defaul-message">
                        <h1>Results will show here</h1>
                    </div>
                    <div class="col-xs-12 col-sm-6 video-thumb">

                    </div>
                    <div class="col-xs-12 col-sm-6 video-info">

                    </div>
                </div>
            </div>
        </div>
    </div>

</div><!-- /.container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script>
    /**
     * validate link
     *
     * @param url
     * @returns string|false
     */
    function validateYoutubeLink(url) {
        // check url is undefined
        if (typeof url === 'undefined') {
            return false;
        }

        var parent = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
        return (url.match(parent)) ? RegExp.$1 : false;
    }

    /**
     * parse json data to HTML
     *
     */
    function parseData(data) {
        if (data.status !== 200) {
            alert('Has error occurred, please try again');
        }

        data = data.data;

        var videoThumb = `<img class="img-responsive center-block" src="${data.thumbnail}" title="${data.title}">`;
        var videoInfo = `
            <h3>${data.title}</h3>
        `;
        // foreach link
        data.downloadlink.forEach(function (element, index) {
            videoInfo += `<a href="${element.url}" class="btn btn-primary" target="_blank"
                            title="${data.title}" download="${data.title}">${element.video}</a>`
        });

        $('.video-thumb').html(videoThumb);
        $('.video-info').html(videoInfo);
        // hide message
        $('.defaul-message').hide();
    }

    /**
     * send request to get video info
     *
     * @param data
     */
    function sendRequest(data) {
        var overlayElement = $('div.overlay');
        // show loading
        overlayElement.show();
        $.ajax({
            url: '{{ route('getinfo') }}',
            method: 'POST',
            data: data,
            success: function (data) {
                parseData(data);
                overlayElement.hide();
            },
            error: function (error) {
                alert('Has error occurred, please try again');
                overlayElement.hide();
                // return false;
            }
        })
    }

    // set event on submit
    $('.youtube-form').submit(function (e) {
        var videoLink = $('#youtube-video-link').val();

        if (!validateYoutubeLink(videoLink)) {
            alert('Video link invalid');
            return false;
        }

        // send request
        sendRequest($(this).serialize());

        e.preventDefault()
    });

    $('.btn-submit').click(function () {
        $('.youtube-form').submit();
    });
</script>
</body>
</html>
