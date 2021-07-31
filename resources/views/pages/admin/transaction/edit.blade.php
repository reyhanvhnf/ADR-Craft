@extends('layouts.admin')

@section('title')
    ADR Craft -Transaction
@endsection

@section('content')
    <div class="section-content section-dashboard-home" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Transaction</h2>
                <p class="dashboard-subtitle">Edit transaction</p>
            </div>
            <div class="dashboard-content" id="transactionDetails">
                <div class="row">
                    <div class="col-md-12">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>                                        
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('transaction.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                    @method("PUT")
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Transaction Status</label>
                                                <select name="transaction_status" id="status" v-model="status" class="form-control">
                                                    <option value="" disabled>-------------</option>
                                                    <option value="PENDING">PENDING</option>
                                                    <option value="SHIPPING">SHIPPING</option>
                                                    <option value="SUCCESS">SUCCESS</option>
                                                </select>
                                            </div>
                                        </div>
                                        <template v-if="status == 'SHIPPING' ">
                                            <div class="col-md-4">
                                                <label>Input Resi : <b class="text-uppercase text-success">{{ $item->courier }} {{ $item->service }} </b> </label>
                                                <input type="text" class="form-control" name="resi" v-model="resi" > 
                                            </div>
                                        </template>
                                    </div>  
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label>Total Price</label>
                                                <input type="number" name="total_price" class="form-control" value="{{ $item->total_price }}" required readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col text-left">
                                            <button type="submit" class="btn btn-success px-5">
                                                Save Now
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection

@push('addon-script')
    <script src="{{ asset('vendor/vue/vue.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/23.1.0/classic/ckeditor.js"></script>
    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
    <script src="/vendor/vue/vue.js"></script>
    <script>
        var transactionDetails = new Vue({
            el: '#transactionDetails',
            data: {
                status: "{{ $item->transaction_status }}",
                resi: "{{ $item->resi }}",
            },
        })
    </script>
    <script>
    ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .then( editor => {
                    console.log( editor );
            } )
            .catch( error => {
                    console.error( error );
            } );
    </script>
@endpush

