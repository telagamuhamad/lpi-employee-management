<!-- Content -->
<div class="mt-4">
    <h2>Employee Management</h2>
    <div class="my-3">
        <a href="<?= site_url('/employees/create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Add Employee</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($employees)): ?>
                    <tr>
                        <td colspan="5">No employees found.</td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?= $employee['id'] ?></td>
                        <td><?= $employee['name'] ?></td>
                        <td><?= $employee['email'] ?? '-' ?></td>
                        <td><?= $employee['position'] ?></td>
                        <td>
                            <a href="<?= site_url('/employees/' . $employee['id']) ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>
                            <form action="<?= site_url('/employees/delete/' . $employee['id']) ?>" method="post">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this employee?');">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </form>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- End Content -->