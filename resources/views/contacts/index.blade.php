@extends('tableau.neutre')

@section('content')

<div class="container-fluid py-4">

    {{-- Bouton retour --}}
    <div class="mb-3">
        <button 
            onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
            class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Retour
        </button>
    </div>

    {{-- Message succès --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ $message }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-lg rounded-4">

        {{-- En-tête --}}
        <div class="card-header bg-primary text-white rounded-top-4 py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <h3 class="mb-0 fw-bold">
                    <i class="bi bi-envelope-fill"></i>
                    LISTE DES CONTACTS
                </h3>

            </div>
        </div>

        {{-- Corps --}}
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle table-bordered">

                    <thead class="table-dark text-center">
                        <tr>
                            <th style="width: 70px;">N°</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Objet</th>
                            <th>Message</th>
                            <th style="width: 220px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($contacts as $contact)

                            <tr>

                                <td class="text-center fw-bold">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <span class="fw-semibold text-dark">
                                        {{ $contact->nom }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-info text-dark p-2">
                                        {{ $contact->email }}
                                    </span>
                                </td>

                                <td>
                                    <span class="fw-semibold">
                                        {{ $contact->objet }}
                                    </span>
                                </td>

                                <td>
                                    {{ Str::limit($contact->message, 80) }}
                                </td>

                                <td>

                                    <div class="d-flex gap-2 justify-content-center">

                                        {{-- Voir --}}
                                        <a href="{{ route('contacts.show', $contact->id) }}" 
                                           class="btn btn-warning btn-sm shadow-sm">
                                            <i class="bi bi-eye-fill"></i>
                                            Voir
                                        </a>

                                        {{-- Supprimer --}}
                                        <form action="{{ route('contacts.destroy', $contact->id) }}" 
                                              method="POST"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer ce contact ?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm shadow-sm">
                                                <i class="bi bi-trash-fill"></i>
                                                Supprimer
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center py-5">

                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-5"></i>

                                        <h5 class="mt-3">
                                            Aucun contact disponible
                                        </h5>
                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $contacts->links() }}
            </div>

        </div>
    </div>
</div>

@endsection