@extends('admin::layouts.error')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __('Page Expired'))
@section('text-color', 'warning')
@section('body-class', 'sidebar-mini')
@section('breadcrumbs', \Breadcrumbs::render())
