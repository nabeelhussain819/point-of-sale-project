<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            @if(auth()->user()->hasPermissionTo('customer-create'))
                @if(auth()->user()->hasPermissionTo('category-create'))
                    @if($updateMode)

                        @include('livewire.products.edit')
                    @else
                        @include('livewire.products.create')
                    @endif
                @endif

            @endif
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 ">
            <div class="table-responsive">
                <table class="table mt-5">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product</th>
                        <th scope="col">Device Type</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @forelse($associations as $association)
                        <tr>
                            <td>{{$association->id}}</td>
                            <td>{{$association->product->name}}</td>
                            <td>{{$association->brand->name}}</td>
                            <td>{{$association->devicesType->name}}</td>


                            <td>
                                <div style="display: flex">
                                    @if(auth()->user()->hasPermissionTo('customer-edit'))
                                        <button class="btn btn-info mr-1" wire:click="edit({{$association->id}})"><i
                                                    class="fa fa-pen"></i></button>
                                    @endif
                                    @if(auth()->user()->hasPermissionTo('customer-delete'))
                                        <button type="submit" class="btn btn-danger ml-1"
                                                wire:click="delete({{$association->id}})">
                                            <i class="fa fa-trash"></i></button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                    @empty
                        <p>No customers</p>
                    @endforelse

                    </tbody>
                </table>
                {{ $associations->links() }}
            </div>
        </div>
    </div>
</div>
