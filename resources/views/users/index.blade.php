@extends('layouts.app')

@section('content')
<div class="col-xxl-12 col-lg-6">
    <div class="card h-100">
        <div class="card-body p-24">
            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                <h6 class="mb-2 fw-bold text-lg mb-0">List Users</h6>
                <button class="btn btn-sm btn-primary d-flex align-items-center gap-2 px-3 py-2" data-bs-toggle="modal"
                    data-bs-target="#createUserModal">
                    <iconify-icon icon="heroicons:plus" class="icon"></iconify-icon>
                    Add New
                </button>
            </div>
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Users</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-{{ $user->role == 'administrator' ? 'primary' : 'success' }}">
                                    {{ $user->role == 'administrator' ? 'Administrator' : 'Calon Mahasiswa' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">Active</span>
                            </td>
                            <td>{{ $user->created_at->format('d-m-Y H:i') }}</td>
                            <td>
                                <a href="#" class="text-secondary-light hover-text-primary"
                                    onclick="openEditModal({{ $user->id }})">
                                    <iconify-icon icon="heroicons:pencil-square" class="icon"></iconify-icon>
                                </a>
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <a href="javascript:void(0)" class="text-secondary-light hover-text-danger ms-12"
                                        onclick="if(confirm('Apakah Anda yakin ingin menghapus user ini?')) { this.closest('form').submit(); }">
                                        <iconify-icon icon="heroicons:trash" class="icon"></iconify-icon>
                                    </a>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('users.components.user-create-modal')
@include('users.components.user-edit-modal')

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

    window.openEditModal = async function(userId) {
        try {
            const response = await fetch(`/admin/users/${userId}/data`);
            const result = await response.json();

            if (result.success) {
                const user = result.data;

                const form = document.getElementById('editUserForm');
                form.action = `/admin/users/${user.id}`;

                document.getElementById('edit_user_id').value = user.id;
                document.getElementById('edit_name').value = user.name;
                document.getElementById('edit_email').value = user.email;
                document.getElementById('edit_role').value = user.role;

                if (user.role === 'mahasiswa' && user.calon_mahasiswa) {
                    const cm = user.calon_mahasiswa;
                    document.getElementById('edit_calon_mahasiswa_id').value = cm.id;
                    document.getElementById('edit_program_studi').value = cm.kode_program_studi;
                    document.getElementById('edit_jenis_kelamin').value = cm.jenis_kelamin;
                    
                    if (cm.tanggal_lahir) {
                        const tanggal = new Date(cm.tanggal_lahir);
                        const yyyy = tanggal.getFullYear();
                        const mm = String(tanggal.getMonth() + 1).padStart(2, '0');
                        const dd = String(tanggal.getDate()).padStart(2, '0');
                        document.getElementById('edit_tanggal_lahir').value = `${yyyy}-${mm}-${dd}`;
                    } else {
                        document.getElementById('edit_tanggal_lahir').value = '';
                    }

                    document.getElementById('edit_alamat').value = cm.alamat;
                    document.getElementById('edit_no_telepon').value = cm.no_telepon;
                    document.getElementById('edit_asal_sekolah').value = cm.asal_sekolah;
                } else {
                    ['edit_calon_mahasiswa_id','edit_program_studi','edit_jenis_kelamin','edit_tanggal_lahir','edit_alamat','edit_no_telepon','edit_asal_sekolah']
                    .forEach(id => document.getElementById(id).value = '');
                }

                toggleEditCalonMahasiswaFields();

                new bootstrap.Modal(document.getElementById('editUserModal')).show();
            } else {
                alert('User tidak ditemukan!');
            }
        } catch(e) {
            console.error(e);
            alert('Terjadi kesalahan saat memuat data user');
        }
    }

    function toggleEditCalonMahasiswaFields() {
        const role = document.getElementById('edit_role').value;
        const fields = document.getElementById('editCalonMahasiswaFields');
        const programStudi = document.getElementById('edit_program_studi');
        if(role === 'mahasiswa') {
            fields.style.display = 'block';
            if(programStudi) programStudi.setAttribute('required','required');
        } else {
            fields.style.display = 'none';
            if(programStudi) programStudi.removeAttribute('required');
        }
    }

    const editRoleSelect = document.getElementById('edit_role');
    if(editRoleSelect) editRoleSelect.addEventListener('change', toggleEditCalonMahasiswaFields);

    const editModalElement = document.getElementById('editUserModal');
    if(editModalElement) editModalElement.addEventListener('hidden.bs.modal', function() {
        document.getElementById('editUserForm').reset();
        toggleEditCalonMahasiswaFields();
    });

});

</script>
@endsection