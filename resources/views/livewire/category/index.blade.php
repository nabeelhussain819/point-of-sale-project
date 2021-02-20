<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            @if(auth()->user()->hasPermissionTo('category-create'))
                @if($updateMode)
                    @include('livewire.category.edit')
                @else
                    @include('livewire.category.create')
                @endif
            @endif

        </div>

        <div class="col-lg-6 col-md-6 col-sm-6">
            <table class="table">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Reference</th>
                    <th scope="col">Active</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $count = 1;
                @endphp
                @forelse($categories as $item)
                    <tr>
                        <td>{{$count++}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->full_name ?? 'No description'}}</td>
                        <td>{{$item->reference ?? 'No reference given'}}</td>
                        <td>
                            <span class="{!! $item->active == 0 ? 'badge badge-danger' : 'badge badge-success' !!}">{!!$item->active == 0 ? 'IN-ACTIVE' : 'ACTIVE'  !!}</span>
                        </td>
                        <td>{{$item->created_at}}</td>
                        <td>
                            <div style="display: flex">
                                @if(auth()->user()->hasPermissionTo('category-edit'))
                                    <button class="btn btn-info" wire:click="edit({{$item->id}})"><i
                                                class="fa fa-pen"></i></button>
                                @endif
                                @if(auth()->user()->hasPermissionTo('category-delete'))
                                    <button type="submit" class="btn btn-danger" wire:click="delete({{$item->id}})"><i class="fa fa-trash"></i></button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <p>No Category</p>
                @endforelse
                </tbody>
            </table>
            {{$categories->links('pagination::bootstrap-4')}}
        </div>
    </div>
</div>