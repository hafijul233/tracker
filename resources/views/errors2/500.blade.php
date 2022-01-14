@extends('admin::layouts.error')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Server Error'))
@section('text-color', 'danger')
@section('body-class', 'sidebar-mini')
@section('breadcrumbs', \Breadcrumbs::render())
