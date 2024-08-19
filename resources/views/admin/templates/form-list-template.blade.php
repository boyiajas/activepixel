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

    @section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#{{ $entityName }}List').DataTable({
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
                columns: [
                    @foreach($columns as $column)
                        { data: '{{ $column }}', name: '{{ $column }}' },
                    @endforeach
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
    @endsection
@endsection
