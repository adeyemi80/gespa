@include('entete.entete')

<!-- Nos Offres Section -->
<section class="py-5 bg-light">
    <div class="container">
        <!-- Header -->
        <div class="row mb-5 text-center">
            <div class="col-12">
                <div class="display-5 fw-bold text-primary mb-3">
                    📚 Nos Offres Éducatives
                </div>
                <p class="lead text-muted mb-0">
                    Découvrez nos programmes adaptés à chaque niveau scolaire
                </p>
            </div>
        </div>

        <!-- Cards Grid -->
        <div class="row g-4">
            <!-- Enseignement Maternel -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0 hover-lift">
                    <div class="card-img-top overflow-hidden rounded-top">
                        <img src="{{ asset('images/maternel.jpg') }}" 
                             alt="Enseignement Maternel" 
                             class="img-fluid w-100" 
                             style="height: 220px; object-fit: cover;">
                    </div>
                    <div class="card-body d-flex flex-column text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-baby-carriage display-4 text-warning mb-2"></i>
                            <h5 class="card-title fw-bold text-primary mb-2">Enseignement Maternel</h5>
                        </div>
                        <p class="card-text flex-grow-1 text-muted mb-3">
                            Petits apprentissages joyeux pour les tout-petits
                            <br><strong>Petite & Moyenne Section</strong>
                        </p>
                        <a href="/serviceReadm" class="btn btn-primary btn-lg w-100 mt-auto">
                            <i class="fas fa-arrow-right me-2"></i>Lire Plus
                        </a>
                    </div>
                </div>
            </div>

            <!-- Enseignement Primaire -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0 hover-lift">
                    <div class="card-img-top overflow-hidden rounded-top">
                        <img src="{{ asset('images/primaire.jpg') }}" 
                             alt="Enseignement Primaire" 
                             class="img-fluid w-100" 
                             style="height: 220px; object-fit: cover;">
                    </div>
                    <div class="card-body d-flex flex-column text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-book-open display-4 text-info mb-2"></i>
                            <h5 class="card-title fw-bold text-primary mb-2">Enseignement Primaire</h5>
                        </div>
                        <p class="card-text flex-grow-1 text-muted mb-3">
                            Fondamentaux solides pour l'avenir
                            <br><strong>CI à CM2</strong>
                        </p>
                        <a href="/serviceReadp" class="btn btn-primary btn-lg w-100 mt-auto">
                            <i class="fas fa-arrow-right me-2"></i>Lire Plus
                        </a>
                    </div>
                </div>
            </div>

            <!-- Enseignement Secondaire -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card h-100 shadow-sm border-0 hover-lift">
                    <div class="card-img-top overflow-hidden rounded-top">
                        <img src="{{ asset('images/secondaire.jpg') }}" 
                             alt="Enseignement Secondaire" 
                             class="img-fluid w-100" 
                             style="height: 220px; object-fit: cover;">
                    </div>
                    <div class="card-body d-flex flex-column text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-graduation-cap display-4 text-success mb-2"></i>
                            <h5 class="card-title fw-bold text-primary mb-2">Enseignement Secondaire</h5>
                        </div>
                        <p class="card-text flex-grow-1 text-muted mb-3">
                            Excellence académique garantie
                            <br><strong>6<sup>ème</sup> → Terminale A/C/D</strong>
                        </p>
                        <a href="/serviceReads" class="btn btn-primary btn-lg w-100 mt-auto">
                            <i class="fas fa-arrow-right me-2"></i>Lire Plus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.hover-lift {
    transition: all 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}
.card-img-top {
    transition: transform 0.3s ease;
}
.hover-lift:hover .card-img-top {
    transform: scale(1.05);
}
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
