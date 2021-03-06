@extends('layouts.dashboard')

@section('title')
  ADR Craft - Account Settings
@endsection

@section('content')
<!-- Section Content -->
<div
  class="section-content section-dashboard-home"
  data-aos="fade-up"
>
  <div class="container-fluid">
    <div class="dashboard-heading">
      <h2 class="dashboard-title">My Account</h2>
      <p class="dashboard-subtitle">
        Update your current profile
      </p>
    </div>
    <div class="dashboard-content">
      <div class="row">
        <div class="col-12">
          <form id="locations" action="{{ route('dashboard-settings-redirect','dashboard-settings-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="name">Your Name</label>
                      <input
                        type="text"
                        class="form-control"
                        id="name"
                        name="name"
                        value="{{ $user->name }}"
                      />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="email">Your Email</label>
                      <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        value="{{ $user->email }}"
                      />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="address_one">Address 1</label>
                      <input
                        type="text"
                        class="form-control"
                        id="address_one"
                        name="address_one"
                        value="{{ $user->address_one }}"
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
                      />
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="provinces_id">Province</label>
                      <select name="provinces_id" class="form-control">
                          <option value="" holder>Pilih Provinsi</option>
                          @foreach ($provinsi as $result)
                          <option value="{{ $result->id }}" @php if ($user->provinces_id == $result->id) { echo "selected"; } @endphp >{{ $result->province }}</option>
                          @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="regencies_id">City</label>
                      <select name="regencies_id" class="form-control"></select>
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
                      />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="phone_number">Mobile</label>
                      <input
                        type="text"
                        class="form-control"
                        id="phone_number"
                        name="phone_number"
                        value="{{ $user->phone_number }}"
                      />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col text-right">
                    <button
                      type="submit"
                      class="btn btn-success px-5"
                    >
                      Save Now
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


@push('addon-script')
    <script src="/vendor/vue/vue.js"></script>
    <script src="https://unpkg.com/vue-toasted"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <?php if ($user->provinces_id) { ?>
    <script>

        $( document ).ready(function() {
            $.ajax({
                url: 'getCity/' + {{ $user->provinces_id }},
                type: "GET",
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $.each(data, function (key, value) {
                        $('select[name="regencies_id"]').append(
                            '<option value="' +
                            value.id + '">' + value.city_name + '</option>');
                    });
                    $('select[name="regencies_id"]').val({{ $user->regencies_id }})
                }
            });
        });
    </script>
    <?php } ?>
    <script>
        $('select[name="provinces_id"]').on('change', function () {
                    var cityId = $(this).val();
                    if (cityId) {
                        $.ajax({
                            url: 'getCity/' + cityId,
                            type: "GET",
                            dataType: "json",
                            success: function (data) {
                                $('select[name="regencies_id"]').empty();
                                console.log(data);
                                $.each(data, function (key, value) {
                                    $('select[name="regencies_id"]').append(
                                        '<option value="' +
                                        value.id + '">' + value.city_name + '</option>');
                                });
                            }
                        });
                        $('#couriers').attr("disabled", false); 
                    } else {
                        $('select[name="regencies_id"]').empty();
                        $('#couriers').attr("disabled", true); 
                    }
                });
    </script>
    <script>
            $('select[name="provinces_id"]').on('change', function () {
                        var cityId = $(this).val();
                        if (cityId) {
                            $.ajax({
                                url: 'getCity/' + cityId,
                                type: "GET",
                                dataType: "json",
                                success: function (data) {
                                    $('select[name="regencies_id"]').empty();
                                    console.log(data);
                                    $.each(data, function (key, value) {
                                        $('select[name="regencies_id"]').append(
                                            '<option value="' +
                                            value.id + '">' + value.city_name + '</option>');
                                    });
                                }
                            });
                            $('#couriers').attr("disabled", false); 
                        } else {
                            $('select[name="regencies_id"]').empty();
                            $('#couriers').attr("disabled", true); 
                        }
                    });
    </script>
@endpush