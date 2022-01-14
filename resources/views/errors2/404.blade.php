@extends('admin::layouts.error')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('Page Not Found'))
@section('text-color', 'success')
@section('body-class', 'sidebar-mini')
@section('breadcrumbs', \Breadcrumbs::render())
