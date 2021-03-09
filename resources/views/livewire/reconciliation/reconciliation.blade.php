<div>
  <form wire:submit.prevent="submit">
    <div class="form-group">
      <label for="exampleInputEmail1" class="font-weight-normal">Name</label>
      <input type="text" class="form-control" wire:model="name" id="exampleInputEmail1" aria-describedby="emailHelp"
             placeholder="Name" required>
      @error('name') <span class="error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="exampleInputEmail1" class="font-weight-normal">Name</label>
      <input type="text" class="form-control" wire:model="name" id="exampleInputEmail1" aria-describedby="emailHelp"
             placeholder="Name" required>
      @error('name') <span class="error">{{ $message }}</span> @enderror
    </div>

    <button type="submit" wire:click="store()" class="btn btn-success font-weight-bold shadow-lg rounded float-right">Add New</button>
  </form>
</div>
