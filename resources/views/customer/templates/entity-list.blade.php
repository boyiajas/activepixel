@extends('layouts.guest')

@section('content')
<div class="container-fluid">
    <div class="row pt-5">
        @include('customer.partials.sidebar')

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card mt-0">
                <div class="card-header">
                    <h4 class="card-title">{{ $entityName }}</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="entity-table">
                        <thead>
                            <tr>
                                @foreach($columns as $column)
                                    <th>{{ ucfirst(str_replace('_', ' ', $column)) }}</th>
                                @endforeach
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#entity-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("get-{$entityName}-data") }}',
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
