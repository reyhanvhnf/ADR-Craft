@extends('layouts.app')

@section('title')
    ADR - Craft Homepage
@endsection

@section('content')
    <div class="page-content page-home">
      <section class="store-new-products">
        <div class="container">
          <div class="row">
            <div class="col-12" data-aos="fade-up">
              <h5>New Products</h5>
            </div>
          </div>
          <div class="row">
            @php $incrementProduct = 0 @endphp
            @forelse ($products as $product)
              <div
                class="col-6 col-md-4 col-lg-3"
                data-aos="fade-up"
                data-aos-delay="{{  $incrementProduct += 100 }}"
              >
                <a class="component-products d-block" href="{{ route('detail', $product->slug) }}">
                  <div class="products-thumbnail">
                    <div
                      class="products-image"
                      style="
                        @if($product->galleries->count( ))
                            background-image: url('{{ Storage::url($product->galleries->first()->photos) }}');
                        @else
                            background-color: #eee;
                        @endif
                      "
                    ></div>
                  </div>
                  <div class="products-text">
                    {{  $product->name }}
                  </div>
                  <div class="products-price">
                    Rp.{{ $product->price }}
                  </div>
                </a>
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