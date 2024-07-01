<!-- Content -->
<div class="mt-4">
    <h2>Edit Employee</h2>

    <form action="<?= site_url('/employees/' . $employee['id']) ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="PUT">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= esc($employee['name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= esc($employee['email']) ?>" required>
        </div>
        <div class="form-group">
            <label for="position">Position</label>
            <input type="text" class="form-control" id="position" name="position" value="<?= esc($employee['position']) ?>" required>
        </div>
        <?php if (!empty($employee['photo'])): ?>
        <div class="form-group">
            <label for="currentPhoto">Current Photo</label><br>
            <img src="<?= base_url('public/uploads/' . $employee['photo']) ?>" alt="Current Photo" style="max-width: 200px;">
        </div>
        <?php endif; ?>
        <div class="form-group">
            <label for="photo">Update Photo (Max 300KB)</label>
            <input type="file" class="form-control-file" id="photo" name="photo" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<!-- End Content -->
