<?php get_header(); ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Cool Kids App</a>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="<?=home_url(); ?>/">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?=home_url(); ?>/signin">Sign In</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid content-section container">
	<h2>Sign Up</h2>
    <form action="" method="post">
    	<p id="register-err"></p>
        <input type="email" name="email" placeholder="Enter your email" required id="register-email" autocomplete="off">
        <input type="submit" name="register_submit" value="Confirm" id="register-but">
    </form>
</div>

<?php get_footer(); ?>