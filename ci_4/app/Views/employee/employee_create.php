<!-- Content -->
<div class="mt-4">
    <h2>Add Employee</h2>
    <form action="<?= site_url('employees/store') ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="position">Position</label>
        <input type="text" class="form-control" id="position" name="position" required>
    </div>
    <div class="form-group">
        <label for="photo">Photo (Max 300KB)</label>
        <input type="file" class="form-control-file" id="photo" name="photo" accept="image/*" required>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

</div>
<!-- End Content -->
