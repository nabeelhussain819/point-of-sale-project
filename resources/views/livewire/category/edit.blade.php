<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="card-title"> <h1>Edit Category</h1></div>

        </div>
        <div class="card-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" class="form-control" wire:model="name" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Name" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Full Name</label>
                    <input type="text" class="form-control" wire:model="fullName" id="exampleInputEmail1"  aria-describedby="emailHelp" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Reference</label>
                    <input type="text" class="form-control" wire:model="reference" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="#Reference" required>
                </div>
                <div class="form-group ">
                    <label>Status</label>
                    <select name="active" class="form-control" wire:model="status" required>
                        <option value="">Please Select Status</option>
                        <option value="0">IN-ACTIVE</option>
                        <option value="1">ACTIVE</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" wire:click="update()">Submit</button>
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