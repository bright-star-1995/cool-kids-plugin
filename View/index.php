<?php get_header(); ?>

<?php if ( isset( $_SESSION['member'] ) ) { ?>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Cool Kids App</a>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="javascript:void(0);" id="logout-link">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>

	<div class="container-fluid content-section container" style="margin-top: 30px">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<td colspan='2'><h2>Your Character</h2></td>
				</tr>
				<tr>
					<td>First Name</td>
					<td><?= $_SESSION['member']->firstname; ?></td>
				</tr>
				<tr>
					<td>Last Name</td>
					<td><?= $_SESSION['member']->lastname; ?></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><?= $_SESSION['member']->email; ?></td>
				</tr>
				<tr>
					<td>Country</td>
					<td><?= $_SESSION['member']->country; ?></td>
				</tr>
				<tr>
					<td>Role</td>
					<td><?= $_SESSION['member']->role; ?></td>
				</tr>
			</tbody>
		</table>
	</div>

	<?php if ( count( $members ) > 0 ) : ?>
		<div class="container">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th colspan='5'>Members</th>
					</tr>
					<tr>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Email</th>
						<th>Country</th>
						<th>Role</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $members as $member ) : ?>
						<tr>
							<td><?= $member->firstname; ?></td>
							<td><?= $member->lastname; ?></td>
							<td><?= $member->email; ?></td>
							<td><?= $member->country; ?></td>
							<td><?= $member->role; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
<?php } else { ?>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Cool Kids App</a>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?=home_url(); ?>/signin">Sign In</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?=home_url(); ?>/signup">Sign Up</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid content-section container">
        <h1>Welcome to Cool Kids App</h1>

        <!-- Your content here -->
    </div>
<?php } ?>

<?php get_footer(); ?>