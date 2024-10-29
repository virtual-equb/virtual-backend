

<?php $__env->startSection('styles'); ?>
<style type="text/css">
    .details-control {
        background: url("<?php echo e(url('images/plus20.webp')); ?>") no-repeat center center;
        cursor: pointer;
    }
    tr.shown .details-control {
        background: url("<?php echo e(url('images/minus20.webp')); ?>") no-repeat center center;
    }
    .form-group.required .control-label:after {
        content: "*";
        color: red;
    }
    .table-responsive {
        overflow-x: auto;
    }
    @media (max-width: 768px) {
        .responsive-input {
            width: 100%;
            margin-bottom: 20px;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-pills" id="custom-tabs-two-tab" role="tablist">
                                    <li class="nav-item nav-blue memberTab">
                                        <a class="nav-link active" id="custom-tabs-two-member-tab" data-toggle="pill" href="#custom-tabs-two-member" role="tab" aria-controls="custom-tabs-two-member" aria-selected="true">
                                            <span class="fa fa-list"></span> Main Equb
                                        </a>
                                    </li>
                                </ul>
                                <div class="float-right">
                                    <?php if(!in_array(Auth::user()->role, ['assistant', 'finance'])): ?>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addMainEqubModal" style="margin-right: 30px;">
                                            <span class="fa fa-plus-circle"></span> Add Main Equb
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <input class="form-control responsive-input" type="text" id="equbSearchText" placeholder="Search Main Equb">
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-default" id="clearSearch">Clear</button>
                                    </div>
                                </div>
                                <div id="equb_table_data" class="table-responsive">
                                    <table class="table table-bordered" id="equbTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Image</th>
                                                <th>Main Equbs Name</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $mainEqubs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $equb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($key + 1); ?></td>
                                                    <td>
    <img src="<?php echo e(asset('storage/' . $equb->image)); ?>" alt="<?php echo e($equb->name); ?>" style="width: 50px; height: auto;">
</td>

                                                    <td><?php echo e($equb->name); ?></td>
                                                    <td>
                                                        <span class="badge <?php echo e($equb->active == 1 ? 'badge-success' : 'badge-danger'); ?>">
                                                            <?php echo e($equb->active == 1 ? 'Active' : 'Inactive'); ?>

                                                        </span>
                                                    </td>
                                                    <?php if(Auth::user()->role != 'assistant'): ?>
                                                        <td>
                                                            <div class='dropdown'>
                                                                <button class='btn btn-secondary btn-sm btn-flat dropdown-toggle' type='button' data-toggle='dropdown'>Menu<span class='caret'></span></button>
                                                                <ul class='dropdown-menu p-4'>
                                                                    <?php if(Auth::user()->role != 'finance'): ?>
                                                                        <li>
                                                                            <button class="text-secondary btn btn-flat" onclick="openEditModal(<?php echo e($equb->id); ?>)">
                                                                                <span class="fa fa-edit"></span> Edit
                                                                            </button>
                                                                        </li>
                                                                        <li>
                                                                            <button class="text-secondary btn btn-flat delete-equb" data-id="<?php echo e($equb->id); ?>">
                                                                                <i class="fas fa-trash-alt"></i> Delete
                                                                            </button>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Include the Add Main Equb Modal -->
<?php echo $__env->make('admin.mainEqub.addMainEqub', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('admin.mainEqub.editMainEqub', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    $(document).on('click', '.delete-equb', function() {
        const equbId = $(this).data('id');
        if (confirm('Are you sure you want to remove this main equb?')) {
            $.ajax({
                url: '/main-equbs/' + equbId,
                type: 'DELETE',
                success: function(result) {
                    location.reload(); // Refresh the equb table
                },
                error: function(xhr) {
                    alert('Error deleting equb: ' + xhr.responseText);
                }
            });
        }
    });

    $('#clearSearch').click(function() {
        $('#equbSearchText').val('');
        // Optionally refresh the table or apply a filter reset
    });

    function openEditModal(equbId) {
        $.ajax({
            type: 'GET',
            url: '/main-equbs/' + equbId, // Adjust URL to fetch the specific equb data
            success: function(data) {
                $('#edit_equb_id').val(data.id);
                $('#edit_name').val(data.name);
                $('#edit_remark').val(data.remark);
                $('#edit_status').val(data.active); // Set the status dropdown
                $('#editMainEqubModal').modal('show'); // Open the modal
            },
            error: function(xhr) {
                console.error('Error fetching data:', xhr);
            }
        });
    }

    $('#saveChanges').click(function() {
        const id = $('#edit_equb_id').val();
        const name = $('#edit_name').val();
        const remark = $('#edit_remark').val();
        const status = $('#edit_status').val();

        $.ajax({
            type: 'PUT',
            url: '/main-equbs/' + id,
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                name: name,
                remark: remark,
                status: status // Include status in the data sent
            },
            success: function(result) {
                location.reload(); // Refresh the equb table after saving
            },
            error: function(xhr) {
                console.log(xhr.responseText); // Corrected here
                alert('Error updating equb: ' + xhr.responseText);
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\virtual Equb\virtual-backend\resources\views/admin/mainEqub/mainEqubList.blade.php ENDPATH**/ ?>