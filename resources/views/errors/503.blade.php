@extends('layouts.error')

@section('title', __('error.Service Unavailable'))
@section('code', '503')
@section('message', __('error.Service Unavailable'))
@section('text-color', 'secondary')
@section('body-class', 'sidebar-mini')
@section('breadcrumbs', \Breadcrumbs::render('errors.503'))
