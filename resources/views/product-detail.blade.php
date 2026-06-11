<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>Le Glorieux</title>

        <!-- CSS FILES -->
        <link rel="preconnect" href="https://fonts.googleapis.com">

        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;700;900&display=swap" rel="stylesheet">

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-icons.css" rel="stylesheet">

        <link rel="stylesheet" href="css/slick.css"/>

        <link href="css/tooplate-little-fashion.css" rel="stylesheet">
<!--

Tooplate 2127 Little Fashion

https://www.tooplate.com/view/2127-little-fashion

-->
    </head>
    
    <body>

        <section class="preloader">
            <div class="spinner">
                <span class="sk-inner-circle"></span>
            </div>
        </section>
    
        <main>

            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <a class="navbar-brand" href="">
                        <strong><span>Le</span> Glorieux</strong>
                    </a>

                   
                </div>
            </nav>

            <section class="product-detail section-padding">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-6 col-12">
                            <div class="product-thumb">
                            <div class="img-box">
                        <img src="images/hero-bg.jpg" alt="">
                      </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-12">
                            <div class="product-info d-flex">
                                <div>
                                    <h2 class="product-title mb-0">Comptabilité</h2>

                                    <!--<p class="product-p">Original package design from house</p>-->
                                </div>

                               
                            </div>

                            <div class="product-cart-thumb row">
                            <p>
                            <div class="col-lg-6 col-12">
                                    
                                    <select class="form-select cart-form-select" id="inputGroupSelect01">
                                        <option selected>Sélectionnez la Classe</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                </p>
                            </div>
                            
                            <div class="product-cart-thumb row">
                            <p>
                            <div class="col-lg-6 col-12">
                                    
                                    <select class="form-select cart-form-select" id="inputGroupSelect01">
                                        <option selected>Sélectionnez le Nom de l'élève</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                </p>
                                <div class="col-lg-6 col-12 mt-4 mt-lg-0">
                                    <button type="submit" class="btn custom-btn cart-btn" data-bs-toggle="modal" data-bs-target="#cart-modal">Add to Cart</button>
                                </div>

                                <!--<p>
                                    <a href="#" class="product-additional-link">Details</a>

                                    <a href="#" class="product-additional-link">Delivery and Payment</a>
                                </p>-->
                            </div>

                        </div>

                    </div>
                </div>
            </section>
        </main>

        <!-- CART MODAL -->
        <div class="modal fade" id="cart-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header flex-column">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 col-12 mt-4 mt-lg-0">
                            <div class="img-box">
                        <img src="images/lg.png" alt="">
                      </div>
                            </div>

                            <div class="col-lg-6 col-12 mt-3 mt-lg-0">
                                <h3 class="modal-title" id="exampleModalLabel">Versement de Scolarité</h3>

                                <form action="{{ route('annees.show') }}" method="POST">
    
                               
                            </div>
                            
                            <div class="product-cart-thumb row">
                            <p>
                            <div class="col-lg-6 col-12">
                                    
                                    <select class="form-select cart-form-select" id="inputGroupSelect01">
                                        <option selected>Sélectionnez le Versement</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                </p>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="row w-50">
                            <button type="button" class="btn custom-btn cart-btn ms-lg-4">Checkout</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- JAVASCRIPT FILES -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/Headroom.js"></script>
        <script src="js/jQuery.headroom.js"></script>
        <script src="js/slick.min.js"></script>
        <script src="js/custom.js"></script>

    </body>
</html>