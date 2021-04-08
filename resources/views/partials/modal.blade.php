<!-- Modal -->
<div class="modal fade" id="exampleModalCenter{{$modalId}}" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Assign Store to User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label>Name:</label>
                <p>{{$nameO}}</p>
                <label>Description: </label>
                <p>{{$description}}</p>
                <form action="{{route("$route")}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" value="{{$id}}" name="{{$name}}"/>
                    </div>
                    <div class="form-group">
                        <label>Select User</label>
                        @include('partials.user_look_up')
                    </div>

                    @if (isset($errors) && count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="assignform">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
