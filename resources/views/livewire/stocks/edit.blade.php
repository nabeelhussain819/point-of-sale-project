<div class="container">
    <h4>Edit Stock</h4>
    <div class="card shadow rounded">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <input wire:model="selected_id" type="hidden"/>
                        <label for="exampleInputEmail1">Name</label>
                        <input type="text" class="form-control" wire:model="name" placeholder="Name" required>
                    </div>
                </div>
            </div>
            <button type="submit" wire:click="update()" class="btn btn-success font-weight-bold shadow-lg rounded float-right">Edit</button>
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