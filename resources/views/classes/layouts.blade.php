<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Scolaire - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 220px;
            background-color: #0d6efd;
            color: white;
            flex-shrink: 0;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: rgba(255,255,255,0.1);
        }
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        .card-header.bg-gradient {
            background: linear-gradient(90deg, #0d6efd, #20c997);
            color: white;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column p-3">
        <h4 class="text-center mb-4">📚 Gestion Scolaire</h4>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-1">
                <a href="#" class="nav-link text-white"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
            </li>
            <li class="nav-item mb-1">
                <a href="#" class="nav-link text-white"><i class="bi bi-people me-2"></i> Élèves</a>
            </li>
            <li class="nav-item mb-1">
                <a href="#" class="nav-link text-white"><i class="bi bi-book me-2"></i> Classes</a>
            </li>
            <li class="nav-item mb-1">
                <a href="#" class="nav-link text-white"><i class="bi bi-wallet2 me-2"></i> Paiements</a>
            </li>
            <li class="nav-item mb-1">
                <a href="#" class="nav-link text-white"><i class="bi bi-bar-chart-line me-2"></i> Rapports</a>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <div class="content">

        <!-- Dashboard Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Élèves</h5>
                        <p class="card-text fs-4 fw-bold">120</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Classes</h5>
                        <p class="card-text fs-4 fw-bold">8</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Paiements</h5>
                        <p class="card-text fs-4 fw-bold">65%</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Budgets</h5>
                        <p class="card-text fs-4 fw-bold">9 500 000 FCFA</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Élèves Table -->
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-gradient rounded-top-4">
                <h4 class="mb-0"><i class="bi bi-people me-2"></i> Liste des Élèves</h4>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Matricule</th>
                                <th>Nom & Prénom</th>
                                <th>Classe</th>
                                <th>Sexe</th>
                                <th>Date de naissance</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>231220250034</td>
                                <td>Ademola Kolawolé</td>
                                <td>6ème A</td>
                                <td>M</td>
                                <td>12/02/2010</td>
                                <td class="d-flex justify-content-center gap-1">
                                    <a href="#" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash3"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>231220250035</td>
                                <td>Fatoumata Diallo</td>
                                <td>5ème B</td>
                                <td>F</td>
                                <td>25/08/2011</td>
                                <td class="d-flex justify-content-center gap-1">
                                    <a href="#" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash3"></i></button>
                                </td>
                            </tr>
                            <!-- Autres élèves -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
