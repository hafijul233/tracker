@extends('layouts.error')

@section('title', __('error.Server Error'))
@section('code', '500')
@section('message', __('error.Server Error'))
@section('text-color', 'danger')
@section('body-class', 'sidebar-mini')
@section('breadcrumbs', \Breadcrumbs::render('errors.500'))
