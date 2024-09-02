@extends('layouts.admin')

@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5">
                            <h4 class="card-title float-left mt-2">All {{ $entityName }}</h4>
                            <a href="{{ route('admin.'.$entity.'.create') }}" class="btn btn-primary float-right veiwbutton">Add {{ $entityName }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body booking_card">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-center mb-0" id="{{ $entityName }}List">
                                    <thead>
                                        <tr>
                                            @foreach($columns as $column)
                                                <th>{{ ucfirst($column) }}</th>
                                            @endforeach
                                            <th>Modify</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Dynamic Rows --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('style')
        <link rel="stylesheet" href="{{ URL::to('assets/css/dataTables.dataTables.css') }}">
        <link rel="stylehseet" href="https://cdn.datatables.net/select/2.0.5/css/select.dataTables.css">
        <link rel="stylesheet" href="{{ URL::to('assets/css/buttons.dataTables.css') }}">

        <style>
            
            .selected{
                /* background-color: red !important; */
            }
            table.dataTable > tbody > tr.selected > * {
                box-shadow: inset 0 0 0 9999px rgba(0, 151, 178, 0.4);
                
                color: rgb(255, 255, 255);
            }
            table.dataTable > tbody > tr > .selected {
                background-color: rgba(0, 151, 178, 0.4);
                color: white;
            }
        </style>
    @endsection

    @section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#{{ $entityName }}List').DataTable({
                processing: true,
                serverSide: true,
                ordering: true,
                searching: true,
                ajax: {
                    url: "{{ route('get-'.$entity.'-data') }}",
                    error: function(xhr, error, code) {
                        console.log("AJAX Error: ", error);
                        console.log("Response Text: ", xhr.responseText);
                    }
                },
                pagingType: 'full_numbers',
                /* lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
                ], */
                                
                columns: [
                    @foreach($columns as $column)
                        { data: '{{ $column }}', name: '{{ $column }}' },
                    @endforeach
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                 select: {
                    style: 'multi'  // Allows multiple row selection
                }, 
               /*  dom: 'Bfrtip', */  // Button control

               layout: {
                    topStart: {
                        buttons: [
                            'pageLength',
                            {
                                text: 'Delete Selected',
                                action: function () {
                                    let selectedRows = table.rows({ selected: true }).data().toArray();
                                    let ids = selectedRows.map(row => row.id);

                                    if(ids.length === 0) {
                                        alert('No rows selected');
                                        return;
                                    }

                                    if(confirm('Are you sure you want to delete the selected rows?')) {
                                        $.ajax({
                                            url: "{{ route('delete-'.$entity.'-data') }}",  // API endpoint to delete
                                            type: 'DELETE',
                                            data: {
                                                ids: ids,
                                                _token: '{{ csrf_token() }}'  // Include CSRF token
                                            },
                                            success: function(response) {
                                                table.ajax.reload();  // Reload the table after deletion
                                                alert('Rows deleted successfully');
                                            },
                                            error: function(xhr, status, error) {
                                                alert('Failed to delete rows');
                                                console.error(error);
                                            }
                                        });
                                    }
                                }
                            },

                            'selected',
                            'selectAll',
                            'selectNone',
                            
                           /*  'selectRows', */
                        ]
                    }
               },
               select: true,
                
                               
                /* layout: {
                    topStart: {
                        buttons: [
                            'selected',
                            'selectedSingle',
                            'selectAll',
                            'selectNone',
                            'selectRows',
                            'selectColumns',
                            'selectCells',
                            'delete'
                        ]
                    }
                },
                select: true */

            });
        });
    </script>
    @endsection
@endsection
