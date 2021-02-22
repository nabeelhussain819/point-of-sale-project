<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            @if(auth()->user()->hasPermissionTo('customer-create'))
                @if($updateMode)
                    @include('livewire.customer.edit')
                @else
                    @include('livewire.customer.create')
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
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Telephone</th>
                        {{--                    <th scope="col">Created At</th>--}}
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @forelse($customers as $item)
                        <tr>
                            <td>{{$count++}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->email}}</td>
                            <td>{{$item->phone}}</td>
                            <td>{{$item->telephone}}</td>
                            {{--                        <td>{{$item->created_at}}</td>--}}
                            <td>
                                <div style="display: flex">
                                    @if(auth()->user()->hasPermissionTo('customer-edit'))
                                        <button class="btn btn-info" wire:click="edit({{$item->id}})"><i
                                                    class="fa fa-pen"></i></button>
                                    @endif
                                    @if(auth()->user()->hasPermissionTo('customer-delete'))
                                        <button type="submit" class="btn btn-danger" wire:click="delete({{$item->id}})">
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
            </div>
        </div>
    </div>
</div>
