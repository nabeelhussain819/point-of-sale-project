<div>

    <div class="card shadow rounded">
        <div class="card-body">
    <div class="table-responsive">
        <h1>#{{$conciliation->id}} {{$conciliation->name}}</h1>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Product</th>
                {{--<th scope="col">description</th>--}}
                <th scope="col">physical quantity</th>
                <th scope="col">system quantity</th>
            </tr>
            </thead>
            <tbody>

            @foreach($inventories as $inventory)
                <tr>
                    <td>{{$inventory->id}}</td>
                    <td>{{$inventory->product->name}}</td>
                    <td>{{$concileData[$inventory->product_id]['physical_quantity']}}</td>
                    <td>{{$inventory->quantity}}</td>

                </tr>
            @endforeach
            </tbody>

            <tfoot>
            </tfoot>
        </table>

       @php $overstock='';
            $deficit='';
            $matched='' ;
            @endphp
            {{--// God please forgive me --}}
            @foreach($inventories as $inventory)
                @php  $i= '<div class="row">
                    <div class="col-2">'.$inventory->id.'</div>
                    <div class="col-2">'.$inventory->product->name.'</div>
                    <div class="col-2">'.$concileData[$inventory->product_id]['physical_quantity'].'</div>
                    <div class="col-2">'.$inventory->quantity.'</div>
                    <div class="col-2" id="action" data-id="'.$inventory->id.'" data-product-id="'.$inventory->product_id.'" data-physical-quantity="'.$concileData[$inventory->product_id]['physical_quantity'].'" data-quantity="'.$inventory->quantity.'" data-concile-id="'.$conciliation->id.'">Resolve</div>
                </div>';
                @endphp

                @if($concileData[$inventory->product_id]['physical_quantity'] > $inventory->quantity)
                    @php $overstock= $overstock .  $i;  @endphp
                @elseif($concileData[$inventory->product_id]['physical_quantity']<$inventory->quantity)
                    @php $deficit = $deficit . $i; @endphp
                @elseif($concileData[$inventory->product_id]['physical_quantity']==$inventory->quantity)
                    @php    $matched = $matched . $i;@endphp
                @endIf
            @endforeach

        <div class="row">
          <div class="col-12 col-sm-12">
            <div class="card card-primary card-tabs">
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">

                  <li class="nav-item">
                     
                    <a class="nav-link active" id="custom-tabs-one-overstock-tab" data-toggle="pill" href="#custom-tabs-one-overstock" role="tab" aria-controls="custom-tabs-one-overstock" aria-selected="true">Overstock</a>

                </li>
                <li class="nav-item" >
                    <a class="nav-link" id="custom-tabs-one-deficit-tab" data-toggle="pill" href="#custom-tabs-one-deficit" role="tab" aria-controls="custom-tabs-one-deficit" aria-selected="false">Deficit</a>    

                </li>

                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-matched-tab" data-toggle="pill" href="#custom-tabs-one-matched" role="tab" aria-controls="custom-tabs-one-matched" aria-selected="false">Matched</a>
 
                  </li>
                   
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-one-overstock" role="tabpanel" aria-labelledby="custom-tabs-one-overstock-tab" data-name="overstocks">
                     @if($overstock) 
                        {!! $overstock !!}
                    @else 
                        No OverStock Record Found 
                    @endif
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-one-deficit" role="tabpanel" aria-labelledby="custom-tabs-one-deficit-tab" data-name="deficit">
                     @if($deficit)  
                        {!! $deficit !!}
                    @else 
                        No Deficit Record Found 
                    @endif
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-one-matched" role="tabpanel" aria-labelledby="custom-tabs-one-matched-tab" >
                    @if($matched) 
                        {!! $matched !!}
                    @else 
                        No Record Found 
                    @endif
                  </div> 
                </div>
              </div>
              <!-- /.card -->
            </div>
          </div>
          </div>
        </div>
    </div>
        </div>
    </div>

</div>
 
@section('js')
<script type="text/javascript">
var APP_URL = {!! json_encode(url('/')) !!};
</script>
<script src="{{ asset('/js/common.js') }}"></script> 
<script src="{{ asset('/js/reconcillation.js') }}"></script> 
@endsection