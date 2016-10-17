<!DOCTYPE html>
<html>
<head>
    <title>Test Job - HTML Grid</title>
    <meta charset="UTF-8">
    <link href="./bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./dist/app.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>HTML Grid <span class="text-muted">using php7, jquery, bootstrap and ajax</span></h1>
    </div>
    <p class="lead">
        Please choose code example to generate page.
        <span class="text-muted">
            If you want to see project on other code you should
            <a href="https://github.com/Horat1us/TestJob-HtmlGrid">Fork Me on GitHub</a>
        </span>
    </p>
    <div class="row">
        <p class="col-xs-6">Do not forget to install <b>Bower</b> components</p>
        <p class="col-xs-6 text-right">
            Use <a href="https://github.com/Horat1us/TestJob-HtmlGrid">project</a> if need it, too.
            <a class="text-muted" href="https://github.com/Horat1us/TestJob-HtmlGrid/blob/master/LICENSE">Without
                warranties, of course</a>
        </p>
    </div>
    <div class="row">
        <div class="col-md-4">
            <button class="btn btn-primary full-width margin-bottom" data-toggle="generate">
                <span class="glyphicon glyphicon-ok"></span> Generate!
            </button>
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <?php include('./samples/list.php'); ?>
            </div>
        </div>
        <div class="col-md-8">
            <div class="alert alert-danger" style="display:none" data-target="errorBlock">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <b data-target="errorName"></b>
                <span data-target="errorText"></span>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Generation result</div>
                <div class="panel-body" data-target="generationResult"></div>
                <div class="panel-footer">Generated in <span data-target="generationTime">0.0</span>s</div>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container">
        <p class="text-muted">Test Job - HTML Grid</p>
        <p class="text-muted">Created by <a href="mailto:reclamme@gmail.com">Alexander Letnikow</a>, 2016</p>
    </div>
</footer>

<script src="./bower_components/jquery/dist/jquery.min.js"></script>
<script src="./bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="./dist/app.js"></script>
</body>
</html>