<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="mt-2">
{{--            @if(!\App\Models\User::isSuperAdmin())--}}
            <ul class="nav nav-pills nav-sidebar flex-column"  data-widget="treeview" role="menu">
                <li style="margin: 2px; color: #fff;">Current Store</li>
                <form method="POST" action="{{route('store.id')}}">
                    @csrf
                <select name="store_id" class="form-control" onchange="this.form.submit()">
                    <option value="">No Store Selected</option>
                    @foreach(\App\Models\UserStore::getCurrentUserStore() as $item)
                        <option value="{{$item->store_id}}" type="submit" {{$item->store_id == \Illuminate\Support\Facades\Session::get('store_id') ? 'selected' : ''}}>{{$item->store->name}}</option>
                    @endforeach
                </select>
                </form>

            </ul>
{{--            @endif--}}
            <br>
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>
                {{-- Configured sidebar links --}}
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav>
    </div>

</aside>
