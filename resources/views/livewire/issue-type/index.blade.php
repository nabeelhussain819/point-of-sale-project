<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            @if(auth()->user()->hasPermissionTo('customer-create'))
                @if(auth()->user()->hasPermissionTo('category-create'))
                    @if($updateMode)

                        @include('livewire.device-type.edit')
                    @else
                        @include('livewire.device-type.create')
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
                        <th scope="col">Name</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @forelse($devicesType as $deviceType)
                        <tr>
                            <td>{{$deviceType->id}}</td>
                            <td>{{$deviceType->name}}</td>
                            <td>
                                <div style="display: flex">
                                    @if(auth()->user()->hasPermissionTo('customer-edit'))
                                        <button class="btn btn-info mr-1" wire:click="edit({{$deviceType->id}})"><i
                                                    class="fa fa-pen"></i></button>
                                    @endif
                                    @if(auth()->user()->hasPermissionTo('customer-delete'))
                                        <button type="submit" class="btn btn-danger ml-1"
                                                wire:click="delete({{$deviceType->id}})">
                                            <i class="fa fa-trash"></i></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <p>No Issue</p>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
