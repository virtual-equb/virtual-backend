<!-- Edit Main Equb Modal -->
<div class="modal fade" id="editMainEqubModal" tabindex="-1" role="dialog" aria-labelledby="editMainEqubModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMainEqubModalLabel">Edit Main Equb</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editMainEqubForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="edit_equb_id" name="equb_id">
                    
                    <div class="form-group required">
                        <label for="edit_name">Main Equb Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_status">Status</label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_remark">Remark</label>
                        <textarea class="form-control" id="edit_remark" name="remark"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>
            </div>
        </div>
    </div>
</div><?php /**PATH D:\virtual Equb\virtual-backend\resources\views/admin/mainEqub/editMainEqub.blade.php ENDPATH**/ ?>