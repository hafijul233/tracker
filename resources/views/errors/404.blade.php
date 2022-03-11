@extends('layouts.error')

@section('title', __('error.Page Not Found'))
@section('code', '404')
@section('message', __('error.Page Not Found'))
@section('text-color', 'success')
@section('body-class', 'sidebar-mini')
@section('breadcrumbs', \Breadcrumbs::render('errors.404'))
