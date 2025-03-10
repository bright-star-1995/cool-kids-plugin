<div class="wrap">
    <h1>CoolKids Members</h1>
    <input type="hidden" name="nonce" id="nonce" value="<?= wp_create_nonce( 'wp_rest' ); ?>">

    <table class="widefat striped fixed" cellspacing="0">
        <thead>
            <tr>
                <th class="manage-column column-next-run-header" scope="col">First Name</th>
                <th class="manage-column column-next-run-header" scope="col">Last Name</th>
                <th class="manage-column column-next-run-header" scope="col">Email</th>
                <th class="manage-column column-next-run-header" scope="col">Country</th>
                <th class="manage-column column-next-run-header" scope="col">Role</th>
                <th class="manage-column column-next-run-header" scope="col">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php if ( count( $members ) > 0 ) : ?>
                <?php foreach ( $members as $member ) : ?>
                        <tr>
                            <td><?= $member->firstname; ?></td>
                            <td><?= $member->lastname; ?></td>
                            <td><?= $member->email; ?></td>
                            <td><?= $member->country; ?></td>
                            <td>
                                <select id="role-<?= $member->id ?>">
                                    <?php foreach( $roles as $role ) { ?>
                                        <option value="<?= $role; ?>" <?php if ($role == $member->role) echo 'selected'; ?>><?= $role; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <button class="button button-primary update-role-button" data-email="<?= $member->email; ?>" data-id="<?= $member->id; ?>" data-firstname="<?= $member->firstname; ?>" data-lastname="<?= $member->lastname; ?>">Update</button>
                            </td>
                        </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6">No members found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>