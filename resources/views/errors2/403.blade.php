@extends('admin::layouts.error')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
@section('text-color', 'warning')
@section('body-class', 'sidebar-mini')
@section('breadcrumbs', \Breadcrumbs::render())
