@extends('layouts.app')

@section('title')
    ADR Craft - Product
@endsection

@section('content')
     <div class="page-content pages-home">
        <section class="store-products">
            <div class="container">
                <div class="row">
                    <div class="col-12" data-aos="fade-up">
                        <h5>Our Product</h5>
                    </div>
                </div>
                <div class="row">
                    @php $incrementProduct = 0 @endphp
                    @forelse ($products as $product)
                        <div class="col-6 col-md-4 col-lg-3" 
                             data-aos="fade-up" 
                             data-aos-delay="{{ $incrementProduct+= 100 }}">
								<a href="{{ route('detail', $product->slug) }}" class="component-favorites d-block">
									<div class="products-thumbnail">
										<div class="products-image" 
										style="
											@if($product->galleries->count()) 
												background-image: url('{{ Storage::url($product->galleries->first()->photos) }}')
											@else 
												background-color: #eee
											@endif 
											">
										</div>
									</div>
									<div class="products-text">{{ $product->name }}</div>
								</a>
                          </div>
					@empty
						  <div class="col-12 text-center py-5"
								 data-aos = "fade-up"
								 data-aos-delay="100">
								 	No Product Found							  	
						  </div>
                        
                    @endforelse  
                </div>
                <div class="row">
                    <div class="col-12 mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </section>
        </div>
@endsection