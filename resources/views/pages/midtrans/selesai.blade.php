@extends('layouts.success')

@section('title')
selesai
    ADR &#8211; Pancarkan Pesona Cantikmu 
@endsection

@section('content')
    <!-- page content -->
    @if($db->status_pay == 'PENDING')
    <div class="page-content page-success">
        <div class="section-success" data-aos="zoom-in">
          <div class="container">
            <div class="row align-items-center row-login justify-content-center">
              <div class="col-lg-6 text-center">
                <img src="{{ asset('images/checkout.svg') }}" alt="" class="mb-4" />
                <h2>
                  Transaction Failed!
                </h2>
                <p>
                  Silahkan selesaikan pembayaran yang telah ditentukan 
                  agar barang dapat diproses segera mungkin!
                </p>
                <div>
                  <a href="{{ route('dashboard') }}" class="btn btn-success w-50 mt-4">
                    My Dashboard
                  </a>
                  <a href="{{ route('home') }}" class="btn btn-signup w-50 mt-2">
                    Go To Shopping
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @elseif($db->status_pay == 'SUCCESS')
    <div class="page-content page-success">
        <div class="section-success" data-aos="zoom-in">
          <div class="container">
            <div class="row align-items-center row-login justify-content-center">
              <div class="col-lg-6 text-center">
                <img src="{{ asset('images/checkout.svg') }}" alt="" class="mb-4" />
                <h2>
                  Transaction Processed!
                </h2>
                <p>
                  Silahkan tunggu, pesanan sedang kami proses, kami akan
                  menginformasikan resi secept mungkin!
                </p>
                <div>
                  <a href="{{ route('dashboard') }}" class="btn btn-success w-50 mt-4">
                    My Dashboard
                  </a>
                  <a href="{{ route('home') }}" class="btn btn-signup w-50 mt-2">
                    Go To Shopping
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @else
    <div class="page-content page-success">
        <div class="section-success" data-aos="zoom-in">
          <div class="container">
            <div class="row align-items-center row-login justify-content-center">
              <div class="col-lg-6 text-center">
                <img src="{{ asset('images/checkout.svg') }}" alt="" class="mb-4" />
                <h2>
                  Transaction Expired!
                </h2>
                <p>
                  Silahkan Order Kembali!
                </p>
                <div>
                  <a href="{{ route('dashboard') }}" class="btn btn-success w-50 mt-4">
                    My Dashboard
                  </a>
                  <a href="{{ route('home') }}" class="btn btn-signup w-50 mt-2">
                    Go To Shopping
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
@endsection