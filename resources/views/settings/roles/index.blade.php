@extends('layouts.admin2')

@section('content')

    {{-- message --}}
    {!! Toastr::message() !!}

    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content mt-5">
                <div class="row mt-3">
                    <div class="col-sm-8">
                        <h4 class="page-title">Roles & Permissions</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-3 roles_class">
                        <a href="{{ route('roles.create') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i> Add Roles
                        </a>
                        <div class="roles-menu">
                            <ul class="roles-menu-ul">
                                @foreach ($roles as $role)
                                    <li class="{{ $firstRole->id === $role->id ? 'active' : '' }}" style="display: flex;flex-direction: row;justify-content: space-between;">
                                        <a href="{{ route('roles.show', $role->id) }}">
                                            {{ ucfirst($role->name) }}
                                        </a>
                                        <div>
                                            <button type="button" class="btn btn-danger btn-sm mt-1 mr-1" onclick="confirmDelete('{{ $role->id }}', '{{$role->name}}')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-8 col-md-8 col-lg-8 col-xl-9 roles_class">
                        <h6 class="card-title m-b-20">Module Access</h6>
                        
                        {{-- Update Permissions Form --}}
                        <form action="{{ route('roles.update', $firstRole->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="m-b-30">
                                <ul class="list-group">
                                    @foreach ($resourceNames as $resourceName)
                                        <li class="list-group-item">
                                            {{ ucfirst($resourceName) }}
                                            <div class="material-switch float-right">
                                                <input id="{{ $resourceName }}_module" type="checkbox" 
                                                    name="permissions[{{ $resourceName }}][module]"
                                                    value="{{ $resourceName }}"
                                                    {{ in_array($resourceName, $userPermissions->pluck('name')->toArray()) ? 'checked' : '' }}>
                                                <label for="{{ $resourceName }}_module" class="badge-success"></label>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped custom-table">
                                    <thead>
                                        <tr>
                                            <th>Module Permission</th>
                                            <th class="text-center">View</th>
                                            <th class="text-center">Create</th>
                                            <th class="text-center">Edit</th>
                                            <th class="text-center">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($resourceNames as $resourceName)
                                            <tr>
                                                <td>{{ ucfirst($resourceName) }}</td>
                                                @foreach (['view', 'create', 'edit', 'delete'] as $action)
                                                    <td class="text-center">
                                                        <input type="checkbox" 
                                                               name="permissions[{{ $resourceName }}][{{ $action }}]"
                                                               value="{{ $action . ' ' . $resourceName }}"
                                                               {{ in_array($action . ' ' . $resourceName, $userPermissions->pluck('name')->toArray()) ? 'checked' : '' }}>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <button type="submit" class="btn btn-primary buttonedit1 mt-4">Update Permissions</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="delete_role" class="modal fade delete-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h5>Are you sure you want to delete <span id="displayRoleName" class="text-primary"></span> Role?</h5>
                        <div class="m-t-20"> 
                            <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                            <form id="deleteRoleForm" action="" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-3.5.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        $(function () {
            $('#datetimepicker3').datetimepicker({
                format: 'LT'
            });
        });

        function confirmDelete(roleId, roleName) {
            var form = $('#deleteRoleForm');
            form.attr('action', '{{ url("roles") }}/' + roleId);
            $('#displayRoleName').text(roleName);
            $('#delete_role').modal('show');
        }
    </script>
@endsection
