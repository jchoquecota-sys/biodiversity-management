@extends('adminlte::page')

@section('title', 'Detalles de Categoría de Biodiversidad')

@section('content_header')
    <h1>Detalles de Categoría de Biodiversidad</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-right">
                <a href="{{ route('admin.biodiversity.edit', $biodiversity->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('admin.biodiversity.index') }}" class="btn btn-secondary btn-sm">
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
                            <td>{{ $biodiversity->id }}</td>
                        </tr>
                        <tr>
                            <th>Nombre</th>
                            <td>{{ $biodiversity->name }}</td>
                        </tr>
                        <tr>
                            <th>Nombre Científico</th>
                            <td><em>{{ $biodiversity->scientific_name }}</em></td>
                        </tr>
                        <tr>
                            <th>Jerarquía Taxonómica</th>
                            <td>
                                @if($biodiversity->familia)
                                    <div class="taxonomic-hierarchy">
                                        <div class="mb-2">
                                            <strong>Clase:</strong> 
                                            <span class="badge badge-info">{{ $biodiversity->familia->orden->clase->nombre ?? 'N/A' }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Orden:</strong> 
                                            <span class="badge badge-primary">{{ $biodiversity->familia->orden->nombre ?? 'N/A' }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Familia:</strong> 
                                            <span class="badge badge-success">{{ $biodiversity->familia->nombre }}</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Sin clasificación taxonómica</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Reino</th>
                            <td>{{ $kingdoms[$biodiversity->kingdom] ?? $biodiversity->kingdom }}</td>
                        </tr>
                        <tr>
                            <th>Estado de Conservación</th>
                            <td>
                                @php
                                    $conservationStatusObj = $conservationStatusesFromDB->get($biodiversity->conservation_status);
                                    $statusColor = $conservationStatusObj ? $conservationStatusObj->color : 'secondary';
                                    $statusName = $conservationStatusObj ? $conservationStatusObj->name : $biodiversity->conservation_status;
                                @endphp
                                <span class="badge bg-{{ $statusColor }}">
                                    {{ $statusName }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Hábitat</th>
                            <td>{{ $biodiversity->habitat ?? 'No especificado' }}</td>
                        </tr>
                        <tr>
                            <th>Descripción</th>
                            <td>{!! $biodiversity->description ?? 'Sin descripción' !!}</td>
                        </tr>
                        <tr>
                            <th>Fecha de Creación</th>
                            <td>{{ $biodiversity->created_at ? $biodiversity->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Última Actualización</th>
                            <td>{{ $biodiversity->updated_at ? $biodiversity->updated_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                Imágenes 
                                @if($biodiversity->getImageCount() > 1)
                                    <span class="badge badge-light">{{ $biodiversity->getImageCount() }}</span>
                                @endif
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $allImages = $biodiversity->getAllImageUrls();
                            @endphp
                            @if(!empty($allImages))
                                <div class="image-gallery-detail">
                                    @foreach($allImages as $index => $imageUrl)
                                        <div class="mb-3">
                                            <img src="{{ $imageUrl }}" alt="{{ $biodiversity->name }} - Imagen {{ $index + 1 }}" class="img-fluid rounded cursor-pointer" onclick="showImageModal('{{ $imageUrl }}', '{{ addslashes($biodiversity->name) }} - Imagen {{ $index + 1 }}')"> 
                                            <small class="text-muted d-block mt-1 text-center">Imagen {{ $index + 1 }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-image fa-2x text-muted mb-2"></i><br>
                                    No hay imágenes disponibles
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
                            <h5 class="card-title mb-0">Publicaciones Relacionadas</h5>
                        </div>
                        <div class="card-body">
                            @if($biodiversity->publications->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Título</th>
                                                <th>Autor</th>
                                                <th>Año</th>
                                                <th>Extracto Relevante</th>
                                                <th>Referencia</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($biodiversity->publications as $publication)
                                                <tr>
                                                    <td>{{ $publication->title }}</td>
                                                    <td>{{ $publication->author }}</td>
                                                    <td>{{ $publication->publication_year }}</td>
                                                    <td>
                                                        @if($publication->pivot->relevant_excerpt)
                                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#excerptModal{{ $publication->id }}">
                                                                Ver extracto
                                                            </button>
                                                            
                                                            <!-- Modal para mostrar el extracto -->
                                                            <div class="modal fade" id="excerptModal{{ $publication->id }}" tabindex="-1" role="dialog" aria-labelledby="excerptModalLabel{{ $publication->id }}" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="excerptModalLabel{{ $publication->id }}">Extracto de "{{ $publication->title }}"</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>{{ $publication->pivot->relevant_excerpt }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">No disponible</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $publication->pivot->page_reference ?? 'N/A' }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.publications.show', $publication->id) }}" class="btn btn-sm btn-info">
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
                                    No hay publicaciones relacionadas con esta categoría de biodiversidad.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para visualizar imagen en tamaño completo -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Imagen de Biodiversidad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid" style="max-height: 500px;">
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
        .cursor-pointer {
            cursor: pointer;
        }
        .image-gallery-detail img:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: all 0.2s ease;
        }
    </style>
@stop

@section('js')
    <script>
        // Función para mostrar el modal de imagen
        function showImageModal(imageUrl, imageName) {
            $('#modalImage').attr('src', imageUrl);
            $('#modalImage').attr('alt', imageName);
            $('#imageModalLabel').text('Imagen de ' + imageName);
            $('#imageModal').modal('show');
        }
    </script>
@stop