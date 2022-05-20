<!DOCTYPE html>
<html lang="zxx">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="/myNFTStorage/codecanyon/img/favicon.png">
        <title>Frizerie</title>
        <link rel="stylesheet" href="/myNFTStorage/codecanyon/css/bootstrap.min.css">
        <link rel="stylesheet" href="/myNFTStorage/codecanyon/css/line-awesome.min.css">
        <link rel="stylesheet" href="/myNFTStorage/codecanyon/css/magnific-popup.css">
        <link rel="stylesheet" href="/myNFTStorage/codecanyon/css/swiper.min.css">
        <link rel="stylesheet" href="/myNFTStorage/codecanyon/css/style.css">
    </head>
    <body>

        <!-- loader -->
        <div class="loader">
            <div class="loading">
                <div class="spinner-grow aloader" role="status">
                    <span class="sr-only"></span>
                </div>
            </div>
        </div>
        <!-- end loader -->


        <!-- navbar -->
        <div id="navbar" class="navbar navbar-expand-lg">
            <div class="container">
                <a href="#" class="navbar-brand"><img src="/myNFTStorage/codecanyon/img/logo.png" alt=""></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="la la-bars"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#why">Why</a></li>
                        <li class="nav-item"><a class="nav-link" href="#how">How</a></li>
                        <li class="nav-item"><a class="nav-link" href="#what">What</a></li>
                        <li class="nav-item"><a class="nav-link" href="#who">Who</a></li>
                        <li class="nav-item"><a class="nav-link" href="#testimonial">Testimonial</a></li>
                        <li class="nav-item"><a class="nav-link" href="#where">Where</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- end navbar -->


        <!-- intro -->
        <div id="home" class="intro">
            <div class="container">
                <div class="row g-5 align-items-center reverse">
                    <div class="col-md">
                        <div class="content-img">
                            <img src="/myNFTStorage/codecanyon/img/maneki_intro.png" alt="">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="content">
                            <h1>Multiverse NFT</h1>
                            <p>Multiverse NFT is a new concept of NFTs where character image combinations are being sold rather than unique character images. Our aim is to protect the underlying asset of NFTs while at the same time providing awareness to the public about what people are actually buying.</p>
                            <button class="button">Visit Us at OpenSea</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- end intro -->



        <!-- why -->
        <div id="why" class="why">
            <div class="container">
                <div class="row g-5">
                    <div class="col-md">
                        <h2>Why Multiverse NFT?</h2>
                        <div class="content">
                            <p>Whenever people ask us why we buy NFTs, we provide different levels of explanations. From the most complex explanation which is about exclusivity and economy to the simplest conversation as follows:</p>
                            <p>Person A: Why you buy this NFT? I can easily steal its image by simply right clicking it. That's so dumb.</p>
                            <p>Person B: So you steal my NFT huh?! Try sells it at OpenSea then...</p>
                            <p>Which, technically speaking, is not wrong, but maybe not good enough. A secret source is lacking.</p>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="content-right">
                            <img src="/myNFTStorage/codecanyon/img/maneki_why.png" alt="">
                            <div class="ceo">
                                <p>The question is,
                                    <br/>
                                    <strong>Why Don't We Just Make It Impossible For Them To Steal?</strong>
                                </p>
                                <span>PapaSeal</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end why -->

        <!-- how -->
        <div id="how" class="how">
            <div class="container">
                <div class="row g-5">
                    <div class="col-md">
                        <a href="{{ route('nftstorage.multiverse.image', ['sha256' => '8d94179d0989f6f99ec7b55c5f2d590ccdeca0f7a7a4c25a671259cc93e47f8b', 'multiverse' => 'maneki_zodiac']) }}" class="popup-image"><img src="/myNFTStorage/codecanyon/img/how1.png" alt="" class="img1"></a>
                    </div>
                    <div class="col-md">
                        <a href="{{ route('nftstorage.multiverse.image', ['sha256' => '244bb24e51b88aecc08dee721d486d0f418b2de2ca219be707ac00247c8bd47f', 'multiverse' => 'maneki_zodiac']) }}" class="popup-image"><img src="/myNFTStorage/codecanyon/img/how2.png" alt="" class="img2"></a>
                    </div>
                    <div class="col-md-6">
                        <h2>How does Multiverse NFT works?</h2>
                        <div class="content">
                            <p>Take these 2 images as examples.</p>
                            <p>The one on the left is called ADAM whereas the one on the right is called EVE. They both represent 2 exclusive NFTs for the first batch of Multiverse NFTs, which are not for sale.</p>
                            <p>Now, try downloading them onto your own computer. Great, now try refreshing the page. Did you see any difference between them with the images you just downloaded? Correct, they are not the same.</p>
                            <p>You can try to do the same thing again and again, and yes, if you are lucky enough, you will get the same image, but that's not the whole point of this form of NFTs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end  how -->

        <!-- what -->
        <div id="what" class="what">
            <div class="container">
                <div class="row g-5">
                    <div class="col-md-7">
                        <h2>How does Multiverse NFT works?</h2>
                        <div class="content">
                            <p>Why are these images not the same?</p>
                            <p>This is because each Multiverse NFT has more than one what we called the State.</p>
                            <p>Take ADAM as an example, it has 25 states, which also means if you want to "steal" this NFT completely, you need to first at least refresh this page 25 times and you need to be lucky enough to get all 25 unique states of the collection. After that, find a way to combine them so that you are not wasting your local memory spaces. Pretty cool, wasn't it?</p>
                            <p>The EVE here has only 12 states, but unlike ADAM, which is originated from 1 single image, EVE originated from a custom number of images uploaded by creators. In this case, it is us.</p>
                            <p>This shows the potential of how the Multiverse NFTs can change the perspectives of people who buy any NFT. We are not buying the underlying asset actually. We are buying the credits promised by the sellers, the exclusivity provided by the creators, and the perspectives shared by the community, and this is what an NFT is. It is a code, on chain.</p>
                        </div>
                    </div>
                    <div class="col-md">
                        <a href="/myNFTStorage/codecanyon/img/what.png" class="popup-image"><img src="/myNFTStorage/codecanyon/img/gallery1.png" alt="" class="img1"></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end  what -->

        <!-- what -->
        <div id="what" class="what">
            <div class="row g-5">
                <div class="col-md-7">
                    <h2>What is Multiverse NFT trying to solve?</h2>
                    <div class="content">
                        <p>We break down each batch of Multiverse NFTs into different problems that we are trying and most importantly are able to solve. Coins generated by these NFTs represent credits for using those solutions for free.</p>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="content">
                                <p></p>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="content">
                                <p></p>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="content">
                                <p></p>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="content">
                                <p></p>
                            </div>
                        </div>

                    </div>
                    <div class="swiper-button-prev prev-slide"></div>
                    <div class="swiper-button-next next-slide"></div>
                </div>
            </div>
        </div>
        <!-- end what -->

        <!-- who -->
        <div id="who" class="who">
            <div class="container">
                <h2>Multiverse NFT Team</h2>
                <div class="row g-5">
                    <div class="col-md">
                        <div class="content">
                            <img src="/myNFTStorage/codecanyon/img/who1.png" alt="">
                            <h5>PapaSeal</h5>
                            <h6>Developer</h6>
                            <ul>
                                <li><a href=""><i class="la la-twitter"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="content">
                            <img src="/myNFTStorage/codecanyon/img/who2.png" alt="">
                            <h5>Camper</h5>
                            <h6>Blockchain Developer</h6>
                            <ul>
                                <li><a href=""><i class="la la-twitter"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="content">
                            <img src="/myNFTStorage/codecanyon/img/who3.png" alt="">
                            <h5>Alisa</h5>
                            <h6>Artist</h6>
                            <ul>
                                <li><a href=""><i class="la la-twitter"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="content">
                            <img src="/myNFTStorage/codecanyon/img/who4.png" alt="">
                            <h5>Kimpembe</h5>
                            <h6>Social Media Manager</h6>
                            <ul>
                                <li><a href=""><i class="la la-twitter"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end who -->

        <!-- where -->
        <div id="where" class="where">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-md">
                        <div class="open">
                            <h3>Monday - Saturday</h3>
                            <span>08:00 AM - 12.00 PM</span>
                            <h3>Sunday</h3>
                            <span>Closed</span>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="row">
                            <div class="col">
                                <div class="icon"><i class="la la-phone"></i></div>
                                <div class="text">
                                    <h5>Terms and Conditions</h5>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="icon"><i class="la la-envelope"></i></div>
                                <div class="text">
                                    <h5>Privacy Policy</h5>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="icon"><i class="la la-university"></i></div>
                                <div class="text">
                                    <h5>OpenSea</h5>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <ul>
                                    <li><a href=""><i class="lab la-discord"></i></a></li>
                                    <li><a href=""><i class="la la-twitter"></i></a></li>
                                    <li><a href=""><i class="las la-shopping-cart"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end where -->

        <!-- script -->
        <script src="/myNFTStorage/codecanyon/js/jquery.min.js"></script>
        <script src="/myNFTStorage/codecanyon/js/bootstrap.bundle.min.js"></script>
        <script src="/myNFTStorage/codecanyon/js/magnific-popup.min.js"></script>
        <script src="/myNFTStorage/codecanyon/js/swiper.min.js"></script>
        <script src="/myNFTStorage/codecanyon/js/main.js"></script>


    </body>
</html>
