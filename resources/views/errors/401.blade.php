@extends('layouts.error')

@section('title', __('error.Unauthorized'))
@section('code', '401')
@section('message', __('error.Unauthorized'))
@section('text-color', 'info')
@section('body-class', 'sidebar-mini')
@section('breadcrumbs', \Breadcrumbs::render('errors.401'))
