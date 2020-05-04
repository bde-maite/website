<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>MAITE</title>
        <meta name="description" content="Site internete du BDE MAITE" />
        <link rel="shortcut icon" href="<?php echo(get_src('./img/logo.png')); ?>" type="image/vnd.microsoft.icon">
        <link rel="stylesheet" href="<?php echo(get_src('./css/style.css')); ?>" />
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" />
        <script src="https://kit.fontawesome.com/902b444792.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
    </head>
    <body>
        
        <div class="mobile-navigation" id="mobile-nav" style="left: -300px;">
            <button type="button" name="button" id="close-nav"><i class="fas fa-arrow-left"></i></button>
            <div class="header-mobile-navigation">
                <img src="<?php echo(get_src('./img/logo.png')); ?>" class="logo" style="width: 200px;" alt="logo">
            </div>
            <div class="body-mobile-navigation">
                <div class="navtel">
                    <p>Navigation</p>
                </div>
                <ul>
                    <li><a href="/" target="_self"><i class="fas fa-home"></i> Accueil</a></li>
                    <li><a href="/page1" target="_self"><i class="fas fa-question"></i> Page 1</a></li>
                    <li><a href="/page2" target="_self"><i class="fas fa-question"></i> Page 2</a></li>
                    <li><a href="/page3" target="_self"><i class="fas fa-question"></i> Page 3</a></li>
                    <li><a href="/espace-membre#scroll" target="_self" style="color: #f39c12;"><i class="fas fa-gem"></i> Espace membre</a></li>
                </ul>
            </div>
        </div>


        <header>

            <div class="header1">
                <div class="container">
                    <ul>
                        <a href="/discord" target="_blank" style="text-decoration: none;">
                            <ul class="elt-direct">
                                <li class="elt-principal"><i class="fab fa-discord"></i></li>
                            </ul>
                            <ul class="elt-direct-secondaire">
                                <li class="elt-secondaire"><h3>Discord</h3></li>
                                <li class="elt-secondaire"><p><span id="discord-count"></span> Rejoignez-nous !</p></li>
                            </ul>
                        </a>
                    </ul>
                </div>
            </div>

            <nav id="navbar" style="background: none; margin: 20px 0px; padding: 0px;">

                <div class="container">
                    <!-- <ul class="reseaux responsive-reseaux">
                        <li><a target="_blank" href="https://lien1.gg"><i class="fab fa-instagram"></i></a></li>
                        <li><a target="_blank" href="https://lien2.gg"><i class="fab fa-facebook"></i></a></li>
                        <li><a target="_blank" href="https://lien2.gg"><i class="fab fa-twitter"></i></a></li>
                    </ul> -->
                    <ul class="navigation-liens responsive-navigation-liens">
                        <li id="button-open"><button type="button" id="open-nav" name="button" style="display: none;"><i class="fas fa-bars"></i></button></li>
                        <div id="cacher" style="display: inline-block;">
                            <li><a href="/" target="_self"><i class="fas fa-home"></i> Accueil</a></li>
                            <!-- <li><a href="/page1" target="_self"><i class="fas fa-question"></i> Page 1</a></li>
                            <li><a href="/page2" target="_self"><i class="fas fa-question"></i> Page 2</a></li>
                            <li><a href="/page3" target="_self"><i class="fas fa-question"></i> Page 3</a></li> -->
                            <li><a href="/espace-membre#scroll" target="_self" style="color: #f39c12;"><i class="fas fa-gem"></i> Espace membre</a></li>
                        </div>
                    </ul>
                </div>

            </nav>


        </header>

        <div class="mid-section" id="scroll">
            <div class="container" style="padding-top: 40px;padding-bottom: 40px;">
                <div class="col-flex">
                    <ul class="elt-direct">
                        <li class="elt-principal"><i class="fas fa-calendar-check"></i></li>
                    </ul>
                    <ul class="elt-direct-secondaire">
                        <li class="elt-secondaire"><h3>0</h3></li>
                        <li class="elt-secondaire"><p>Evenements organisés</p></li>
                    </ul>
                </div>

                <img src="<?php echo(get_src('./img/logo.png')); ?>" class="logo-mid" alt="logo" />

                <div class="col-flex flex-right">
                    <ul class="elt-direct-secondaire" style="margin-right: 0px;">
                        <li class="elt-secondaire"><h3><?php echo(getMemberCount($db, $connection)); ?></h3></li>
                        <li class="elt-secondaire"><p>Membres actifs</p></li>
                    </ul>
                    <ul class="elt-direct" style="margin-left: 15px;">
                        <li class="elt-principal"><i class="fas fa-users"></i></li>
                    </ul>
                </div>
            </div>
        </div>

