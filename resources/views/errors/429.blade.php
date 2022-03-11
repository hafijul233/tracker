@extends('layouts.error')

@section('title', __('error.Too Many Requests'))
@section('code', '429')
@section('message', __('error.Too Many Requests'))
@section('text-color', 'danger')
@section('body-class', 'sidebar-mini')
@section('breadcrumbs', \Breadcrumbs::render('errors.429'))
