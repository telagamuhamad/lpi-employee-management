<!-- Content -->
<div class="mt-4">
    <h2>Edit User</h2>

    <form action="<?= site_url('/users/' . $user['id']) ?>" method="post">
        <input type="hidden" name="_method" value="PUT">
        <div class="form-group">
            <label for="username">Username <sup style="color: red;">*</sup></label>
            <input type="text" class="form-control" id="username" name="username" value="<?= $user['username'] ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email <sup style="color: red;">*</sup></label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Leave blank if not changing">
        </div>
        <div class="form-group">
            <label for="level">Level: <sup style="color: red;">*</sup></label>
            <select name="level" id="level" class="form-control" required>
                <option value="admin" <?= ($user['level'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                <option value="superadmin" <?= ($user['level'] === 'superadmin') ? 'selected' : '' ?>>Superadmin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<!-- End Content -->
