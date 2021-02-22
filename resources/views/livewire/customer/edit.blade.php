<div class="container">
    <h4>Edit Customer</h4>
    <div class="card shadow rounded">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="text" class="form-control" wire:model="name" id="exampleInputEmail1"  placeholder="Name" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" class="form-control"  wire:model="email" id="exampleInputEmail1"  placeholder="Email" required>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Phone</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" wire:model="phone" placeholder="Phone" required>
                    </div>
                    <div class="form-group ">
                        <label>Telephone</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" wire:model="telephone" placeholder="Telephone " required>
                    </div>
                </div>
            </div>
            <button type="submit" wire:click="store()" class="btn btn-success font-weight-bold shadow-lg rounded float-right">Edit</button>
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