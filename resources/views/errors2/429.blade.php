@extends('admin::layouts.error')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', __('Too Many Requests'))
@section('text-color', 'danger')
@section('body-class', 'sidebar-mini')
@section('breadcrumbs', \Breadcrumbs::render())
