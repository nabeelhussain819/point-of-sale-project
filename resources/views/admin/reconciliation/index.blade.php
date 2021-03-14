@extends('adminlte::page')

@section('title','Reconciliation Create')

@section('content')
 




    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr> 
                     <th>#</th>
                    <th>Title</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                   @foreach($reconciliations as $product)
                    <tr>
                        <td>{{$product->id}}</td>
                        <td>{{$product->name}}</td>
                        <td>
                            <!-- <a onclick="return confirm('Are you sure you want to concile? It will lock all the inventory')" href="{{route('conciliation',$product->id)}}">Reconcile</a> -->
                            <a href="{{route('conciliation',$product->id)}}">Reconcile</a>
                        </td>
                    </tr>
                @endforeach 
                  </tbody>
                  <tfoot>
                  <tr> 
                     <th>#</th>
                    <th>Title</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            </div>
            </div>
            </div>
            </section>
            <!-- /.card -->





 

@endsection 
<!-- @section('js')
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script> 
<script src="{{ asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script> 
<script src="{{ asset('vendor/datatables-responsive/js/dataTables.responsive.min.js') }}"></script> 
<script src="{{ asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>  
@endsection -->