<!-- resources/views/welcome.blade.php -->

@extends('site.layouts.app')

@section('title', 'Anasayfa')

@section('content')

<section class="page-header page-header-dark bg-secondary">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h1>{{__("messages.offices")}}</h1>
      </div>

    </div>
  </div>
</section>

<div id="content">
  <div class="container office-cont">
    <div class="row">
      <div class="col-lg-4">
        <div class="bg-white shadow-md rounded p-15">
          <h6 class="text-6">BALIKESİR OTOGAR</h2>

        </div>
      </div>

      <div class="col-lg-4">
        <div class="bg-white shadow-md rounded p-15">
          <h6 class="text-6">BALIKESİR (TTM-EFETUR)</h6>

        </div>
      </div>

      <div class="col-lg-4">
        <div class="bg-white shadow-md rounded p-15">
          <h6 class="text-6">BALIKESİR (TTM-LEYDİ)</h6>

        </div>
      </div>


    </div>

    <br>
    <div class="row">
      <div class="col-lg-4">
        <div class="bg-white shadow-md rounded p-15">
          <h6 class="text-6">BALIKESİR (YONCA)</h6>

        </div>
      </div>

      <div class="col-lg-4">
        <div class="bg-white shadow-md rounded p-15">
          <h6 class="text-6">EDREMİT</h6>

        </div>
      </div>

      <div class="col-lg-4">
        <div class="bg-white shadow-md rounded p-15">
          <h6 class="text-6">EDREMİT-TAÇ</h6>

        </div>
      </div>


    </div>

    <br>
    <div class="row">
      <div class="col-lg-4">
        <div class="bg-white shadow-md rounded p-15">
          <h6 class="text-6">KÜÇÜKKUYU</h6>

        </div>
      </div>

      <div class="col-lg-4">
        <div class="bg-white shadow-md rounded p-15">
          <h6 class="text-6">AYVACIK</h6>

        </div>
      </div>

      <div class="col-lg-4">
        <div class="bg-white shadow-md rounded p-15">
          <h6 class="text-6">EZİNE</h6>

        </div>
      </div>


    </div>
    <br>
    <div class="row">
      <div class="col-lg-4">
        <div class="bg-white shadow-md rounded p-15">
          <h6 class="text-6">BURHANİYE</h6>

        </div>
      </div>


      <div class="col-lg-4">
        <div class="bg-white shadow-md rounded p-15">
          <h6 class="text-6">BURHANİYE-MERKEZ</h6>

        </div>
      </div>

      <div class="col-lg-4">
        <div class="bg-white shadow-md rounded p-15">
          <h6 class="text-6">ÇANAKKALE-DÖRTYOL</h6>

        </div>
      </div>


    </div><br>
    <div class="row">
      <div class="col-lg-4">
        <div class="bg-white shadow-md rounded p-15">
          <h6 class="text-6">ÇANAKKALE-İSKELE</h6>

        </div>
      </div>
      <div class="col-lg-4">
        <div class="bg-white shadow-md rounded p-15">
          <h6 class="text-6">ÇANAKKALE-MERKEZ</h6>

        </div>
      </div>

      <div class="col-lg-4">
        <div class="bg-white shadow-md rounded p-15">
          <h6 class="text-6">AYVALIK</h6>

        </div>
      </div>
    </div><br>

    <div class="row">


      <div class="col-lg-4">
        <div class="bg-white shadow-md rounded p-15">
          <h6 class="text-6">AYVALIK-MERKEZ</h6>

        </div>
      </div>
    </div><br>

  </div><br>
</div>




@endsection