@extends('layouts.app')

@section('title')
    ADR - Craft Cart Page
@endsection

@section('content')
    <!-- Page Content -->
    <div class="page-content page-cart">
      <section
        class="store-breadcrumbs"
        data-aos="fade-down"
        data-aos-delay="100"
      >
        <div class="container">
          <div class="row">
            <div class="col-12">
              <nav>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Home</a>
                  </li>
                  <li class="breadcrumb-item active">
                    Cart
                  </li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </section>

      <section class="store-cart">
        <div class="container">
          <div class="row" data-aos="fade-up" data-aos-delay="100">
            <div class="col-12 table-responsive">
              <table class="table table-borderless table-cart">
                <thead>
                  <tr>
                    <td>Image</td>
                    <td>Name</td>
                    <td>Quantity</td>
                    <td>Price</td>
                  </tr>
                </thead>
                <tbody>
                  @php $totalPrice = 0 @endphp
                  @foreach ($carts as $index=>$cart)
                    <tr>
                      <td style="width: 20%;">
                        @if($cart->product->galleries)
                          <img
                            src="{{ Storage::url($cart->product->galleries->first()->photos) }}"
                            alt=""
                            class="cart-image"
                          />
                        @endif
                      </td>
                      <td style="width: 35%;">
                        <div class="product-title">{{ $cart->product->name }}</div>
                      </td>
                      <td style="width: 35%;">
                        <form action="#">
                          <input type="hidden" value="{{ csrf_token() }}" id="quantityToken">
                            <div class="quantity">
                                <button type="button" data-quantity="minus" data-field="formInput{{ $index }}" data-stock="{{ $cart->product->stock }}" data-productId="{{ $cart->id }}" data-productPrice="{{ $cart->product->price }}"><i class="fas fa-minus"></i></button>
                                <input type="text" data-formQuantity="quantity" name="formInput{{ $index }}" id="quantity{{ $index }}" value="{{ $cart->quantity }}" data-stock="{{ $cart->product->stock }}" data-productId="{{ $cart->id }}" data-productPrice="{{ $cart->product->price }}"/>
                                <button type="button" data-quantity="plus" data-field="formInput{{ $index }}" data-stock="{{ $cart->product->stock }}" data-productId="{{ $cart->id }}" data-productPrice="{{ $cart->product->price }}"><i class="fas fa-plus"></i></button>
                            </div>
                        </form>
                      </td>
                      <td style="width: 35%;">
                        <div class="product-title" id="productPrice{{ $index }}">Rp.{{ number_format($cart->product->price) }}</div>
                        <div class="product-subtitle">IDR</div>
                      </td>
                      <td style="width: 20%;">
                        <form action="{{ route('cart-delete', $cart->id) }}" method="POST">
                          @method('DELETE')
                          @csrf
                          <button class="btn btn-remove-cart" type="submit">
                            Remove
                          </button>
                        </form>
                      </td>
                    </tr>
                    @php $totalPrice += $cart->product->price @endphp
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="row" data-aos="fade-up" data-aos-delay="150">
            <div class="col-12">
              <hr />
            </div>
            <div class="col-12">
              <h2 class="mb-4">Shipping Details</h2>
            </div>
          </div>
          <form action="{{ route('checkout') }}" id='locations' method="POST" enctype="multipart/form-data">
            @csrf
            {{-- <input type="hidden" name="total_price" value="{{ $totalPrice }}"> --}}
            <div class="row mb-2" data-aos="fade-up" data-aos-delay="200">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="address_one">Address 1</label>
                  <input
                    type="text"
                    class="form-control"
                    id="address_one"
                    name="address_one"
                    value="{{ $user->address_one }}"
                    readonly
                  />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="address_two">Address 2</label>
                  <input
                    type="text"
                    class="form-control"
                    id="address_two"
                    name="address_two"
                    value="{{ $user->address_two }}"
                    readonly
                    />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="provinces_id">Province</label>
                  {{--<select name="province_id" id="province_id" class="form-control">
                  <option value="">Province</option>
                  @foreach ($provinsi  as $row)
                  <option value="{{$row['province_id']}}" namaprovinsi="{{$row['province']}}">{{$row['province']}}</option>
                  @endforeach --}}
                  <select name="provinces_id" class="form-control" disabled>
                      <option value="" holder>Pilih Provinsi</option>
                      @foreach ($provinsi as $result)
                      <option value="{{ $result->id }}" @php if ($user->provinces_id == $result->id) { echo "selected"; } @endphp  >{{ $result->province }}</option>
                      @endforeach
                  </select>
                  {{--  <select v-else class="form-control"></select> --}}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="regencies_id">City</label>
                  {{--  <select name="regencies_id" id="regencies_id" class="form-control" v-model="regencies_id" v-if="regencies">
                    <option v-for="regency in regencies" :value="regency.id">@{{regency.name }}</option>
                  </select> --}}
                   <select name="regencies_id" class="form-control" disabled> </select>
                  {{--  <select v-else class="form-control"></select>--}}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="zip_code">Postal Code</label>
                  <input
                    type="text"
                    class="form-control"
                    id="zip_code"
                    name="zip_code"
                    value="{{ $user->zip_code }}"
                    readonly
                  />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="phone_number">Mobile</label>
                  <input
                    type="number"
                    class="form-control"
                    id="phone_number"
                    name="phone_number"
                    value="{{ $user->phone_number }}"
                    readonly
                  />
                </div>
              </div>
              <div class="col-md-6">
                <a href="{{ route('dashboard-settings-store') }}" class="mx-3 btn btn-success mt-3">Edit Shipping</a>
              </div>
            <div class="row" data-aos="fade-up" data-aos-delay="150">
              <div class="col-12">
                <hr />
              </div>
              <div class="col-12">
                <h2 class="mb-1">Payment Informations</h2>
                <select name="couriers" id="couriers" class="form-control mt-3 mb-2">
                    <option value="" holder>Pilih Kurir</option>
                    <option value="jne">JNE</option>
                    <option value="tiki">TIKI</option>
                    <option value="pos">POS Indonesia</option>
                </select>
                <select name="services" id="services" class="form-control mt-3 mb-2"></select>
                <table class="pay-info">
                    <tr>
                        <td width="50% " >Sub total</td>
                          <td width="50% " class="text-right" style="color: green;" id="subTotal"></td>
                    </tr>
                    <tr>
                        <td width="50% " >Pajak</td>
                            <td width="50% " class="text-right ">10%</td>
                    </tr>
                    <tr>
                        <td width="50% " >Ongkir</td>
                        <input type="hidden" name="ongkir">
                        <td width="50% " class="text-right" id="ongkir">Rp 0</td>
                    </tr>
                        <td width="50% " >Total Biaya</td>
                          <td width="50% " class="text-right" style="color: green;" id="totalBiaya"></td>
                    </tr>
                    
                </table>
            </div>
            </div>
              <div class="col-8 col-md-3">
                <button
                  type="submit"
                  class="btn btn-success mt-4 px-4 btn-block"
                >
                  Checkout Now
                </button>
              </div>
            </div>
          </form>
        </div>
      </section>
    </div>
@endsection

@push('addon-script')
<script src="/vendor/vue/vue.js"></script>
    <script src="https://unpkg.com/vue-toasted"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
       <script>
            var locations = new Vue({
                el: "#locations",
                mounted() {
                    AOS.init();
                    this.getProvincesData();
                },
                data: {
                    provinces: null,
                    regencies: null,
                    provinces_id: null,
                    regencies_id: null,
                    cities: null,
                    selectedCity: null,
                },
                methods: {
                    getProvincesData(){
                        var self = this;
                        axios.get('{{ route('api-provinces') }}')
                            .then(function(response){
                                self.provinces = response.data;
                            })
                    },
                    getRegenciesData(){
                        var self = this;
                        axios.get('{{ url('api/regencies') }}/' + self.provinces_id)
                            .then(function(response){
                                self.regencies = response.data;
                            })
                    }, 
                    getCity: function() {
                        axios.get('getCity/' + {{ $user->provinces_id }})
                        .then(function (response) {
                            cities = response.data
                            console.log(cities)
                        })
                     }
                },
                watch: {
                    provinces_id: function(val, oldVal) {
                        this.regencies_id = null;
                        this.getRegenciesData();
                    },
                }
            });
        </script>
        <script>
            jQuery(document).ready(function() {
                $.ajax({
                        url: 'getCity/' + {{ $user->provinces_id }},
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $.each(data, function (key, value) {
                                $('select[name="regencies_id"]').append(
                                    '<option value="' +
                                    value.id + '">' + value.city_name + '</option>');
                            });
                            $('select[name="regencies_id"]').val({{ $user->regencies_id }})
                            $('#couriers').attr("disabled", false); 
                        }
                    });

                let totalBiayaValue = 0;

                const jumlahItems = document.querySelectorAll(".items");
                const subTotal = document.getElementById('subTotal')
                const totalBiaya = document.getElementById('totalBiaya')
                let productPriceShow;
                let totalHarga = 0;

                for(i = 0; i < jumlahItems.length; i++){
                    const firstQuantity = document.getElementById('quantity' + i).value
                    let hargaProduk = document.getElementById('productPrice' + i).innerHTML
                    productPriceShow = document.getElementById('productPrice' + i)
                    const firstHargaProduk = hargaProduk * firstQuantity

                    totalHarga += firstHargaProduk;
                    
                    productPriceShow.innerText = 'Rp. ' + parseFloat(hargaProduk, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()
                    subTotal.innerText = 'Rp. ' + parseFloat(totalHarga, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()
                    totalBiaya.innerText = 'Rp. ' + parseFloat(totalHarga, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()
                    totalBiayaValue = totalHarga
                }

                // Ketika quantity diganti manual tanpa klik tombol
                let currenValueInput = 0;
                $("[data-formQuantity='quantity' ] ").on('focusin', function(e) {
                    currenValueInput = parseInt($(this).val());
                });
                
                $("[data-formQuantity='quantity' ] ").change(function(e) {
                    let quantity = 0
                    let stockBerubah = 0
                    const angka = parseInt($(this).val())
                    const stock = $(this).attr('data-stock');
                    const hargaProduk = $(this).attr('data-productPrice');
                    
                    if (angka > stock){
                        quantity = stock
                        $(this).val(stock)
                        $('input[name=quantity]').val(quantity);
                    } else {
                        quantity = angka
                        $('input[name=quantity]').val(quantity);
                    }

                    // Update Produk Price
                    if (currenValueInput < quantity) {
                        updateHarga = hargaProduk * (quantity - currenValueInput)
                        totalHarga += updateHarga;
                    } else {
                        updateHarga = hargaProduk * (currenValueInput - quantity)
                        totalHarga -= updateHarga;
                    }
                    subTotal.innerText = 'Rp. ' + parseFloat(totalHarga, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()
                    totalBiaya.innerText = 'Rp. ' + parseFloat(totalHarga + 10000, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()
                    totalBiayaValue = totalHarga

                    // Update quantity 
                    let productId = $(this).attr('data-productId');
                    let CSRFToken = document.getElementById("quantityToken").value

                    $.ajax({
                        url: `cart/${productId}`,
                        type: 'post',
                        data: {
                            _token: CSRFToken,
                            quantity: quantity
                        },
                    });
                });
            
                // This button will increment the value
                $("[data-quantity='plus' ] ").click(function(e) {
                    const hargaProduk = $(this).attr('data-productPrice');
                    let quantity;
                    // Stop acting like a button
                    e.preventDefault();
                    // Get the field name
                    fieldName = $(this).attr('data-field');
                    // Get stock
                    stock = $(this).attr('data-stock');
                    // Get its current value
                    var currentVal = parseInt($('input[name=' + fieldName + ']').val());
                    // If is not undefined
                    if (!isNaN(currentVal)) {
                        // Increment
                        quantity = currentVal + 1;
                        if (quantity > stock) {
                            quantity = stock
                        }
                        $('input[name=' + fieldName + ']').val(quantity);
                    } else {
                        // Otherwise put a 0 there
                        quantity = 0;
                        $('input[name=' + fieldName + ']').val(quantity);
                    }

                    // Update Produk Price
                    updateHarga = hargaProduk * (quantity - currentVal )
                    totalHarga += updateHarga;
                    subTotal.innerText = 'Rp. ' + parseFloat(totalHarga, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()
                    totalBiaya.innerText = 'Rp. ' + parseFloat(totalHarga + 10000, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()
                    totalBiayaValue = totalHarga

                    // Update quantity 
                    let productId = $(this).attr('data-productId');
                    let CSRFToken = document.getElementById("quantityToken").value

                    $.ajax({
                        url: `cart/${productId}`,
                        type: 'post',
                        data: {
                            _token: CSRFToken,
                            quantity: quantity
                        },
                    });
                });

                // This button will decrement the value till 0
                $("[data-quantity='minus' ] ").click(function(e) {
                    const hargaProduk = $(this).attr('data-productPrice');
                    let quantity;

                    // Stop acting like a button
                    e.preventDefault();
                    // Get the field name
                    fieldName = $(this).attr('data-field');
                    // Get its current value
                    var currentVal = parseInt($('input[name=' + fieldName + ']').val());
                    // If it isn't undefined or its greater than 0
                    if (!isNaN(currentVal) && currentVal > 0) {
                        // Decrement one
                        quantity = currentVal - 1;
                        $('input[name=' + fieldName + ']').val(quantity);
                    } else {
                        // Otherwise put a 0 there
                        quantity = 0;
                        $('input[name=' + fieldName + ']').val(quantity);
                    }

                    updateHarga = hargaProduk * (currentVal - quantity)
                    totalHarga -= updateHarga;
                    subTotal.innerText = 'Rp. ' + parseFloat(totalHarga, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()
                    totalBiaya.innerText = 'Rp. ' + parseFloat(totalHarga + 10000, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()
                    totalBiayaValue = totalHarga

                    // Update quantity 
                    let productId = $(this).attr('data-productId');
                    let CSRFToken = document.getElementById("quantityToken").value

                    $.ajax({
                        url: `cart/${productId}`,
                        type: 'post',
                        data: {
                            _token: CSRFToken,
                            quantity: quantity
                        },
                    });
                });

                $('#services').hide();
    
                $('select[name="couriers"]').on('change', function () {
                    var courier = $(this).val();
                    let ongkirShow = document.getElementById("ongkir")
                    let CSRFToken = '{{csrf_token()}}'
                    let dataRequest = {
                        destination: $('select[name=regencies_id] option').filter(':selected').val(),
                        courier: courier
                    }
                    $.ajax({
                        url: 'getOngkir',
                        type: "POST",
                        dataType: "json",
                        data: {
                            request: dataRequest,
                            _token: CSRFToken
                        },
                        success: function (data) {
                            $('#services').show();
                            $('select[name="services"]').empty();

                            // Reset ongkir
                            ongkirShow.innerText = 'Rp. ' + parseFloat(0, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()

                            $('select[name="services"]').append("<option value='0'>Pilih Layanan</option>");

                            data.map(item => {
                                $('select[name="services"]').append(
                                    '<option value="' +
                                        item.cost[0].value + '">' + item.service + ' - ' + ' est ' + item.cost[0].etd + ' hari' + '</option>');
                            })
                        }
                    });
                });

                $('select[name="services"]').on('change', function () {
                    let hargaOngkir = parseInt($(this).val())
                    let ongkirShow = document.getElementById("ongkir")
                    let totalBiaya = document.getElementById('totalBiaya')

                    $('input[name=ongkir]').val(hargaOngkir)
                    
                    totalBiaya.innerText = 'Rp. ' + parseFloat(totalBiayaValue + hargaOngkir, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()

                    ongkirShow.innerText = 'Rp. ' + parseFloat(hargaOngkir, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()
                });

            });
        </script>
@endpush