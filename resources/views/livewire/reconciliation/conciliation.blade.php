<div>
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

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                        data-bs-target="#home" type="button"
                        role="tab" aria-controls="home" aria-selected="true">Overstock
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                        data-bs-target="#profile" type="button"
                        role="tab" aria-controls="profile" aria-selected="false">Deficit
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                        data-bs-target="#contact" type="button"
                        role="tab" aria-controls="contact" aria-selected="false">Matched
                </button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">

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

            <div class="tab-pane fade show active" data-name="overstocks" id="home" role="tabpanel" aria-labelledby="home-tab">
                @if($overstock) 
                    <div class="" id=""> Match All OverStocks
                    </div>
                    {!! $overstock !!}
                @else 
                    No OverStock Record Found 
                @endif
            </div>
            <div class="tab-pane fade" data-name="deficit" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                @if($deficit)  
                    {!! $deficit !!}
                @else 
                    No Deficit Record Found 
                @endif
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                @if($matched) 
                    {!! $matched !!}
                @else 
                    No Record Found 
                @endif
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
     
 