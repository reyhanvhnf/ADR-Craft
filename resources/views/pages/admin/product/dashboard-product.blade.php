@extends('layouts.admin')

@section('title')
    ADR Craft - Product Details
@endsection

@section('content')
    <div class="section-content section-dashboard-home" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Products</h2>
                <p class="dashboard-subtitle">List of Products</p>
            </div>
            <div class="dashboard-content">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('dashboard-product-create') }}" class="btn btn-success">Add New Product</a>
                    </div>
                </div>
                <div class="row mt-4">
                    @foreach ($products as $product)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <a href="{{ route('dashboard-product-details', $product->id) }}" class="card card-dashboard-product d-block" >                              
                                <div class="card-body" >
                                    <img src="{{ Storage::url($product->galleries->first()->photos ?? '') }}" alt="" class="w-100 mb-2">
                                    <div class="myproduct-title ">{{ $product->name }}</div>
                                    <div class="product-price text-danger">Rp {{ number_format($product->prices) }}</div>
                                </div>  
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection


@push('addon-script')
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    const notifikasiHapus = sessionStorage.getItem("hapus");

    if (notifikasiHapus) {
        Toast.fire({
            icon: 'success',
            title: 'Berhasil di hapus!'
        });

        // Hapus session
        sessionStorage.removeItem("hapus")

    }
</script>
@endpush
