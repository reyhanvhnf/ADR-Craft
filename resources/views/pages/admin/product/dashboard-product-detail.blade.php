@extends('layouts.admin')

@section('title')
    ADR Craft
@endsection

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">{{ $product->name }}</h2>
            <p class="dashboard-subtitle">Product Details</p>
        </div>
        <div class="dashboard-content">
            <div class="row">
                <div class="col-12">
                    @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>                                        
                                    @endforeach
                                </ul>
                            </div>
                    @endif
                    <form action="{{ route('dashboard-product-update', $product->id_product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card card-list p-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Product Name</label>
                                            <input type="text" class="form-control" name="name" value="{{ $product->name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Price</label>
                                            <input type="number" class="form-control uang" name="prices" value="{{ $product->prices }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Stock</label>
                                            <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Description</label>
                                            <textarea name="description" id="editor">{!! $product->description !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 text-right">
                                        <button type="submit" class="btn btn-success btn-block px-5">
                                    Save Now
                                    </button>
                                    </div>    
                                </div>
                            </div>
                    </form>
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-danger btn-block px-5 btn-delete" productId={{ $product->id_product }} id="delete">
                            Hapus Product
                            </button>
                        </div>
                        <div class="col-12 pt-3">
                            <p class="text-warning">Note : Jika ingin menambahkan foto, harap save terlbih dahulu.</p>
                        </div>
                        
                </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <div class="card card-list">
                        <div class="card-body ">
                            <div class="row">
                                @foreach ($product->galleries as $gallery)
                                    <div class="col-md-4">
                                        <div class="gallery-container">
                                            <img src="{{ Storage::url($gallery->photos ?? '') }}" alt="" class="w-100">
                                            <a href="{{ route('dashboard-product-gallery-delete', $gallery->id_gallery) }}" class="delete-gallery">
                                                <img src="{{ asset('images/icon-delete.svg') }}" alt="">
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-12">
                                    <form action="{{ route('dashboard-product-gallery-upload') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="products_id" value="{{ $product->id_product }}">
                                        <input type="file" name="photos" id="file" style="display: none;" onchange="form.submit()">
                                        <p class="text-danger">* Image must be 764 x 560 pixel</p>
                                        <button type="button" class="btn btn-secondary btn-block" onclick="thisFileUpload()">
                                        Tambah Foto
                                        </button>
                                    </form>
                                </div>
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
    <script src="https://cdn.ckeditor.com/4.15.1/standard/ckeditor.js"></script>
    <script>
        function thisFileUpload() {
            document.getElementById('file').click();
        }
    </script>
    <script>
        CKEDITOR.replace('editor');
    </script>
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

        $(".btn-delete").on("click", function (e) {
            e.preventDefault();
            let form = $(this).parents('form');
            let productId = $(this).attr("productId");

            Swal.fire({
                title: 'Konfirmasi',
                text: "Yakin ingin menghapus data ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#34a0a4',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let CSRFToken = "{{ csrf_token() }}"
                        $.ajax({
                            url: "{{ url('admin/product/') . '/' }}" + productId,
                            type: "POST",
                            data: {
                                _method: "DELETE",
                                _token: CSRFToken
                            },
                            success: function () {
                                // Set Session storage
                                sessionStorage.setItem("hapus", "1");
                                window.location.href = "/admin/product"

                            },
                            error: function(ea) {
                                console.log(ea)
                            }
                        });
                    }
                })
            });
    </script>
@endpush



