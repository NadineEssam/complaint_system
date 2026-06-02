<div class="table-responsive border rounded text-right">
    <table class="table table-hover mb-0 align-middle" dir="rtl">
        <thead class="bg-light">
            <tr>
                <th class="text-right py-3" style="width: 250px;">
                    <div class="d-flex align-items-center">
                        <span class="font-weight-bold text-dark">الوصول الكامل</span>
                        <i class="bx bx-info-circle me-2 text-primary" data-bs-toggle="tooltip" title="يسمح بالوصول الكامل للنظام"></i>
                    </div>
                </th>
                <th class="text-right py-3">
                    <div class="form-check mb-0">
                        <input type="checkbox" class="form-check-input cursor-pointer" id="roles_select_all" />
                        <label class="form-check-label font-weight-bold text-primary cursor-pointer" for="roles_select_all">تحديد كل الصلاحيات</label>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
        @foreach(\Spatie\Permission\Models\Permission::select('group',\Illuminate\Support\Facades\DB::raw("COUNT('x')"))->where('guard_name' , 'web')->groupBy('group')->pluck('group')->toArray() as $group)
            
            @php($array = isset($role) ? $role->permissions->pluck('id')->toArray() : (isset($user) ? $user->permissions()->pluck('id')->toArray() : []))

            @php($permissionsList = \Spatie\Permission\Models\Permission::where('group', $group)
                ->where('guard_name', 'web')
                ->orderBy('id', 'ASC')
                 ->get(['id', 'name', 'ar_name', 'group_ar']))
            
            <tr>
                <td class="text-right bg-light border-left pe-4">
                    <div class="form-check mb-0">
                        <input type="checkbox" class="form-check-input cursor-pointer select-all-group" id="group_{{ md5($group) }}" value="{{ $group }}" />
                        <label class="form-check-label font-weight-bold text-dark cursor-pointer" for="group_{{ md5($group) }}">
                            {{ $permissionsList->first()->group_ar ?? $group }}
                        </label>
                    </div>
                </td>
                
                <td class="text-right p-3">
                    <div class="d-flex flex-wrap" style="gap: 1.5rem;">
                        @foreach($permissionsList as $permission)
                            <div class="form-check mb-0">
                                <input type="checkbox"
                                       class="form-check-input cursor-pointer permission-item"
                                       id="perm_{{ $permission->id }}"
                                       value="{{ $permission->name }}"
                                       name="permissions[]"
                                       @if(in_array($permission->id, $array)) checked @endif
                                       data-group="{{ md5($group) }}" />

                                <label class="form-check-label text-secondary cursor-pointer" for="perm_{{ $permission->id }}">
                                    {{ $permission->ar_name ?? $permission->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<style>
    .cursor-pointer { cursor: pointer; }
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllSystem = document.getElementById('roles_select_all');
        const groupCheckboxes = document.querySelectorAll('.select-all-group');
        const permissionItems = document.querySelectorAll('.permission-item');

        // Check if all permissions in a group are selected to toggle the group checkbox
        function updateGroupCheckboxState(groupId) {
            const groupItems = document.querySelectorAll(`.permission-item[data-group="${groupId}"]`);
            const checkedGroupItems = document.querySelectorAll(`.permission-item[data-group="${groupId}"]:checked`);
            const groupCheckbox = document.getElementById(`group_${groupId}`);
            
            if(groupCheckbox) {
                groupCheckbox.checked = (groupItems.length > 0 && groupItems.length === checkedGroupItems.length);
                groupCheckbox.indeterminate = (checkedGroupItems.length > 0 && checkedGroupItems.length < groupItems.length);
            }
        }

        // Check if all groups are selected to toggle the global checkbox
        function updateGlobalCheckboxState() {
            const totalItems = permissionItems.length;
            const checkedItems = document.querySelectorAll('.permission-item:checked').length;
            
            if(selectAllSystem) {
                selectAllSystem.checked = (totalItems > 0 && totalItems === checkedItems);
                selectAllSystem.indeterminate = (checkedItems > 0 && checkedItems < totalItems);
            }
        }

        // Initialize state on load
        groupCheckboxes.forEach(function(groupCb) {
            const groupId = groupCb.id.replace('group_', '');
            updateGroupCheckboxState(groupId);
        });
        updateGlobalCheckboxState();

        // Handle global select all
        if (selectAllSystem) {
            selectAllSystem.addEventListener('change', function () {
                const isChecked = this.checked;
                permissionItems.forEach(item => { item.checked = isChecked; });
                groupCheckboxes.forEach(item => { item.checked = isChecked; item.indeterminate = false; });
            });
        }

        // Handle group select all
        groupCheckboxes.forEach(function(groupCb) {
            groupCb.addEventListener('change', function () {
                const isChecked = this.checked;
                const groupId = this.id.replace('group_', '');
                const groupItems = document.querySelectorAll(`.permission-item[data-group="${groupId}"]`);
                
                groupItems.forEach(item => { item.checked = isChecked; });
                updateGlobalCheckboxState();
            });
        });

        // Handle individual item select
        permissionItems.forEach(function(item) {
            item.addEventListener('change', function () {
                const groupId = this.getAttribute('data-group');
                updateGroupCheckboxState(groupId);
                updateGlobalCheckboxState();
            });
        });
    });
</script>
