<div class="container">
    <h4>New Brand</h4>
    <div class="card shadow rounded">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="text" class="form-control" wire:model="name" id="exampleInputEmail1"
                               placeholder="Name" required>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <br/>
                        <button wire:click="store()"
                                class="btn btn-success font-weight-bold shadow-lg rounded float-right">Add
                            New
                        </button>
                    </div>
                </div>

            </div>

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
</div>