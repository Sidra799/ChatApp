@extends('layouts.app')

@section('content')
<div  class="container">
   <contacts :user="{{auth()->user()}}"></contacts>
</div>
@endsection