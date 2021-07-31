@extends('layouts.app')

@section('title')
    ADR - Craft 
@endsection

@section('content')
    <div class="page-content page-home">
     {{-- <section class="store-carousel">
      <div class="container">
          <div class="row">
          <div class="col-lg-12 mb-4" data-aos="zoom-in">
              <div
              id="storeCarousel"
              class="carousel slide"
              data-ride="carousel"
              >
              <ol class="carousel-indicators">
                  <li
                  class="active"
                  data-target="#storeCarousel"
                  data-slide-to="0"
                  ></li>
                  <li data-target="#storeCarousel" data-slide-to="1"></li>
                  <li data-target="#storeCarousel" data-slide-to="2"></li>
              </ol>
              <div class="carousel-inner">
                  <div class="carousel-item active">
                  <img
                      src="/images/banner.jpg"
                      alt="Carousel Image"
                      class="d-block w-100"
                  />
                  </div>
                  <div class="carousel-item">
                  <img
                      src="/images/banner.jpg"
                      alt="Carousel Image"
                      class="d-block w-100"
                  />
                  </div>
                  <div class="carousel-item">
                  <img
                      src="/images/banner.jpg"
                      alt="Carousel Image"
                      class="d-block w-100"
                  />
                  </div>
              </div>
              </div>
          </div>
          </div>
      </div>
      </section> --}}

      <section class="store-new-products">
        <div class="container">
          <div class="row">
            <div class="col-12 mb-4" data-aos="fade-up">
              <h5>New Product</h5>
            </div>
          </div>
          <div class="row">
            @php $incrementProduct = 0 @endphp
            @forelse ($products as $product)
              <div class="col-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
              <div class="product-area pb-155">
                  <div class="container">
                      <div class="product-slider-active-4">
                          <div class="product-wrap-plr-1">
                              <div class="product-wrap" style="box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.16); border-radius: 11px;">
                                  <div class="product-img product-img-zoom mb-25">
                                      <a href="{{ route('detail', $product->slug) }}">
                                        @if ($product->galleries->count())
                                        <img src="{{ Storage::url($product->galleries->first()->photos) }}" alt="">
                                        @else
                                        <img src="{{ asset('images/product/no-img.jpg')}}" alt="">
                                        @endif 
                                      </a>
                                  </div>
                                  <div class="product-content mt-3 px-3 pb-2 ">
                                      <h4><a href="{{ route('detail', $product->slug) }}" class="">{{ $product->name }}</a></h4>
                                      <div class="product-price">
                                          <span class="text-danger">Rp {{ number_format($product->prices) }}</span>
                                          <!-- <span class="old-price">$ 130</span> -->
                                      </div>
                                  </div>
                                  <div class="product-action-position-1 text-center">
                                      <div class="product-content">
                                          <h4><a href="{{ route('detail', $product->slug) }}">{{ $product->name }}</a></h4>
                                          <div class="product-price">
                                              <span class="text-danger">Rp {{ number_format($product->prices) }}</span>
                                              <!-- <span class="old-price">$ 130</span> -->
                                          </div>
                                      </div>
                                      <div class="product-action-wrap">
                                          <div class="product-action-cart">
                                              <a href="{{ route('detail', $product->slug) }}" class="btn btn-dark text-white rounded-0 py-1 px-4" style="font-size: 14px" >details</a>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
             </div> 
            @empty
              <div
                    class="col-12 text-center py-5"
                    data-aos="fade-up"
                    data-aos-delay="100"
                >
                    No Products Found
              </div>
            @endforelse
          </div>
        </div>
      </section>
    </div>
@endsection