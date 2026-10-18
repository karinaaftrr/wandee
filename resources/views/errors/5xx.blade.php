@extends('errors::minimal')

@section('title', 'Gangguan Server | Wandee')
@section('code', $exception->getStatusCode() ?? '500')
@section('message', 'Terjadi Kendala pada Sistem')
@section('description', 'Sistem kami sedang mengalami kendala tak terduga. Silakan coba kembali beberapa saat lagi.')
