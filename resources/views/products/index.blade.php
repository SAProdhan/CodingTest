@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{route('product.filter')}}" method="POST" class="card-header">
        @csrf
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-4">
                    <select name="variant" class="form-control" style="width:100%;">
                    <option value="">Select variant</option>
                    @forelse($variants as $key=>$value)
                        <optgroup label="{{$key}}">
                            @foreach($value as $variant)
                            <option value="{{$variant->id}}">{{$variant->variant}}</option>
                            @endforeach
                        </optgroup>
                    @empty
                    @endforelse
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td width="3%" width="10">{{++$loop->index}}</td>
                        <td width="10%">{{$product->title}} <br> Created at : {{$product->created_at->diffInHours(now())}} hours ago</td>
                        <td width="30%">{{substr($product->description, 0, 400) }}</td>
                        <td width="40%">
                            
                            <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant{{$product->id}}">
                            @foreach($product->ProductVariantPrices as $variantprice)    
                                <dt class="col-sm-3 pb-0">
                                    <small> {{$variantprice->productvariantone->variant ?? ''}}/ {{$variantprice->productvarianttwo->variant ?? ''}}/ {{$variantprice->productvariantthree->variant ?? ''}}</small>
                                </dt>
                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 pb-0"><small> Price : {{ number_format($variantprice->price,2) }}</small></dt>
                                        <dd class="col-sm-8 pb-0"><small> InStock : {{ number_format($variantprice->stock,0) }}</small></dd>
                                    </dl>
                                </dd>
                                @endforeach
                            </dl>
                            
                            <button onclick="$('#variant{{$product->id}}').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                        </td>
                        <td width="10%">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                    <td colspan="5"></td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <!-- <p>Showing 1 to 10 out of 100</p> -->
                </div>
                <div class="col-md-3">
                    {{$products->links()}}
                </div>
            </div>
        </div>
    </div>

@endsection
