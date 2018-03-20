<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?= $title ?></title>
        <link href="public/css/style.css" rel="stylesheet" />
        <script src="public/css/bootstrap-4.0.0/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="public/css/bootstrap-4.0.0/css/bootstrap.min.css">        
        <script src="public/js/jquery-3.2.1.slim.min.js"></script>
		<script src="public/js/popper.min.js"></script>
    </head>
    
    <body>
    	<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow" style="box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, .05);">
    		<h5 class="my-0 mr-md-auto font-weight-normal">MAUD BLOG</h5>
    		<nav class="my-2 my-md-0 mr-md-3">
    			<a class="p-2 text-dark" href="#">Features</a> 
    			<a class="p-2 text-dark" href="#">Enterprise</a>
    			<a class="p-2 text-dark" href="#">Support</a>
    			<a class="p-2 text-dark" href="#">Pricing</a>
    		</nav>
    		<a class="btn btn-outline-primary" href="#">Sign up</a>
    	</div>
    
    	<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    		<h1 class="display-4">LES PETITES LECTURES DE MAUD</h1>
    		<p class="lead">un blog, des livres</p>
    	</div>
    	
    	<div class="container">
    		<?=$content?>    	
    	</div>


    	<footer class="pt-4 my-md-5 pt-md-5 border-top">
    		<div class="row">
    			<div class="col-12 col-md">
    				<img class="mb-2" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg"
    					alt="" width="24" height="24">
    				<small class="d-block mb-3 text-muted">&copy; 2017-2018</small>
    			</div>
    			<div class="col-6 col-md">
    				<h5>Features</h5>
    				<ul class="list-unstyled text-small">
    					<li><a class="text-muted" href="#">Cool stuff</a></li>
    					<li><a class="text-muted" href="#">Random feature</a></li>
    					<li><a class="text-muted" href="#">Team feature</a></li>
    					<li><a class="text-muted" href="#">Stuff for developers</a></li>
    					<li><a class="text-muted" href="#">Another one</a></li>
    					<li><a class="text-muted" href="#">Last time</a></li>
    				</ul>
    			</div>
    			<div class="col-6 col-md">
    				<h5>Resources</h5>
    				<ul class="list-unstyled text-small">
    					<li><a class="text-muted" href="#">Resource</a></li>
    					<li><a class="text-muted" href="#">Resource name</a></li>
    					<li><a class="text-muted" href="#">Another resource</a></li>
    					<li><a class="text-muted" href="#">Final resource</a></li>
    				</ul>
    			</div>
    			<div class="col-6 col-md">
    				<h5>About</h5>
    				<ul class="list-unstyled text-small">
    					<li><a class="text-muted" href="#">Team</a></li>
    					<li><a class="text-muted" href="#">Locations</a></li>
    					<li><a class="text-muted" href="#">Privacy</a></li>
    					<li><a class="text-muted" href="#">Terms</a></li>
    				</ul>
    			</div>
    		</div>
    	</footer>
	</body>
</html>