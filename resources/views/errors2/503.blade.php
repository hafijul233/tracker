@extends('admin::layouts.error')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', __('Service Unavailable'))
@section('text-color', 'secondary')
@section('body-class', 'sidebar-mini')
@section('breadcrumbs', \Breadcrumbs::render())
