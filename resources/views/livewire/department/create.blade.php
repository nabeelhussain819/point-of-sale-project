<div class="container">
<div class="card">
    <div class="card-header">
        <div class="card-title"> <h1>Create a new Department</h1></div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="exampleInputEmail1">Name</label>
            <input type="text" class="form-control" wire:model="name" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Name" required>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Full Name</label>
            <input type="text" class="form-control"  wire:model="fullName" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Full Name" required>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Reference</label>
            <input type="text" class="form-control" id="exampleInputEmail1" wire:model="reference" aria-describedby="emailHelp" placeholder="#Reference" required>
        </div>
        <div class="form-group ">
            <label>Status</label>
            <select class="form-control" wire:model="status">
                <option value="">Please Select Status</option>
                <option value="1">ACTIVE</option>
                <option value="0">IN-ACTIVE</option>
            </select>
        </div>
        <button type="submit" wire:click="store()" class="btn btn-primary">Submit</button>
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