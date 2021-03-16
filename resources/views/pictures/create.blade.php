@extends('layouts/app')

@push('scripts')
<script src="/js/s3_upload.js"></script>
@endpush

@section('content')
<form class="s3upload" action="{{ route('pictures.store') }}" method="POST" enctype="multipart/form-data" data-s3attributes="{{ json_encode($s3attributes) }}" data-s3inputs="{{ json_encode($s3inputs) }}">
  @csrf
  <input type="text" name="title" />
  @error('title')
  <div class="alert alert-danger">{{ $message }}</div>
  @enderror
  <input type="file" name="file" />
  <input type="hidden" name="storage_path" value="{{ $s3inputs['key'] }}">
  <input type="submit" />
</form>
@endsection