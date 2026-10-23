<!-- resources/views/welcome.blade.php -->

@extends('site.layouts.app')

@section('title', 'Anasayfa')

@section('content')

<div class="page-header page-header-dark bg-secondary">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h1>{{ __('messages.contact') }}</h1>
      </div>
      <!-- <div class="col-md-4">
        <ul class="breadcrumb justify-content-start justify-content-md-end mb-0">
          <li><a href="index.php">Anasayfa</a></li>
          <li class="active">İletişim</li>
        </ul>
      </div> -->
    </div>
  </div>
</div>



<div class="container">
  <div class="row">
    <div class="col-md-6">
      <div class="bg-white shadow-md rounded p-4 mb-50">
        <h2 class="text-6 mb-4">{{ __('messages.page.title') }}</h2>
        <hr class="mx-n4 mb-4">
        <p class="text-3">{{ __('messages.page.description') }}</p>

        <div class="featured-box style-1">
          <div class="featured-box-icon text-primary"><i class="fas fa-map-marker-alt"></i></div>
          <h3>{{ __('messages.address.title') }}</h3>
          <p class="p-1">{{ __('messages.address.value') }}</p>
        </div>

        <div class="featured-box style-1">
          <div class="featured-box-icon text-primary"><i class="fas fa-phone"></i></div>
          <h3>{{ __('messages.phone.title') }}</h3>
          <p class="p-1">
            <a href="tel:+00359883956100" class="text-color">
              <i style="font-size:17px;" class="fas fa-phone"></i> {{ __('messages.phone.value') }}
            </a>
          </p>
        </div>

        <div class="featured-box style-1">
          <div class="featured-box-icon text-primary"><i class="fas fa-envelope"></i></div>
          <h3>{{ __('messages.email.title') }}</h3>
          <p class="p-1">
            <a href="mailto:info@luksyonca.com.tr" class="text-color">
              <i style="font-size:17px;" class="fas fa-envelope"></i> {{ __('messages.email.value') }}
            </a>
          </p>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="shadow-md rounded overflow-hidden" style="height: 440px;">
        <iframe
         src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2982.0199836749593!2d25.375020277249615!3d41.633699480678885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14ad732d20923af1%3A0x57c5f5498b539774!2z0JDQstGC0L7Qs9Cw0YDQsCDQmtGK0YDQtNC20LDQu9C4IEvEsXJjYWFsaSBPdG9nYXI!5e0!3m2!1str!2str!4v1748952822269!5m2!1str!2str"
          width="100%"
          height="100%"
          style="border:0;"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </div>
</div>






@endsection