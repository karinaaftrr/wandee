@extends('errors::minimal')

@section('title', 'Permintaan Tidak Valid | Wandee')
@section('code', $exception->getStatusCode() ?? '400')
@section('message', 'Permintaan Tidak Valid')
@section('description', $exception->getMessage() ?: 'Permintaan yang dikirimkan tidak dapat diproses. Silakan periksa kembali alamat atau masukan Anda.')
