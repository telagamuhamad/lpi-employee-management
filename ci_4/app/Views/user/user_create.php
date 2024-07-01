<!-- Content -->
<div class="mt-4">
    <h2>Add User</h2>

    <form action="<?= site_url('/users/store') ?>" method="post">
    <div class="form-group">
            <label for="username">Username <sup style="color: red;">*</sup></label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email <sup style="color: red;">*</sup></label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="form-group">
            <label for="level">Level: <sup style="color: red;">*</sup></label>
            <select name="level" id="level" class="form-control" required>
                <option value="admin">Admin</option>
                <option value="superadmin">Superadmin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<!-- End Content -->
