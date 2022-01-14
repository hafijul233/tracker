@extends('admin::layouts.error')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('Unauthorized'))
@section('text-color', 'info')
@section('body-class', 'sidebar-mini')
@section('breadcrumbs', \Breadcrumbs::render())
