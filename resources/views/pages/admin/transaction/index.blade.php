@extends('layouts.admin')

@section('title')
    ADR Craft -Transaction
@endsection


@section('content')
    <div class="section-content section-dashboard-home" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Transaction</h2>
                <p class="dashboard-subtitle">List of Transaction</p>
            </div>
            <div class="dashboard-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body p-md-4">
                                <div class="row mb-5 text-center ">
                                    <div class="col-sm-3 mr-5">
                                        <label>From </label>
                                        <input id="date-dari" width="270" name="dari" value="" autocomplete="off" />
                                    </div>
                                    <div class="col-sm-3 mr-5">
                                        <label>To </label>
                                        <input id="date-ke" width="270" name="ke" value="" autocomplete="off" />
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3 mr-3">
                                            <button onclick="filter()" class="btn btn-primary px-4" name="button">Filter
                                                Date</button>
                                        </div>
                                        <div class="col-sm-3">
                                            <button onclick="exportKeExcel()" class="btn btn-success px-4">Print Report</button>
                                        </div>
                                    </div>
                                </div>

                                {{-- </form> --}}
                                <div class="table-responsive">
                                    <table class="table table-hover scroll-horizontal-vertical w-100" id="crudTable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Transaction</th>
                                                <th>Payment</th>
                                                <th>Date</th>
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
            </div>

        </div>
    </div>
@endsection

@push('addon-script')
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script>
        $(document).ready(function() {
            $('#date-dari').datepicker();
            $('#date-ke').datepicker();
        });

    </script>
    <script>
        var datatable = $('#crudTable').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            ajax: {
                url: '{!! url()->current() !!}',
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'user.name',
                    name: 'user.name'
                },
                {
                    data: 'total_price',
                    name: 'total_price'
                },
                {
                    data: 'transaction_status',
                    name: 'transaction_status'
                },
                {
                    data: 'status_pay',
                    name: 'status_pay'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searcable: false,
                    width: '15%'
                },
            ]
        })

        function filter() {
            let dariTanggal = new Date($('#date-dari').val()).toISOString().split('T')[0];
            let sampaiTanggal = new Date($('#date-ke').val()).toISOString().split('T')[0];
            $("#crudTable").dataTable().fnDestroy()
            $('#crudTable').DataTable({
                processing: true,
                serverSide: true,
                ordering: true,
                ajax: {
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        dari: dariTanggal,
                        ke: sampaiTanggal
                    },
                    url: "{{ route('filter') }}",
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name'
                    },
                    {
                        data: 'total_price',
                        name: 'total_price'
                    },
                    {
                        data: 'transaction_status',
                        name: 'transaction_status'
                    },
                    {
                        data: 'status_pay',
                        name: 'status_pay'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searcable: false,
                        width: '15%'
                    },
                ]
            })
        }

        function exportKeExcel() {
            let dariTanggal = new Date($('#date-dari').val()).toISOString().split('T')[0];
            let sampaiTanggal = new Date($('#date-ke').val()).toISOString().split('T')[0];

            $.ajax({
                type: "POST",
                xhrFields: {
                    responseType: 'blob',
                },
                data: {
                    _token: '{{ csrf_token() }}',
                    dari: dariTanggal,
                    ke: sampaiTanggal
                },
                url: "{{ route('rekap') }}",
                success: function(result, status, xhr) {
                    var disposition = xhr.getResponseHeader('content-disposition');
                    var matches = /"([^"]*)"/.exec(disposition);
                    var filename = (matches != null && matches[1] ? matches[1] : 'Rekap.xlsx');

                    var blob = new Blob([result], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;

                    document.body.appendChild(link);

                    link.click();
                    document.body.removeChild(link);
                }
            });
        }

    </script>
@endpush
