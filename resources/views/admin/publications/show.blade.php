@extends('adminlte::page')

@section('title', 'Detalles de Publicación')

@section('content_header')
    <h1>Detalles de Publicación</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-right">
                <a href="{{ route('admin.publications.edit', $publication->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('admin.publications.index') }}" class="btn btn-secondary btn-sm">
                     <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px">ID</th>
                            <td>{{ $publication->id }}</td>
                        </tr>
                        <tr>
                            <th>Título</th>
                            <td>{{ $publication->title }}</td>
                        </tr>
                        <tr>
                            <th>Autor</th>
                            <td>{{ $publication->author }}</td>
                        </tr>
                        <tr>
                            <th>Año de Publicación</th>
                            <td>{{ $publication->publication_year }}</td>
                        </tr>
                        <tr>
                            <th>Revista/Journal</th>
                            <td>{{ $publication->journal ?? 'No especificado' }}</td>
                        </tr>
                        <tr>
                            <th>DOI</th>
                            <td>
                                @if($publication->doi)
                                    <a href="https://doi.org/{{ $publication->doi }}" target="_blank">{{ $publication->doi }}</a>
                                @else
                                    No especificado
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Resumen/Abstract</th>
                            <td>{!! $publication->abstract ?? 'Sin resumen' !!}</td>
                        </tr>
                        <tr>
                            <th>Fecha de Creación</th>
                            <td>{{ $publication->created_at ? $publication->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Última Actualización</th>
                            <td>{{ $publication->updated_at ? $publication->updated_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">Archivo PDF</h5>
                        </div>
                        <div class="card-body text-center">
                            @if($publication->hasPdfFile())
                                <a href="{{ $publication->getPdfUrl() }}" target="_blank" class="btn btn-lg btn-info">
                                    <i class="fas fa-file-pdf fa-2x"></i>
                                    <div class="mt-2">Ver PDF</div>
                                </a>
                            @else
                                <div class="alert alert-info">
                                    No hay archivo PDF disponible
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">Categorías de Biodiversidad Relacionadas</h5>
                        </div>
                        <div class="card-body">
                            @if($publication->biodiversityCategories->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Nombre Científico</th>
                                                <th>Reino</th>
                                                <th>Estado de Conservación</th>
                                                <th>Extracto Relevante</th>
                                                <th>Referencia</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($publication->biodiversityCategories as $category)
                                                <tr>
                                                    <td>{{ $category->name }}</td>
                                                    <td><em>{{ $category->scientific_name }}</em></td>
                                                    <td>{{ $kingdoms[$category->kingdom] ?? $category->kingdom }}</td>
                                                    <td>
                                                        @php
                                                            $statusColors = [
                                                                'EX' => 'danger',
                                                                'EW' => 'danger',
                                                                'CR' => 'danger',
                                                                'EN' => 'warning',
                                                                'VU' => 'warning',
                                                                'NT' => 'info',
                                                                'LC' => 'success',
                                                                'DD' => 'secondary',
                                                                'NE' => 'secondary',
                                                            ];
                                                            $statusColor = $statusColors[$category->conservation_status] ?? 'secondary';
                                                        @endphp
                                                        <span class="badge badge-{{ $statusColor }}">
                                                            {{ $conservationStatuses[$category->conservation_status] ?? $category->conservation_status }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($category->pivot->relevant_excerpt)
                                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#excerptModal{{ $category->id }}">
                                                                Ver extracto
                                                            </button>
                                                            
                                                            <!-- Modal para mostrar el extracto -->
                                                            <div class="modal fade" id="excerptModal{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="excerptModalLabel{{ $category->id }}" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="excerptModalLabel{{ $category->id }}">Extracto relevante para "{{ $category->name }}"</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>{{ $category->pivot->relevant_excerpt }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">No disponible</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $category->pivot->page_reference ?? 'N/A' }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.biodiversity.show', $category->id) }}" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    No hay categorías de biodiversidad relacionadas con esta publicación.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table th {
            background-color: #f8f9fa;
        }
    </style>
@stop