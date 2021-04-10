<div> @foreach($data as  $key=>$serial)
        <div class="form-check">
            <input class="form-check-input"
                   wire:click="handleSerial({{$serial->serial_no,$serial->product_id}})"
                   type="checkbox"
                   wire.model="postSerial.{{$key}}.{{$serial->product_id}}.[serial]"
                   name="postSerial[{{$serial->product_id}}][]"
                   value="{{$serial->serial_no}}"
                   id="serial-{{$serial->serial_no}}">
            <label class="form-check-label" for="flexCheckDefault">
                {{$serial->serial_no}}
            </label>
        </div>
    @endforeach
</div>
