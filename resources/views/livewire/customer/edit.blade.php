<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="card-title"> <h1>Edit Customer</h1></div>
        </div>
        <div class="card-body">
            <input type="hidden" wire:model="selected_id" />
            <div class="form-group">
                <label for="exampleInputEmail1">Name</label>
                <input type="text" class="form-control" wire:model="name" id="exampleInputEmail1"  placeholder="Name" required>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Full Name</label>
                <input type="text" class="form-control"  wire:model="email" id="exampleInputEmail1"  placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Reference</label>
                <input type="text" class="form-control" id="exampleInputEmail1" wire:model="phone" placeholder="Phone" required>
            </div>
            <div class="form-group ">
                <label>Status</label>
                <input type="text" class="form-control" id="exampleInputEmail1" wire:model="telephone" placeholder="Telephone " required>
            </div>
            <button type="submit" wire:click="update()" class="btn btn-primary">Submit</button>
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