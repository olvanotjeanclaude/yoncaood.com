<!-- resources/views/welcome.blade.php -->

@extends('site.layouts.app')

@section('title', 'Anasayfa')

@section('content')

<section class="page-header page-header-dark bg-secondary">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h1>{{__("messages.about")}}</h1>
      </div>

    </div>
  </div>
</section>

<div id="content">
  <div class="container bg-white shadow-md mb-50">
    <div class="row">
      <div class="col-md-7">
        <div class="rounded p-4">
          <h2 class="text-6">{{__("messages.company.title")}}</h2>
          <p>{{__("messages.company.paragraph_1")}}</p>
          <p>{{__("messages.company.paragraph_2")}}</p>
          <p>{{__("messages.company.paragraph_3")}}</p>
          <p>{{__("messages.company.paragraph_4")}}</p>
        </div>
      </div>
      <div class="col-md-5">
        <div class="div p-4 mt-3">
          <img src="img/yonca-ood-r.jpg" alt="Lüks Yonca OOD" class="img-fluid rounded1">
        </div>
      </div>
    </div>

  </div>
</div>




@endsection