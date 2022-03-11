@extends('layouts.error')

@section('title', __('error.Page Expired'))
@section('code', '419')
@section('message', __('error.Page Expired'))
@section('text-color', 'warning')
@section('body-class', 'sidebar-mini')
@section('breadcrumbs', \Breadcrumbs::render('errors.419'))
